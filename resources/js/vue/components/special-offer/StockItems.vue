<template>
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
</template>

<script>
import StockItem from "../stock-search/stock-items/StockItem";
import { mapState } from "vuex";

export default {
  props: {
    stockItems: {
      type: Array,
      required: true,
    },
  },

  components: {
    StockItem,
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
  },
};
</script>