<template>
    <div
        class="grid grid-cols-1 gap-10 grid-auto-rows md:grid-cols-2 xl:grid-cols-4 py-10 px-standard"
    >
        <div
            class="h-48 flex flex-col justify-center items-center text-center h-full"
        >
            <div class="text-endeavour text-h3 mb-5">
                Compare upto 3 caravans
            </div>
            <div class="w-full mb-5" v-if="caravans.length > 0">
                <a
                    :href="caravanComparisonPageUrl"
                    v-if="caravans.length > 1 && caravanComparisonPageUrl"
                    class="flex justify-center items-center h-10 bg-endeavour text-white w-full"
                >
                  View Comparison
                </a>
            </div>
            <div>
                <a
                    v-if="caravans.length > 0"
                    class="text-endeavour underline cursor-pointer"
                    @click="onRemoveAllClick"
                >
                  Clear all
                </a>
            </div>
        </div>
        <div class="h-48 bg-white border h-full">
            <StockItem
                v-if="hasComparisonItemAtIndex(0)"
                :id="caravans[0].id"
                :stock-type="caravans[0].stock_type"
                :attention-grabber="caravans[0].attention_grabber"
                :detail-page-url="caravans[0].detail_page_url"
                :images="caravans[0].images"
                :year="caravans[0].year"
                :berths="caravans[0].berths"
                :condition="caravans[0].condition"
                :currency-symbol="caravans[0].currency_symbol"
                :managers-special="caravans[0].managers_special"
                :price="caravans[0].price"
                :recommended-price="caravans[0].recommended_price"
                :initial-delivery-date="caravans[0].delivery_date"
                :special-offers="caravans[0].special_offers"
                :show-add-to-comparison-button="shouldShowComparisonButton"
                :mileage="caravans[0].mileage"
                :transmission="caravans[0].transmission"
                :manufacturer="caravans[0].manufacturer"
                :model="caravans[0].model"
                :unique-code="caravans[0].unique_code"
            />
        </div>
        <div class="h-48 bg-white border h-full">
            <StockItem
                v-if="hasComparisonItemAtIndex(1)"
                :id="caravans[1].id"
                :stock-type="caravans[1].stock_type"
                :attention-grabber="caravans[1].attention_grabber"
                :detail-page-url="caravans[1].detail_page_url"
                :images="caravans[1].images"
                :year="caravans[1].year"
                :berths="caravans[1].berths"
                :condition="caravans[1].condition"
                :currency-symbol="caravans[1].currency_symbol"
                :managers-special="caravans[1].managers_special"
                :price="caravans[1].price"
                :recommended-price="caravans[1].recommended_price"
                :initial-delivery-date="caravans[1].delivery_date"
                :special-offers="caravans[1].special_offers"
                :show-add-to-comparison-button="shouldShowComparisonButton"
                :mileage="caravans[1].mileage"
                :transmission="caravans[1].transmission"
                :manufacturer="caravans[1].manufacturer"
                :model="caravans[1].model"
                :unique-code="caravans[1].unique_code"
            />
        </div>
        <div class="h-48 bg-white border h-full">
            <StockItem
                v-if="hasComparisonItemAtIndex(2)"
                :id="caravans[2].id"
                :stock-type="caravans[2].stock_type"
                :attention-grabber="caravans[2].attention_grabber"
                :detail-page-url="caravans[2].detail_page_url"
                :images="caravans[2].images"
                :year="caravans[2].year"
                :berths="caravans[2].berths"
                :condition="caravans[2].condition"
                :currency-symbol="caravans[2].currency_symbol"
                :managers-special="caravans[2].managers_special"
                :price="caravans[2].price"
                :recommended-price="caravans[2].recommended_price"
                :initial-delivery-date="caravans[2].delivery_date"
                :special-offers="caravans[2].special_offers"
                :show-add-to-comparison-button="shouldShowComparisonButton"
                :mileage="caravans[2].mileage"
                :transmission="caravans[2].transmission"
                :manufacturer="caravans[2].manufacturer"
                :model="caravans[2].model"
                :unique-code="caravans[2].unique_code"
            />
        </div>
    </div>
</template>

<script>
import { mapActions, mapState } from "vuex";
import { formatPrice } from "../../../utilities/helpers";
import StockItem from "../../stock-search/stock-items/StockItem";

export default {
    components: {
        StockItem
    },
    computed: {
        ...mapState("comparison", ["caravans", "caravanComparisonPageUrl"])
    },
    methods: {
        ...mapActions("comparison", ["removeAllCaravans"]),
        onRemoveAllClick() {
            this.removeAllCaravans();
        },
        getFormattedPrice: (currencySymbol, price) => {
            if (price == null) {
                return "£TBA";
            }
            return formatPrice(currencySymbol, price);
        },
        hasComparisonItemAtIndex(index) {
            return this.caravans.hasOwnProperty(index);
        },
        shouldShowComparisonButton() {
            return null !== this.caravanComparisonPageUrl;
        }
    }
};
</script>
