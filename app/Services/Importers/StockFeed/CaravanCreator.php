<?php

namespace App\Services\Importers\StockFeed;

use App\Models\Berth;
use App\Models\Caravan;
use App\Models\CaravanRange;
use App\Models\CaravanStockItem;
use App\Models\SpecialOffer;
use App\Services\Importers\StockFeed\Exceptions\NoBerthsException;
use App\Services\Importers\StockFeed\Exceptions\NoLayoutException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class CaravanCreator extends BaseCreator
{
    public const SINGLE_AXLE_VALUES = ["SA", "1"];

    public function call(): bool
    {
        DB::beginTransaction();
        try {
            $layout = $this->findOrCreateLayout();
            $manufacturer = $this->findOrCreateManufacturer();
            $range = $this->createCaravanRange($manufacturer->id);
            $dealer = $this->findDealer();
            $stockItem = $this->createCaravanStockItem([
                'layout_id' => $layout->id,
                'manufacturer_id' => $manufacturer->id,
                'caravan_range_id' => $range->id ?? null,
                'dealer_id' => $dealer->id ?? null,
            ]);
            $this->syncBerths($stockItem);
            $this->createFeedStockItemVideo($stockItem, $this->fetchValueFromFeedItemData('WebVideoURL', ''));
            $this->syncSpecialOffers($stockItem);
            $this->createFeatures($stockItem);
            $this->createImages($stockItem);

            DB::commit();
            return true;
        } catch (NoBerthsException $e) {
            DB::rollBack();
            return false;
        } catch (NoLayoutException $e) {
            DB::rollBack();
            return false;
        } catch (Throwable $e) {
            Log::error($e);
            DB::rollBack();
            return false;
        }
    }

    private function createCaravanRange(int $manufacturerId): ?CaravanRange
    {
        $rangeName = $this->fetchValueFromFeedItemData("WebModel");

        if (!$rangeName) {
            return null;
        }

        $caravanRange = CaravanRange::firstOrNew(
            ['name' => $rangeName],
            [
                'manufacturer_id' => $manufacturerId,
                'prepend_range_name_to_model_names' => false,
                'overview' => '',
                'position' => 1,
            ]
        );

        $caravanRange->save();
        return $caravanRange;
    }

    private function createCaravanStockItem(array $associationData): CaravanStockItem
    {
        $uniqueCode = $this->fetchValueFromFeedItemData("UniqueCode");
        $caravanData = $this->fetchCaravanData();
        $data = array_merge($caravanData, $associationData);
        $caravan = CaravanStockItem::firstOrNew([
            'unique_code' => $uniqueCode,
        ]);
        $caravan->fill($data);
        $caravan->live = true;
        if ($caravan->searchIndexShouldBeUpdated()) {
            $caravan->save();
        } else {
            CaravanStockItem::withoutSyncingToSearch(function () use ($caravan) {
                $caravan->save();
            });
        }
        return $caravan;
    }

    private function syncBerths(CaravanStockItem $caravanStockItem): void
    {
        $berths = $this->fetchValueFromFeedItemData('Berths');
        if (is_null($berths)) {
            throw new NoBerthsException('No berths provided in feed');
        }
        $berth = Berth::firstOrCreate([
            'number' => $berths,
        ]);

        $caravanStockItem->berths()->sync($berth);
    }

    /**
     * Sync without detaching because Marquis need to be able to add individual stock items to special offers.
     *
     * We don't want to detach any set this way.
     */
    private function syncSpecialOffers(CaravanStockItem $stockItem): void
    {
        $specialOfferIds = $this->fetchSpecialOfferIds($stockItem);

        $stockItem->specialOffers()->syncWithoutDetaching($specialOfferIds);
    }

    private function fetchSpecialOfferIds(CaravanStockItem $stockItem): array
    {
        $eligibleSpecialOffers = collect([]);

        $specialOffers = SpecialOffer::where('link_used_caravan_stock', true)
            ->orWhere('link_on_sale_stock', true)
            ->orWhere('link_managers_special_stock', true)
            ->orWhere('link_feed_special_offers_caravans', true)
            ->get();

        if ($stockItem->isUsed()) {
            $usedSpecialOffers = $specialOffers->where('link_used_caravan_stock', true);
            $eligibleSpecialOffers->push($usedSpecialOffers);
        }

        if ($stockItem->isOnSale()) {
            $onSaleSpecialOffers = $specialOffers->where('link_on_sale_stock', true);
            $eligibleSpecialOffers->push($onSaleSpecialOffers);
        }

        if ($stockItem->isManagersSpecial()) {
            $managersSpecialSpecialOffers = $specialOffers->where('link_managers_special_stock', true);
            $eligibleSpecialOffers->push($managersSpecialSpecialOffers);
        }

        if ($stockItem->special_offer) {
            $feedSpecialOffersCaravans = $specialOffers->where('link_feed_special_offers_caravans');
            $eligibleSpecialOffers->push($feedSpecialOffersCaravans);
        }

        return $eligibleSpecialOffers->flatten()->pluck('id')->toArray();
    }

    /**
     * Use 0 or 1 for true and false otherwise getDirty fails because it doesn't seem to cast on loading from the database
     */
    private function fetchCaravanData(): array
    {
        /**
         * The feed fields are slightly confusing.
         *
         * The "SalePrice" field in the feed means the "now" price.
         * The "Price" field in the feed means the "was" price.
         *
         * If the "now" price is lower than the "was" price, the stock item is on offer.
         * So we set the "recommended_price" to the value of the "was" price and the "price" to the value of the "now" price.
         *
         * If the "now" price is higer than the "was" price (which is possible), we need to ignore the "was" price.
         * In this case we set the "recommended_price" to the value of the "now" price, and leave the "price" field null.
         */
        $feedWasPrice = $this->fetchValueFromFeedItemData("SalePrice");
        $feedNowPrice = $this->fetchValueFromFeedItemData("Price");

        if ($feedWasPrice > $feedNowPrice) {
            $recommendedPrice = $feedWasPrice;
            $price = $feedNowPrice;
        } else {
            $recommendedPrice = $feedNowPrice;
            $price = null;
        }

        return [
            "attention_grabber" => $this->fetchValueFromFeedItemData("AttentionGrabber"),
            "axles" => $this->calculateAxles($this->fetchValueFromFeedItemData("Axles")),
            "condition" => $this->fetchValueFromFeedItemData("Condition"),
            "demonstrator" => $this->fetchValueFromFeedItemData("Demonstrator", 0),
            "description" => $this->fetchValueFromFeedItemData("VehicleDescription"),
            "height" => $this->fetchValueFromFeedItemData("Height"),
            "length" => $this->fetchValueFromFeedItemData("OverallLength"),
            "mtplm" => $this->fetchValueFromFeedItemData("MTPLM"),
            "model" => $this->fetchModel(),
            "number_of_owners" => $this->fetchValueFromFeedItemData("Owners", 1),
            "payload" => $this->fetchValueFromFeedItemData("EstimatedPayload"),
            "recommended_price" => $recommendedPrice,
            "price" => $price,
            "registration_date" => $this->fetchValueFromFeedItemData("RegYear"),
            "source" => CaravanStockItem::FEED_SOURCE,
            "mro" => $this->fetchValueFromFeedItemData("MIRO"),
            "width" => $this->fetchValueFromFeedItemData("Width"),
            "year" => $this->fetchValueFromFeedItemData("Year"),
            "managers_special" => $this->fetchValueFromFeedItemData("Exclusive", 0),
            "delivery_date" => $this->fetchValueFromFeedItemData("DeliveryDate"),
            "reduced_price_start_date" => $this->fetchValueFromFeedItemData("ReducedPriceDate"),
            "special_offer" => $this->fetchValueFromFeedItemData("SpecialOffer", 0),
        ];
    }


    private function calculateAxles(?string $value): string
    {
        if (in_array($value, static::SINGLE_AXLE_VALUES)) {
            return Caravan::AXLE_SINGLE;
        }
        return Caravan::AXLE_TWIN;
    }
}
