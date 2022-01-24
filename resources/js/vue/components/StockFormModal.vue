<template>
  <modal v-if="showModal" @close="handleCloseModal">
    <div slot="body" class="stock-form text-left">
      <form v-if="showForm" id="stock-form" @submit.prevent="submitForm">
        <h3 class="heading">Interested in this vehicle</h3>

        <errors-display :errors="errors"></errors-display>

        <fieldset>
          <legend>Your contact details</legend>
          <div class="fields-container">
            <div class="-mx-2 flex flex-wrap">
              <div
                class="px-2 mb-4 w-full md:w-1/3 flex flex-col justify-between"
              >
                <label for="title">* Title</label>
                <input type="text" name="title" id="title" v-model="title" />
              </div>

              <div
                class="px-2 mb-4 w-full md:w-1/3 flex flex-col justify-between"
              >
                <label for="first_name">* First Name</label>
                <input
                  type="text"
                  name="first_name"
                  id="first_name"
                  v-model="firstName"
                />
              </div>

              <div
                class="px-2 mb-4 w-full md:w-1/3 flex flex-col justify-between"
              >
                <label for="surname">* Surname</label>
                <input
                  type="text"
                  name="surname"
                  id="surname"
                  v-model="surname"
                />
              </div>

              <div class="px-2 mb-4 w-full">
                <label for="email">* Email Address</label>
                <input type="text" name="email" id="email" v-model="email" />
              </div>

              <div
                class="px-2 mb-4 md:mb-0 w-full md:w-1/3 flex flex-col justify-between"
              >
                <label for="telephone_number">* Telephone Number</label>
                <input
                  type="text"
                  name="telephone_number"
                  id="telephone_number"
                  v-model="phoneNumber"
                />
              </div>

              <div class="px-2 w-full md:w-2/3 flex flex-col justify-between">
                <label for="county"
                  >* Your County
                  <span class="text-sm"
                    >(so we can establish which dealer is best for you e.g.
                    Yorkshire)</span
                  ></label
                >
                <input type="text" name="county" id="county" v-model="county" />
              </div>
            </div>
          </div>
        </fieldset>

        <fieldset>
          <legend>Please note</legend>
          <div class="fields-container">
            <div class="-mx-2 flex flex-wrap text-shiraz">
              Caravans can only be viewed at our specially selected caravan
              sites in Durham, South Yorkshire, Surrey, Suffolk and Exeter. New
              and used motorhome displays only at all other branches.
            </div>
          </div>
        </fieldset>

        <fieldset>
          <legend>How can we help</legend>
          <div class="fields-container">
            <div class="-mx-2 flex flex-wrap">
              <div class="px-2 w-full md:w-1/2 lg:w-1/3 mb-4">
                <label>
                  <input
                    type="checkbox"
                    name="ask_question"
                    v-model="askQuestion"
                  />
                  Ask a question about this vehicle
                </label>
              </div>

              <div class="px-2 w-full md:w-1/2 lg:w-1/3 mb-4">
                <label>
                  <input
                    type="checkbox"
                    name="request_callback"
                    v-model="requestCallback"
                  />
                  Request a callback
                </label>
              </div>

              <div class="px-2 w-full md:w-1/2 lg:w-1/3 mb-4">
                <label>
                  <input
                    type="checkbox"
                    name="request_demonstration"
                    v-model="requestDemonstration"
                  />
                  Request a demonstration
                </label>
              </div>

              <div class="px-2 w-full md:w-1/2 lg:w-1/3 mb-4">
                <label>
                  <input
                    type="checkbox"
                    name="request_video_tour"
                    v-model="requestVideoTour"
                  />
                  Request a video tour
                </label>
              </div>

              <div class="px-2 w-full md:w-1/2 lg:w-1/3 mb-4">
                <label>
                  <input
                    type="checkbox"
                    name="view_at_branch"
                    v-model="viewAtLocalBranch"
                  />
                  View at local branch
                </label>
              </div>

              <div class="px-2 w-full md:w-1/2 lg:w-1/3 mb-4">
                <label>
                  <input
                    type="checkbox"
                    name="reserve_vehicle"
                    v-model="reserveVehicle"
                  />
                  Reserve this vehicle
                </label>
              </div>

              <div class="px-2 w-full md:w-1/2 lg:w-1/3 mb-4">
                <label>
                  <input
                    type="checkbox"
                    name="make_offer"
                    v-model="makeOffer"
                  />
                  Make an offer on this vehicle
                </label>
              </div>

              <div class="px-2 w-full">
                <textarea
                  name="message"
                  id="message"
                  cols="30"
                  rows="10"
                  aria-label="Your message"
                  v-model="message"
                ></textarea>
              </div>
            </div>
          </div>
        </fieldset>

        <fieldset>
          <legend>Keep in touch</legend>
          <div class="fields-container">
            <p class="mb-2">
              From time to time we would like to contact you with information
              concerning our products, services and events which may be of
              interest to you. Please select your preferences below:
            </p>

            <p class="mb-2 font-bold">I’m interested in:</p>

            <div class="-mx-2 flex flex-wrap">
              <div
                v-for="option in ['caravans', 'motorhomes']"
                :key="option"
                class="px-2 mb-4 w-full md:w-1/2 lg:w-1/3 capitalize"
              >
                <label>
                  <input
                    type="checkbox"
                    name="interests[]"
                    v-model="interests"
                    :value="option"
                  />
                  {{ option }}
                </label>
              </div>
            </div>

            <p class="font-bold mb-2">Communication preferences:</p>

            <div class="-mx-2 flex flex-wrap">
              <div
                v-for="option in ['email', 'telephone', 'third party']"
                :key="option"
                class="px-2 mb-4 w-full md:w-1/2 lg:w-1/3 capitalize"
              >
                <label>
                  <input
                    type="checkbox"
                    name="interests[]"
                    v-model="marketingPreferences"
                    :value="option"
                  />
                  {{ option }}
                  <span
                    class="normal-case text-sm"
                    v-if="descriptionForCommunicationPreference(option)"
                    >({{ descriptionForCommunicationPreference(option) }})</span
                  >
                </label>
              </div>
            </div>

            <p class="font-bold mb-2">Preferred Dealers:</p>
            <div class="-mx-2 flex flex-wrap">
              <div
                v-for="(dealer, index) in dealers"
                :key="index"
                class="px-2 mb-4 w-full md:w-1/2 lg:w-1/3 capitalize"
              >
                <label>
                  <input
                    type="checkbox"
                    name="interests[]"
                    v-model="dealerIds"
                    :value="dealer.id"
                  />
                  {{ dealer.name }}
                </label>
              </div>
            </div>
          </div>
        </fieldset>

        <fieldset>
          <legend>Submit</legend>
          <div class="fields-container">
            <vue-recaptcha
              :sitekey="recaptchaSiteKey"
              @verify="onVerifyRecaptcha"
            ></vue-recaptcha>
            <button class="mt-5" type="submit">Submit</button>
          </div>
        </fieldset>
      </form>
      <div v-else>
        <h3 class="heading">Thank you for your enquiry</h3>
        <p>We will contact you shortly.</p>
      </div>
    </div>
  </modal>
</template>

<script>
import Errors from "./common/Errors";
import Modal from "./common/Modal";
import VueRecaptcha from "vue-recaptcha";

export default {
  components: {
    errorsDisplay: Errors,
    modal: Modal,
    VueRecaptcha,
  },

  props: {
    initialShowModal: {
      type: Boolean,
      required: true,
    },
    initialHelpMethod: {
      type: String,
    },
    dealers: {
      type: Array,
      required: true,
    },
    itemDetails: {
      type: String,
      required: true,
    },
    recaptchaSiteKey: {
      type: String,
      required: true,
    },
  },

  data: function () {
    return {
      message: `I am interested in: - ${this.itemDetails} \n`,
      askQuestion: false,
      county: "",
      dealerIds: [],
      email: "",
      errors: {},
      firstName: "",
      interests: [],
      makeOffer: false,
      marketingPreferences: [],
      phoneNumber: "",
      requestCallback: false,
      requestDemonstration: false,
      requestVideoTour: false,
      reserveVehicle: false,
      showForm: true,
      showModal: this.initialShowModal,
      surname: "",
      title: "",
      viewAtLocalBranch: false,
      recaptchaVerified: false,
    };
  },

  methods: {
    descriptionForCommunicationPreference: function (option) {
      if (option != "third party") {
        return null;
      }
      return "We only pass details onto carefully selected third parties";
    },

    handleCloseModal: function (event) {
      this.resetData();
      this.$emit("close-stock-form-modal");
    },

    onVerifyRecaptcha: function (response) {
      if (response) {
        this.recaptchaVerified = true;
      }
    },

    submitForm: function () {
      if (this.recaptchaVerified) {
        axios
          .post("/api/vehicle-enquiries", {
            county: this.county,
            dealer_ids: this.dealerIds,
            email: this.email,
            first_name: this.firstName,
            interests: this.interests,
            marketing_preferences: this.marketingPreferences,
            phone_number: this.phoneNumber,
            message: this.message,
            surname: this.surname,
            title: this.title,
          })
          .then((response) => {
            this.errors = {};
            this.showForm = false;
          })
          .catch((error) => {
            this.errors = error.response.data.errors;
            document.querySelector("#stock-form").scrollIntoView({
              behavior: "smooth",
            });
          });
      } else {
        this.errors = {
          Recaptcha: ["Please complete the recaptcha before submitting"],
        };

        document.querySelector("#stock-form").scrollIntoView({
          behavior: "smooth",
        });
      }
    },

    addToMessage: function (string) {
      this.message += string;
    },

    removeFromMessage: function (string) {
      this.message = this.message.replace(string, "");
    },

    resetData: function () {
      this.askQuestion = false;
      this.county = "";
      this.dealerIds = [];
      this.email = "";
      this.errors = {};
      this.firstName = "";
      this.interests = [];
      this.makeOffer = false;
      this.marketingPreferences = [];
      this.phoneNumber = "";
      this.requestCallback = false;
      this.requestDemonstration = false;
      this.requestVideoTour = false;
      this.reserveVehicle = false;
      this.showForm = true;
      this.showModal = this.initialShowModal;
      this.surname = "";
      this.title = "";
      this.viewAtLocalBranch = false;
      this.recaptchaVerified = false;
    },
  },
  watch: {
    initialShowModal: function (showModal) {
      this.showModal = showModal;
    },

    itemDetails: function (itemDetails, oldItemDetails) {
      this.message = this.message.replace(
        `I am interested in: - ${oldItemDetails} \n`,
        `I am interested in: - ${itemDetails} \n`
      );
    },

    initialHelpMethod: function (helpMethod) {
      switch (helpMethod) {
        case "makeOffer":
          this.makeOffer = true;
          break;
        case "requestDemonstration":
          this.requestDemonstration = true;
          break;
        case "reserveVehicle":
          this.reserveVehicle = true;
          break;
      }
    },

    askQuestion: function (checked) {
      let messageString = `\nI would like to ask a question concerning: ${this.itemDetails}\n`;
      if (checked) {
        this.addToMessage(messageString);
      } else {
        this.removeFromMessage(messageString);
      }
    },

    makeOffer: function (checked) {
      let messageString = `\nI would like to make you an offer on ${this.itemDetails}. My offer is £\n`;
      if (checked) {
        this.addToMessage(messageString);
      } else {
        this.removeFromMessage(messageString);
      }
    },

    requestCallback: function (checked) {
      let messageString = `\nCould you please call me back concerning: ${this.itemDetails}\n`;
      if (checked) {
        this.addToMessage(messageString);
      } else {
        this.removeFromMessage(messageString);
      }
    },

    requestDemonstration: function (checked) {
      let messageString = `\nI would like to arrange a demonstration of ${this.itemDetails}\n`;
      if (checked) {
        this.addToMessage(messageString);
      } else {
        this.removeFromMessage(messageString);
      }
    },

    requestVideoTour: function (checked) {
      let messageString = `\nI would like to be sent a video tour of ${this.itemDetails}\n`;
      if (checked) {
        this.addToMessage(messageString);
      } else {
        this.removeFromMessage(messageString);
      }
    },

    reserveVehicle: function (checked) {
      let messageString = `\nI am interested in reserving: - ${this.itemDetails}\n`;
      if (checked) {
        this.addToMessage(messageString);
      } else {
        this.removeFromMessage(messageString);
      }
    },

    viewAtLocalBranch: function (checked) {
      let messageString = `\nI am interested in viewing this ${this.itemDetails} my local branch which is:\n`;
      if (checked) {
        this.addToMessage(messageString);
      } else {
        this.removeFromMessage(messageString);
      }
    },
  },
};
</script>