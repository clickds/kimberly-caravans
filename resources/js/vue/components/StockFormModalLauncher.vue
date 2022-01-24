<template>
  <div
    :class="`print:hidden py-2 md:px-2 w-full flex flex-col md:flex-row items-center md:items-stretch text-center stock-modal-icons ${size}`"
  >
    <button
      class="my-2 stock-form-modal-opener"
      @click="handleOpenModal('requestDemonstration')"
    >
      <div class="icon-container" v-html="bookViewingIcon"></div>
      <h5
        class="text-endeavour font-semibold"
        :class="'large' === size ? 'text-lg md:text-xl' : 'text-sm'"
      >
        Book a Viewing
      </h5>
    </button>

    <button
      class="my-2 stock-form-modal-opener"
      @click="handleOpenModal('makeOffer')"
    >
      <div class="icon-container" v-html="makeOfferIcon"></div>
      <h5
        class="text-endeavour font-semibold"
        :class="'large' === size ? 'text-lg md:text-xl' : 'text-sm'"
      >
        Make an Offer
      </h5>
    </button>

    <button
      class="my-2 stock-form-modal-opener"
      @click="handleOpenModal('reserveVehicle')"
    >
      <div class="icon-container" v-html="reserveIcon"></div>
      <h5
        class="text-endeavour font-semibold"
        :class="'large' === size ? 'text-lg md:text-xl' : 'text-sm'"
      >
        Reserve now
      </h5>
    </button>
    <stock-form-modal
      :initial-show-modal="showModal"
      :initial-help-method="helpMethodClicked"
      :dealers="dealers"
      :recaptcha-site-key="recaptchaSiteKey"
      :item-details="itemDetails"
      @close-stock-form-modal="handleCloseModal"
    />
  </div>
</template>

<script>
  import slugify from "slugify";
  import StockFormModal from './StockFormModal.vue';

  export default {
    components: {
      StockFormModal,
    },
    props: {
      size: {
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
        required: true
      },
      itemDetails: {
        type: String,
        required: true
      },
      recaptchaSiteKey: {
        type: String,
        required: true,
      },
    },
    data: function () {
      return {
        showModal: false,
        helpMethodClicked: null,
      };
    },
    methods: {
      handleOpenModal: function (helpMethod) {
        this.helpMethodClicked = helpMethod;
        this.showModal = true;
      },
      handleCloseModal: function () {
        this.helpMethodClicked = null;
        this.showModal = false;
      },
    },
  };
</script>