<template>
  <div class="w-full">
    <div
      class="container mx-auto flex flex-wrap py-5 px-standard"
      v-for="(model, index) in models"
      :key="index"
    >
      <div class="w-full md:w-1/3 flex flex-col justify-center">
        <div>
          <h1 class="font-bold text-endeavour mb-4">{{ model.formattedName }}</h1>
          <compare-button 
            v-if="model.stockItem"
            :id="model.stockItem.id"
            :stock-type="vehicleType"
            button-type="full"
            class="mb-4"
          />
          {{ model.description }}
        </div>
        <stock-form-modal-launcher
          :size="modalLauncherSize"
          :book-viewing-icon="bookViewingIcon"
          :make-offer-icon="makeOfferIcon"
          :reserve-icon="reserveIcon"
          :item-details="model.stockFormDetails"
          :dealers="dealers"
          :recaptcha-site-key="recaptchaSiteKey"
        />
      </div>
      <div
        class="w-full md:w-1/3 flex flex-col justify-center"
      >
        <div
          class="px-2 w-full my-2 image-center"
          v-if="model.dayFloorplanImageUrl"
        >
          <img :src="model.dayFloorplanImageUrl" />
        </div>
        <div
          class="px-2 w-full my-2 image-center"
          v-if="model.nightFloorplanImageUrl"
        >
          <img :src="model.nightFloorplanImageUrl" />
        </div>
      </div>
      <div
        class="w-full md:w-1/3 flex flex-col items-center justify-center md:flex-row"
      >
        <div class="mb-5 md:mb-0 w-full md:w-1/2 flex flex-col items-center justify-center text-center text-endeavour font-heading font-semibold">
          <div class="text-2xl">Berths</div>
          <div class="vehicle-icon endeavour">
            <div class="column icon-container" v-html="berthIcon"></div>
            <div class="column number-container">
              {{ model.berthString }}
            </div>
          </div>
        </div>
        <div
          class="w-full md:w-1/2 flex flex-col items-center justify-center text-center text-endeavour font-heading font-semibold"
          v-if="model.seatString"
        >
          <div class="text-2xl">Seat Belts</div>
          <div class="vehicle-icon endeavour">
            <div class="column icon-container" v-html="designatedSeatsIcon"></div>
            <div class="column number-container">
              {{ model.seatString }}
            </div>
          </div>
        </div>
      </div>
    </div>
    <slot></slot>
  </div>
</template>

<script>
import CompareButton from '../stock-search/CompareButton';

export default {
  components: {
    CompareButton,
  },
  props: {
    models: {
      type: Array,
      required: true,
    },
    berthIcon: {
      type: String,
      required: true,
    },
    designatedSeatsIcon: {
      type: String,
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
    vehicleType: {
      type: String,
      required: true,
    },
  },
};
</script>
