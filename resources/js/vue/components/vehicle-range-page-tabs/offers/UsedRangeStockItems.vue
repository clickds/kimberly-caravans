<template>
  <div v-if="stockItems.length > 0">
    <div class="text-endeavour text-lg mb-4">You may be interested in</div>
    <div class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
      <div v-for="(stockItem, index) in stockItems" :key="index" class="flex flex-col justify-end">
        <StockItem
          :key="stockItem.id"
          :id="stockItem.id"
          :attention-grabber="stockItem.attention_grabber"
          :stock-type="stockItem.stock_type"
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
    <div class="mt-4 w-full" v-if="searchPageUrl">
      <a
        :href="searchPageUrl"
        class="block p-2 bg-shiraz hover:bg-monarch text-xl text-white text-center font-heading font-semibold"
      >
        View all
      </a>
    </div>
  </div>
</template>

<script>
import StockItem from "../../stock-search/stock-items/StockItem";
import { mapState } from "vuex";
import { stockSearch } from "../../../utilities/api";

export default {
  components: {
    StockItem,
  },
  props: {
    apiSearchUrl: {
      type: String,
      required: true,
    },
    rangeName: {
      type: String,
      required: true,
    },
    rangeFilter: {
      type: String,
      required: true,
    },
    usedStockFilter: {
      type: String,
      required: true,
    },
    searchPageUrl: {
      type: String,
      required: true,
    }
  },
  data: function () {
    return {
      stockItems: [],
    };
  },
  computed: {
    ...mapState("comparison", [
      "motorhomeComparisonPageUrl",
      "caravanComparisonPageUrl",
    ]),
  },
  methods: {
    shouldShowComparisonButton: function (stockType) {
      switch (stockType) {
        case "motorhome":
          return null !== this.motorhomeComparisonPageUrl;
        case "caravan":
          return null !== this.caravanComparisonPageUrl;
        default:
          return false;
      }
    },
    fetchUsedRangeStockItems: async function () {
      await stockSearch(
        this.apiSearchUrl,
        {
          status: [this.usedStockFilter],
          range: [this.rangeName]
        },
        {},
        {
          per_page: 4,
        },
        {}
      )
      .then((response) => {
        this.stockItems = response.data.data;
      })
      .catch((error) => {
        console.error(error);
      });
    },
  },
  created() {
    this.fetchUsedRangeStockItems();
  },
};
</script>