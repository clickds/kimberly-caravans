<template>
    <div
        class="grid grid-cols-1 gap-10 grid-auto-rows md:grid-cols-2 xl:grid-cols-4 py-10 px-standard"
    >
        <div
            class="h-48 flex flex-col justify-center items-center text-center h-full"
        >
            <div class="text-endeavour text-h3 mb-5">
                Compare upto 3 motorhomes
            </div>
            <div class="w-full mb-5" v-if="motorhomes.length > 0">
                <a
                    :href="motorhomeComparisonPageUrl"
                    v-if="motorhomes.length > 1 && motorhomeComparisonPageUrl"
                    class="flex justify-center items-center h-10 bg-endeavour text-white w-full"
                >
                  View Comparison
                </a>
            </div>
            <div>
                <a
                    v-if="motorhomes.length > 0"
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
                :id="motorhomes[0].id"
                :stock-type="motorhomes[0].stock_type"
                :attention-grabber="motorhomes[0].attention_grabber"
                :detail-page-url="motorhomes[0].detail_page_url"
                :images="motorhomes[0].images"
                :year="motorhomes[0].year"
                :berths="motorhomes[0].berths"
                :condition="motorhomes[0].condition"
                :currency-symbol="motorhomes[0].currency_symbol"
                :managers-special="motorhomes[0].managers_special"
                :price="motorhomes[0].price"
                :recommended-price="motorhomes[0].recommended_price"
                :initial-delivery-date="motorhomes[0].delivery_date"
                :special-offers="motorhomes[0].special_offers"
                :show-add-to-comparison-button="shouldShowComparisonButton"
                :mileage="motorhomes[0].mileage"
                :transmission="motorhomes[0].transmission"
                :manufacturer="motorhomes[0].manufacturer"
                :model="motorhomes[0].model"
                :unique-code="motorhomes[0].unique_code"
            />
        </div>
        <div class="h-48 bg-white border h-full">
            <StockItem
                v-if="hasComparisonItemAtIndex(1)"
                :id="motorhomes[1].id"
                :stock-type="motorhomes[1].stock_type"
                :attention-grabber="motorhomes[1].attention_grabber"
                :detail-page-url="motorhomes[1].detail_page_url"
                :images="motorhomes[1].images"
                :year="motorhomes[1].year"
                :berths="motorhomes[1].berths"
                :condition="motorhomes[1].condition"
                :currency-symbol="motorhomes[1].currency_symbol"
                :managers-special="motorhomes[1].managers_special"
                :price="motorhomes[1].price"
                :recommended-price="motorhomes[1].recommended_price"
                :initial-delivery-date="motorhomes[1].delivery_date"
                :special-offers="motorhomes[1].special_offers"
                :show-add-to-comparison-button="shouldShowComparisonButton"
                :mileage="motorhomes[1].mileage"
                :transmission="motorhomes[1].transmission"
                :manufacturer="motorhomes[1].manufacturer"
                :model="motorhomes[1].model"
                :unique-code="motorhomes[1].unique_code"
            />
        </div>
        <div class="h-48 bg-white border h-full">
            <StockItem
                v-if="hasComparisonItemAtIndex(2)"
                :id="motorhomes[2].id"
                :stock-type="motorhomes[2].stock_type"
                :attention-grabber="motorhomes[2].attention_grabber"
                :detail-page-url="motorhomes[2].detail_page_url"
                :images="motorhomes[2].images"
                :year="motorhomes[2].year"
                :berths="motorhomes[2].berths"
                :condition="motorhomes[2].condition"
                :currency-symbol="motorhomes[2].currency_symbol"
                :managers-special="motorhomes[2].managers_special"
                :price="motorhomes[2].price"
                :recommended-price="motorhomes[2].recommended_price"
                :initial-delivery-date="motorhomes[2].delivery_date"
                :special-offers="motorhomes[2].special_offers"
                :show-add-to-comparison-button="shouldShowComparisonButton"
                :mileage="motorhomes[2].mileage"
                :transmission="motorhomes[2].transmission"
                :manufacturer="motorhomes[2].manufacturer"
                :model="motorhomes[2].model"
                :unique-code="motorhomes[2].unique_code"
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
        ...mapState("comparison", ["motorhomes", "motorhomeComparisonPageUrl"])
    },
    methods: {
        ...mapActions("comparison", ["removeAllMotorhomes"]),
        onRemoveAllClick() {
            this.removeAllMotorhomes();
        },
        getFormattedPrice: (currencySymbol, price) => {
            if (price == null) {
                return "£TBA";
            }
            return formatPrice(currencySymbol, price);
        },
        hasComparisonItemAtIndex(index) {
            return this.motorhomes.hasOwnProperty(index);
        },
        shouldShowComparisonButton() {
            return null !== this.motorhomeComparisonPageUrl;
        }
    }
};
</script>
