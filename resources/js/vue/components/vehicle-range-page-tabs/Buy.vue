<template>
  <div class="w-full">
    <div class="w-full bg-white">
      <div class="container mx-auto py-5 px-standard">
        <h1 class="py-3 md:py-6 text-center text-endeavour">
          {{ selectedModel.formattedName }} Prices
        </h1>
        <div class="flex flex-row items-center justify-center space-x-5">
          <div class="text-endeavour font-bold text-xl">Model select</div>
          <select v-model="selectedModel" class="p-3 border">
            <option
              v-for="(model, index) in models"
              :key="index"
              :value="model"
            >
              {{ model.formattedName }}
            </option>
          </select>
        </div>
      </div>
    </div>
    <div class="w-full bg-gallery py-4">
      <div class="container mx-auto px-standard flex flex-wrap">
        <div class="px-2 w-full lg:w-2/5">
          <h2>{{ selectedModel.year }} OTR Price</h2>
          <div
            class="text-2xl md:text-6xl xl:text-price text-endeavour font-bold"
          >
            <div v-if="selectedModel.hasReducedPrice">
              <div class="text-lg md:text-3xl">Now only</div>
              <div
                class="-mt-6 xl:-mt-10"
                v-html="selectedModel.formattedPrice"
              ></div>
            </div>
            <div v-else v-html="selectedModel.formattedRecommendedPrice"></div>
          </div>
          <div
            class="-mt-6 xl:-mt-10 text-2xl md:text-4xl xl:text-6xl text-shiraz font-bold"
            v-if="selectedModel.hasReducedPrice"
          >
            <span class="text-md md:text-lg xl:text-xl">Save</span
            ><span v-html="selectedModel.formattedSaving"></span>
          </div>
        </div>
        <div class="px-12 w-full lg:w-3/5 flex items-center text-center">
          <stock-form-modal-launcher
            :size="modalLauncherSize"
            :book-viewing-icon="bookViewingIcon"
            :make-offer-icon="makeOfferIcon"
            :reserve-icon="reserveIcon"
            :item-details="selectedModel.stockFormDetails"
            :dealers="dealers"
            :recaptcha-site-key="recaptchaSiteKey"
          />
        </div>
      </div>
    </div>

    <slot></slot>

    <div
      class="w-full bg-white container mx-auto px-standard py-5"
      v-if="selectedModel.price"
    >
      <finance-calculator
        :item="selectedModel.name"
        :locale="siteLocale"
        :currency-code="siteCurrencyCode"
        :initial-price="selectedModel.price"
        :credit-indicator-url="creditIndicatorUrl"
        :credit-indicator-desktop-image-url="creditIndicatorDesktopImageUrl"
        :credit-indicator-mobile-image-url="creditIndicatorMobileImageUrl"
      />
    </div>
  </div>
</template>

<script>
import FinanceCalculator from "../FinanceCalculator";

export default {
  components: {
    FinanceCalculator,
  },
  props: {
    models: {
      type: Array,
      required: true,
    },
    siteLocale: {
      required: true,
    },
    siteCurrencyCode: {
      required: true,
    },
    modalLauncherSize: {
      type: String,
      required: true,
    },
    bookViewingIcon: {
      type: String,
      required: true,
    },
    makeOfferIcon: {
      type: String,
      required: true,
    },
    reserveIcon: {
      type: String,
      required: true,
    },
    dealers: {
      type: Array,
      required: true,
    },
    recaptchaSiteKey: {
      type: String,
      required: true,
    },
    creditIndicatorUrl: {
      type: String,
      required: true,
    },
    creditIndicatorDesktopImageUrl: {
      type: String,
      required: true,
    },
    creditIndicatorMobileImageUrl: {
      type: String,
      required: true,
    },
  },
  data: function () {
    return {
      selectedModel: this.models[0],
    };
  },
};
</script>
