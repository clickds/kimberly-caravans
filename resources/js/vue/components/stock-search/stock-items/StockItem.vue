<template>
  <div class="border border-gallery text-sm font-medium bg-white">
    <div class="offer-bars">
      <SpecialOfferBar
        v-for="specialOffer in specialOffers"
        :key="specialOffer.id"
        :background-colour="specialOffer.stock_bar_colour"
        :bar-text="barText(specialOffer)"
        :text-colour="specialOffer.stock_bar_text_colour"
        :icon-svg="specialOffer.icon_svg"
        :pages="specialOffer.pages"
      ></SpecialOfferBar>
    </div>
    <a v-bind:href="detailPageUrl">
      <div class="-mx-1 flex">
        <div class="px-1 w-2/3 md:w-full">
          <img :src="firstImageUrl" class="w-full h-full" />
        </div>
        <div class="px-1 w-1/3 md:hidden flex flex-col justify-between">
          <img
            :src="imageUrl"
            v-for="(imageUrl, index) in otherImageUrls"
            :key="imageUrl"
            class="w-full h-auto"
            :class="[index === 0 ? 'mb-2' : '']"
          />
        </div>
      </div>
    </a>
    <div class="px-4 py-2">
      <p class="text-xs mb-2" v-if="uniqueCode">Stock Ref: {{ uniqueCode }}</p>
      <a v-bind:href="detailPageUrl">
        <div>{{ manufacturer }}</div>
        <div class="text-lg mb-1">{{ model }}</div>
      </a>
      <p class="mb-1">{{ mainDetails }}</p>
      <p v-if="mileage">{{ mileage.toLocaleString() }} miles</p>
      <p>{{ attentionGrabber }}</p>
    </div>
    <div class="font-bold border-t border-gray-500 px-4 py-2 grid grid-cols-3">
      <div class="flex flex-col justify-center">
        <div class="flex flex-col" v-if="showPrice">
          <div class="text-xs text-silver-gray -mb-2" v-if="isNew && onSale">
            Now OTR from
          </div>
          <div
            class="text-xs text-silver-gray -mb-2"
            v-else-if="!isNew && onSale"
          >
            Now
          </div>
          <div class="text-xs text-silver-gray -mb-2" v-else-if="isNew">
            OTR from
          </div>
          <div class="text-lg">{{ formattedPrice }}</div>
        </div>
      </div>
      <div class="flex flex-col justify-center">
        <div class="flex flex-col text-monarch" v-if="showPrice && onSale">
          <div class="text-xs -mb-2">Save</div>
          <div class="text-lg">{{ formattedSaving }}</div>
        </div>
      </div>
      <div
        v-if="showAddToComparisonButton"
        class="flex flex-col justify-center items-end"
      >
        <compare-button
          :id="id"
          :stock-type="stockType"
          :price="currentPrice"
        />
      </div>
    </div>
  </div>
</template>

<script>
import SpecialOfferBar from "./SpecialOfferBar";
import CompareButton from "../CompareButton";
import { formatPrice } from "../../../utilities/helpers";

export default {
  components: { CompareButton, SpecialOfferBar },
  props: [
    "id",
    "stockType",
    "attentionGrabber",
    "detailPageUrl",
    "images",
    "managersSpecial",
    "model",
    "year",
    "berths",
    "condition",
    "currencySymbol",
    "price",
    "recommendedPrice",
    "initialDeliveryDate",
    "specialOffers",
    "showAddToComparisonButton",
    "mileage",
    "transmission",
    "manufacturer",
    "model",
    "uniqueCode",
  ],

  data: function () {
    return {
      deliveryDate: null,
      currentPrice: null,
      recommendedRetailPrice: null,
    };
  },

  created() {
    if (this.initialDeliveryDate) {
      this.deliveryDate = Date.parse(this.initialDeliveryDate);
    }
    if (this.price != null) {
      this.currentPrice = parseFloat(this.price);
    }
    if (this.recommendedPrice != null) {
      this.recommendedRetailPrice = parseFloat(this.recommendedPrice);
    }
  },

  computed: {
    firstImageUrl: function () {
      return this.images[0];
    },

    otherImageUrls: function () {
      return this.images
        .filter((imageUrl, index) => {
          // Not the first one and not the third because that is a "text slide"
          return ![0, 2].includes(index);
        })
        .slice(0, 2);
    },

    mainDetails: function () {
      const details = [
        this.berths.join("/") + " Berths",
        this.condition,
      ];
      if (this.transmission) {
        details.push(this.transmission);
      }

      return details.join(" | ");
    },

    showFrom: function () {
      if (this.seats && this.seats.length > 1) {
        return true;
      }
      return this.berths.length > 1;
    },

    onSale: function () {
      if (this.currentPrice == null || this.recommendedRetailPrice == null) {
        return false;
      }
      return this.currentPrice < this.recommendedRetailPrice;
    },

    saving: function () {
      if (this.currentPrice == null || this.recommendedRetailPrice == null) {
        return 0;
      }
      return this.recommendedRetailPrice - this.currentPrice;
    },

    formattedSaving: function () {
      return formatPrice(this.currencySymbol, this.saving);
    },

    formattedPrice: function () {
      if (this.onSale) {
        return formatPrice(this.currencySymbol, this.currentPrice);
      }

      return this.formattedRecommendedPrice;
    },

    formattedRecommendedPrice: function () {
      if (this.recommendedRetailPrice == null) {
        return "£TBA";
      }
      return formatPrice(this.currencySymbol, this.recommendedRetailPrice);
    },

    showPrice: function () {
      if (this.deliveryDate === null) {
        return true;
      }
      if (this.deliveryDate > Date.now()) {
        return false;
      }
      return true;
    },

    isNew: function () {
      return this.condition === "New";
    },
  },

  methods: {
    barText: function (specialOffer) {
      if (specialOffer.link_on_sale_stock) {
        return "Save " + this.formattedSaving;
      }
      return specialOffer.name;
    },
  },
};
</script>
