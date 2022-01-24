<?php

namespace App\Services\Importers\StockFeed;

use App\Models\Berth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;
use App\Models\Motorhome;
use App\Models\MotorhomeRange;
use App\Models\MotorhomeStockItem;
use App\Models\Seat;
use App\Models\SpecialOffer;
use App\Services\Importers\StockFeed\Exceptions\NoBerthsException;
use App\Services\Importers\StockFeed\Exceptions\NoLayoutException;

class MotorhomeCreator extends BaseCreator
{
    public const SINGLE_AXLE_VALUES = ["SA", "1"];

    public function call(): bool
    {
        DB::beginTransaction();
        try {
            $layout = $this->findOrCreateLayout();
            $manufacturer = $this->findOrCreateManufacturer();
            $range = $this->createMotorhomeRange($manufacturer->id);
            $dealer = $this->findDealer();
            $stockItem = $this->createMotorhomeStockItem([
                'layout_id' => $layout->id,
                'manufacturer_id' => $manufacturer->id,
                'motorhome_range_id' => $range->id ?? null,
                'dealer_id' => $dealer->id ?? null,
            ]);
            $this->syncBerths($stockItem);
            $this->syncSeats($stockItem);
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
            Log::info($this->feedItemData);
            Log::error($e);
            DB::rollBack();
            return false;
        }
    }

    private function createMotorhomeRange(int $manufacturerId): ?MotorhomeRange
    {
        $rangeName = $this->fetchValueFromFeedItemData("WebModel");

        if (!$rangeName) {
            return null;
        }

        $motorhomeRange = MotorhomeRange::firstOrNew(
            ['name' => $rangeName],
            [
                'manufacturer_id' => $manufacturerId,
                'prepend_range_name_to_model_names' => false,
                'overview' => '',
                'position' => 1,
            ]
        );

        $motorhomeRange->save();
        return $motorhomeRange;
    }

    private function createMotorhomeStockItem(array $associationData): MotorhomeStockItem
    {
        $uniqueCode = $this->fetchValueFromFeedItemData("UniqueCode");
        $motorhomeData = $this->fetchMotorhomeData();
        $data = array_merge($motorhomeData, $associationData);
        $motorhome = MotorhomeStockItem::firstOrNew([
            'unique_code' => $uniqueCode,
        ]);
        $motorhome->fill($data);
        $motorhome->live = true;
        if ($motorhome->searchIndexShouldBeUpdated()) {
            $motorhome->save();
        } else {
            MotorhomeStockItem::withoutSyncingToSearch(function () use ($motorhome) {
                $motorhome->save();
            });
        }
        return $motorhome;
    }

    private function syncBerths(MotorhomeStockItem $motorhomeStockItem): void
    {
        $berths = $this->fetchValueFromFeedItemData('Berths');
        if (is_null($berths)) {
            throw new NoBerthsException('No berths provided in feed');
        }
        $berth = Berth::firstOrCreate([
            'number' => $berths,
        ]);

        $motorhomeStockItem->berths()->sync($berth);
    }

    private function syncSeats(MotorhomeStockItem $motorhomeStockItem): void
    {
        $seats = $this->fetchValueFromFeedItemData('Seats');
        $seat = Seat::firstOrCreate([
            'number' => $seats,
        ]);

        $motorhomeStockItem->seats()->sync($seat);
    }

    /**
     * Sync without detaching because Marquis need to be able to add individual stock items to special offers.
     *
     * We don't want to detach any set this way.
     */
    private function syncSpecialOffers(MotorhomeStockItem $stockItem): void
    {
        $specialOfferIds = $this->fetchSpecialOfferIds($stockItem);

        $stockItem->specialOffers()->syncWithoutDetaching($specialOfferIds);
    }

    private function fetchSpecialOfferIds(MotorhomeStockItem $stockItem): array
    {
        $eligibleSpecialOffers = collect([]);

        $specialOffers = SpecialOffer::where('link_used_motorhome_stock', true)
            ->orWhere('link_on_sale_stock', true)
            ->orWhere('link_managers_special_stock', true)
            ->orWhere('link_feed_special_offers_motorhomes', true)
            ->get();

        if ($stockItem->isUsed()) {
            $usedSpecialOffers = $specialOffers->where('link_used_motorhome_stock', true);
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
            $feedSpecialOffersMotorhomes = $specialOffers->where('link_feed_special_offers_motorhomes');
            $eligibleSpecialOffers->push($feedSpecialOffersMotorhomes);
        }

        return $eligibleSpecialOffers->flatten()->pluck('id')->toArray();
    }

    /**
     * Use 0 or 1 for true and false otherwise getDirty fails because it doesn't seem to cast on loading from the database
     */
    private function fetchMotorhomeData(): array
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
            "chassis_manufacturer" => $this->calculateChassisManufacturer(),
            "condition" => $this->fetchValueFromFeedItemData("Condition"),
            "demonstrator" => $this->fetchValueFromFeedItemData("Demonstrator", 0),
            "description" => $this->fetchValueFromFeedItemData("VehicleDescription"),
            "engine_size" => $this->fetchValueFromFeedItemData("Engine"),
            "fuel" => $this->calculateFuel(),
            "height" => $this->fetchValueFromFeedItemData("Height"),
            "length" => $this->fetchValueFromFeedItemData("OverallLength"),
            "mtplm" => $this->fetchValueFromFeedItemData("MTPLM"),
            "mileage" => $this->fetchValueFromFeedItemData("Mileage"),
            "model" => $this->fetchModel(),
            "number_of_owners" => $this->fetchValueFromFeedItemData("Owners", 1),
            "payload" => $this->fetchValueFromFeedItemData("EstimatedPayload"),
            "recommended_price" => $recommendedPrice,
            "price" => $price,
            "registration_date" => $this->fetchValueFromFeedItemData("RegYear"),
            "registration_number" => $this->fetchValueFromFeedItemData("RegNo"),
            "source" => MotorhomeStockItem::FEED_SOURCE,
            "transmission" => $this->calculateTransmission(),
            "conversion" => $this->calculateConversion(),
            "mro" => $this->fetchValueFromFeedItemData("MIRO"),
            "width" => $this->fetchValueFromFeedItemData("Width"),
            "year" => $this->fetchValueFromFeedItemData("Year"),
            "managers_special" => $this->fetchValueFromFeedItemData("Exclusive", 0),
            "delivery_date" => $this->fetchValueFromFeedItemData("DeliveryDate"),
            "reduced_price_start_date" => $this->fetchValueFromFeedItemData("ReducedPriceDate"),
            "special_offer" => $this->fetchValueFromFeedItemData("SpecialOffer", 0),
        ];
    }

    private function calculateChassisManufacturer(): ?string
    {
        $value = $this->fetchValueFromFeedItemData("ChassisEngineType");
        if (is_null($value)) {
            return $value;
        }
        return Str::title($value);
    }

    private function calculateFuel(): string
    {
        switch ($this->fetchValueFromFeedItemData("CCFuel")) {
            case "T":
                return Motorhome::FUEL_TURBO_DIESEL;
            case "D":
                return Motorhome::FUEL_DIESEL;
            default:
                return Motorhome::FUEL_PETROL;
        }
    }

    private function calculateTransmission(): string
    {
        switch ($this->fetchValueFromFeedItemData("ManAuto")) {
            case 'A':
                return Motorhome::TRANSMISSION_AUTOMATIC;
            default:
                return Motorhome::TRANSMISSION_MANUAL;
        }
    }

    private function calculateConversion(): string
    {
        switch ($this->fetchValueFromFeedItemData("ConvType")) {
            case 'coachbuilt':
            case 'CB':
                return Motorhome::CONVERSION_COACHBUILT;
            case 'AC':
                return Motorhome::CONVERSION_A_CLASS;
            default:
                return Motorhome::CONVERSION_CAMPERVAN;
        }
    }
}
