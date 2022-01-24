<template>
  <div class="w-full grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4" :class="gap">
    <template v-for="(stockItem, index) in stockItems">
      <div :key="index" class="flex flex-col justify-end">
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

      <template v-if="showStockSearchLinks(index)">
        <div
          :key="'stock-search-links-' + index"
          class="md:col-span-2 xl:col-span-4 grid grid-cols-1 xl:grid-cols-2"
          :class="gap"
        >
          <div
            v-for="stockSearchLink in StockSearchLinks"
            :key="index + '-stock-search-link-' + stockSearchLink.id"
          >
            <StockSearchLink
              :id="stockSearchLink.id"
              :name="stockSearchLink.name"
              :link="stockSearchLink.link"
              :image="stockSearchLink.image"
              :mobile-image="stockSearchLink.mobile_image"
            ></StockSearchLink>
          </div>
        </div>
      </template>
    </template>
  </div>
</template>

<script>
import StockItem from "./StockItem";
import StockSearchLink from "./StockSearchLink";
import { mapState } from "vuex";

export default {
  components: {
    StockItem,
    StockSearchLink,
  },

  props: {
    StockSearchLinks: {
      type: Array,
      default: () => [],
    },
  },

  data: function () {
    return {
      gap: "gap-10",
    };
  },

  computed: {
    ...mapState(["stockItems"]),
    ...mapState("comparison", [
      "motorhomeComparisonPageUrl",
      "caravanComparisonPageUrl",
    ]),
  },

  methods: {
    showStockSearchLinks: function (index) {
      // Show after every 8th item
      return (index + 1) % 8 === 0;
    },

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