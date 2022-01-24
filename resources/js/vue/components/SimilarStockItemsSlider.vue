<template>
    <section v-if="stockItems.length > 0" class="bg-regal-blue print:hidden">
        <div class="container mx-auto px-standard py-4">
            <h3 class="text-center text-white font-medium mb-4">
                You may also be
                <span class="font-semibold text-h2">Interested</span> in these
                models
            </h3>
            <div ref="slidesContainer" class="flex">
                <div :key="stockItem.id" v-for="stockItem in stockItems">
                    <div class="flex flex-col justify-end h-full">
                        <StockItem
                            :id="stockItem.id"
                            :stock-type="stockItem.stock_type"
                            :attention-grabber="stockItem.attention_grabber"
                            :detail-page-url="stockItem.detail_page_url"
                            :images="stockItem.images"
                            :year="stockItem.year"
                            :berths="stockItem.berths"
                            :condition="stockItem.condition"
                            :currency-symbol="stockItem.currency_symbol"
                            :managers-special="stockItem.managers_special"
                            :price="stockItem.price"
                            :recommended-price="stockItem.recommended_price"
                            :initial-delivery-date="stockItem.delivery_date"
                            :special-offers="stockItem.special_offers"
                            :show-add-to-comparison-button="
                                shouldShowComparisonButton(stockItem.stock_type)
                            "
                            :mileage="stockItem.mileage"
                            :transmission="stockItem.transmission"
                            :manufacturer="stockItem.manufacturer"
                            :model="stockItem.model"
                            :unique-code="stockItem.unique_code"
                        />
                    </div>
                </div>
            </div>

            <div class="mx-auto my-4 md:my-8 w-full md:w-1/4">
                <div
                    ref="controlsContainer"
                    class="controls flex justify-around text-white"
                >
                    <div class="control" aria-label="Previous slide">
                        <i class="fas fa-chevron-left"></i>
                    </div>
                    <div class="control" aria-label="Next slide">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import { stockSearch } from "../utilities/api";
import StockItem from "./stock-search/stock-items/StockItem";
import { mapActions, mapState } from "vuex";
import { tns } from "tiny-slider/src/tiny-slider.js";

export default {
    components: {
        StockItem
    },

    props: {
        berths: {
            type: Array,
            required: true
        },
        conversion: {
            type: String,
            default: ""
        },
        layout: {
            type: String,
            required: true
        },
        price: {
            type: Number,
            default: null
        },
        stockItemId: {
            type: Number,
            required: true
        },
        url: {
          type: String,
          required: true
        }
    },

    data: function() {
        return {
            minPrice: this.price - 10000,
            maxPrice: this.price + 10000,
            slider: null,
            stockItems: []
        };
    },

    created() {
        this.fetchStock().then(() => {
            this.initialiseSlider();
        });
        this.initialiseComparison();
    },

    computed: {
        ...mapState("comparison", [
            "motorhomeComparisonPageUrl",
            "caravanComparisonPageUrl"
        ]),

        minBerths: function () {
          return Math.min(this.berths);
        },

        maxBerths: function () {
          return Math.max(this.berths);
        }
    },

    methods: {
        ...mapActions("comparison", {
            initialiseComparison: "initialise"
        }),

        shouldShowComparisonButton: function(stockType) {
            switch (this.type) {
                case "motorhome":
                    return null !== this.motorhomeComparisonPageUrl;
                case "caravan":
                    return null !== this.caravanComparisonPageUrl;
                default:
                    return false;
            }
        },

        fetchStock: async function() {
            const optionFilters = {
                layout: [this.layout]
            };
            if (this.type === "motorhome" && this.conversion) {
                optionFilters.conversion = [this.conversion];
            }
            const rangeFilters = {
                berths: {
                    min: this.minBerths,
                    max: this.maxBerths
                },
                price: {
                    min: this.minPrice,
                    max: this.maxPrice
                }
            };
            await stockSearch(
                this.url,
                optionFilters,
                rangeFilters,
                {
                    per_page: 16
                },
                {
                    exclude_ids: [this.stockItemId]
                }
            )
                .then(response => {
                    this.stockItems = response.data.data;
                })
                .catch(error => {
                    console.error(error);
                });
        },

        initialiseSlider: function() {
            this.slider = tns({
                container: this.$refs.slidesContainer,
                controlsContainer: this.$refs.controlsContainer,
                items: 1,
                autoplay: false,
                controls: true,
                gutter: 16,
                responsive: {
                    768: {
                        items: 2
                    },
                    1024: {
                        items: 4
                    }
                }
            });
        }
    }
};
</script>
