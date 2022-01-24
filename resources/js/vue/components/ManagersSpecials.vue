<template>
  <div class="bg-alabaster py-10" v-if="shouldDisplay">
    <div class="container mx-auto px-standard">
      <div v-if="hasCaravanManagersSpecials">
        <div class="flex flex-row font-heading font-bold mb-5">
          <div class="px-2 text-shiraz w-full">Caravan Manager's Special</div>
        </div>
        <div class="w-full grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-10 mb-10">
          <StockItem
            v-for="stockItem in caravanManagersSpecials"
            :key="stockItem.id"
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
            :show-add-to-comparison-button="shouldShowComparisonButton(stockItem.stock_type)"
            :mileage="stockItem.mileage"
            :transmission="stockItem.transmission"
            :manufacturer="stockItem.manufacturer"
            :model="stockItem.model"
            :unique-code="stockItem.unique_code"
          />
        </div>
      </div>
      <div v-if="hasMotorhomeManagersSpecials">
        <div class="flex flex-row font-heading font-bold mb-5">
          <div class="px-2 text-shiraz w-full">Motorhome Manager's Special</div>
        </div>
        <div class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
          <StockItem
            v-for="stockItem in motorhomeManagersSpecials"
            :key="stockItem.id"
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
            :show-add-to-comparison-button="shouldShowComparisonButton(stockItem.stock_type)"
            :mileage="stockItem.mileage"
            :transmission="stockItem.transmission"
            :manufacturer="stockItem.manufacturer"
            :model="stockItem.model"
            :unique-code="stockItem.unique_code"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { stockSearch } from "../utilities/api";
import StockItem from "./stock-search/stock-items/StockItem";
import { mapActions, mapState } from "vuex";

export default {
  components: {
    StockItem,
  },

  props: {
    caravanSearchUrl: {
      type: String,
      default: "",
    },
    motorhomeSearchUrl: {
      type: String,
      default: "",
    },
    dealerId: {
      type: Number,
      required: true,
    }
  },

  data: function () {
    return {
      caravanManagersSpecials: [],
      motorhomeManagersSpecials: [],
    };
  },

  created() {
    this.fetchManagersSpecials();
    this.initialiseComparison();
  },

  computed: {
    ...mapState("comparison", [
      "motorhomeComparisonPageUrl",
      "caravanComparisonPageUrl",
    ]),
    shouldDisplay: function () {
      return (
        this.caravanManagersSpecials.length > 0 ||
        this.motorhomeManagersSpecials.length > 0
      );
    },
    hasCaravanManagersSpecials: function () {
      return this.caravanManagersSpecials.length > 0;
    },
    hasMotorhomeManagersSpecials: function () {
      return this.motorhomeManagersSpecials.length > 0;
    },
  },

  methods: {
    ...mapActions("comparison", {
      initialiseComparison: "initialise",
    }),
    fetchManagersSpecials: async function () {
      await stockSearch(
        this.caravanSearchUrl,
        {},
        {},
        [],
        { dealer_id: this.dealerId }
      )
        .then((response) => {
          this.caravanManagersSpecials = response.data.data;
        })
        .catch((error) => {
          console.error(error);
        });

      await stockSearch(
        this.motorhomeSearchUrl,
        {},
        {},
        [],
        { dealer_id: this.dealerId }
      )
        .then((response) => {
          this.motorhomeManagersSpecials = response.data.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    shouldShowComparisonButton: function (stockType) {
      switch (stockType) {
        case "motorhome":
          return "" !== this.motorhomeComparisonPageUrl;
        case "caravan":
          return "" !== this.caravanComparisonPageUrl;
        default:
          return false;
      }
    },
  },
};
</script>
