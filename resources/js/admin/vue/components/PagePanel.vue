
<template>
  <div>
    <div>
      <label for="type">Type</label>
      <select v-model="type" name="type" @change="changeType">
        <option :key="value" v-for="(name, value) in types" :value="value">{{ name }}</option>
      </select>
    </div>

    <div v-show="showContentField">
      <wysiwyg-field
        :csrf-token="csrfToken"
        label="Content"
        name="content"
        :initial-value="initialContent"
        :assets-page-url="assetsPageUrl"
      ></wysiwyg-field>
    </div>

    <div v-show="showReadMoreContentField">
      <wysiwyg-field
        :csrf-token="csrfToken"
        label="Read More Content"
        name="read_more_content"
        :initial-value="initialReadMoreContent"
        :assets-page-url="assetsPageUrl"
      ></wysiwyg-field>
    </div>

    <div class="mt-4" v-show="showHtmlField">
      <html-field
        label="HTML Content"
        name="html_content"
        :initial-value="initialHtmlContent"
        :assets-page-url="assetsPageUrl"
      />
    </div>

    <div v-show="showImageField">
      <div>
        <label for="image">Image</label>
        <input type="file" name="image" />
      </div>
      <div>
        <label for="image_alt_text">Image Alt Text</label>
        <input type="text" name="image_alt_text" v-model="imageAltText" />
      </div>
      <div>
        <single-page-field
          label="Image Page Link"
          :initial-page-id="pageId"
          :site-id="siteId"
        />
      </div>
      <div>
        <label for="external_url">Image External URL Link (Page link will take priority)</label>
        <input type="text" name="external_url" v-model="externalUrl" />
      </div>
    </div>

    <div v-show="showFeaturedImageField">
      <div>
        <label for="featured_image">Featured Image</label>
        <input type="file" name="featured_image" />
      </div>
      <div>
        <label for="featured_image_alt_text">Featured Image Alt Text</label>
        <input type="text" name="featured_image_alt_text" v-model="featuredImageAltText" />
      </div>

      <div>
        <label for="featured_image_content">Featured Image Content</label>
        <textarea
          name="featured_image_content"
          id="featured_image_content"
          cols="30"
          rows="5"
          v-model="featuredImageContent"
        ></textarea>
      </div>
    </div>

    <div v-show="showOverlayPositionsField">
      <label for="overlay_position">Overlay Position</label>
      <select v-model="overlayPosition" name="overlay_position">
        <option :key="value" v-for="(name, value) in overlayPositions" :value="value">{{ name }}</option>
      </select>
    </div>

    <div v-show="showVehicleTypeField">
      <label for="vehicle_type">Vehicle Type</label>
      <select v-model="vehicleType" name="vehicle_type">
        <option :key="value" v-for="(name, value) in vehicleTypes" :value="value">{{ name }}</option>
      </select>
    </div>

    <fieldset v-show="showSpecialOffers">
      <legend>Special Offers</legend>

      <div v-for="offer in specialOffers" :key="offer.id">
        <label>
          <input
            type="checkbox"
            name="special_offer_ids[]"
            :value="offer.id"
            v-model="selectedSpecialOffers"
          />
          {{ offer.name }}
        </label>
      </div>
    </fieldset>

    <fieldset v-show="showFeaturedItemField">
      <legend>Featured Item</legend>
      <input type="hidden" name="featureable_type" v-model="featureableType" />
      <select name="featureable_id" v-model="featureableId">
        <option
          v-for="option in featureableOptions"
          :key="option.label + ' ' + option.value"
          :value="option.value"
        >{{ option.label }}</option>
      </select>
    </fieldset>
  </div>
</template>

<script>
import WysiwygField from "./WysiwygField.vue";
import HtmlField from "./HtmlField";
import { getForms, getSpecialOffers, getVideos, getReviews, getBrochures, getEvents } from "../utilities/api";
import SinglePageField from "./SinglePageField";

export default {
  components: {
    WysiwygField,
    HtmlField,
    SinglePageField,
  },

  props: {
    csrfToken: {
      type: String,
      required: true
    },
    siteId: {
      type: Number,
      default: null,
    },
    initialContent: {
      type: String,
      default: ""
    },
    initialHtmlContent: {
      type: String,
      default: ""
    },
    initialFeaturedImageContent: {
      type: String,
      default: ""
    },
    initialFeaturedImageAltText: {
      type: String,
      default: ""
    },
    initialImageAltText: {
      type: String,
      default: ""
    },
    initialPageId: {
      type: Number,
      default: null,
    },
    initialExternalUrl: {
      type: String,
      default: "",
    },
    initialFeatureableId: {
      type: Number,
      default: null
    },
    initialFeatureableType: {
      type: String,
      default: null
    },
    initialOverlayPosition: {
      type: String,
      default: ""
    },
    initialReadMoreContent: {
      type: String,
      default: ""
    },
    initialSpecialOfferIds: {
      type: Array,
      default: []
    },
    initialType: {
      type: String,
      default: "standard"
    },
    initialVehicleType: {
      type: String,
      default: ""
    },
    assetsPageUrl: {
      type: String,
      required: true,
    },
    overlayPositions: Object,
    types: Object,
    vehicleTypes: Object
  },

  data: function() {
    return {
      overlayPosition: this.initialOverlayPosition,
      specialOffers: null,
      featureableId: this.initialFeatureableId,
      featureableType: this.initialFeatureableType,
      featuredImageContent: this.initialFeaturedImageContent,
      featuredImageAltText: this.initialFeaturedImageAltText,
      imageAltText: this.initialImageAltText,
      pageId: this.initialPageId,
      externalUrl: this.initialExternalUrl,
      forms: null,
      selectedSpecialOffers: this.initialSpecialOfferIds,
      type: this.initialType,
      vehicleType: this.initialVehicleType,
      videos: null,
      reviews: null,
      brochures: null,
      events: null,
    };
  },

  created: function() {
    switch (this.type) {
      case "special-offers":
        this.fetchSpecialOffers();
        break;
      case "form":
        this.fetchForms();
        break;
      case "video":
        this.fetchVideos();
        break;
      case "review":
        this.fetchReviews();
        break;
      case "brochure":
        this.fetchBrochures();
        break;
      case "event":
        this.fetchEvents();
        break;
      default:
        break;
    }
  },

  computed: {
    featureableOptions: function() {
      switch (this.type) {
        case "form":
          return this.forms;
        case "video":
          return this.videos;
        case "brochure":
          return this.brochures;
        case "event":
          return this.events;
        case "review":
          return this.reviews;
        default:
          return [];
      }
    },

    showFeaturedItemField: function() {
      return ["form", "video", "review", "brochure", "event"].includes(this.type);
    },

    showContentField: function() {
      return !["special-offers", "featured-image", "html"].includes(this.type);
    },

    showHtmlField: function() {
      return this.type == "html";
    },

    showFeaturedImageField: function() {
      return this.type == "featured-image";
    },

    showImageField: function() {
      return this.type == "image";
    },

    showOverlayPositionsField: function() {
      return this.type == "featured-image";
    },

    showReadMoreContentField: function() {
      return this.type == "read-more";
    },

    showSpecialOffers: function() {
      return this.type == "special-offers";
    },

    showVehicleTypeField: function() {
      return [
        "search-by-berth",
        "stock-item-category-tabs",
        "manufacturer-slider"
      ].includes(this.type);
    }
  },

  methods: {
    changeType() {
      switch (this.type) {
        case "special-offers":
          this.fetchSpecialOffers();
          this.clearFeatureableFields();
          break;
        case "form":
          this.fetchForms();
          break;
        case "video":
          this.fetchVideos();
          break;
        case "review":
          this.fetchReviews();
          break;
        case "brochure":
          this.fetchBrochures();
          break;
        case "event":
          this.fetchEvents();
          break;
        default:
          this.clearFeatureableFields();
          break;
      }
    },

    clearFeatureableFields() {
      this.featureableId = null;
      this.featureableType = null;
    },

    fetchBrochures() {
      this.featureableType = "App\\Models\\Brochure";
      if (this.brochures !== null) {
        return;
      }

      getBrochures()
        .then(response => {
          this.brochures = response.data.data.map(function(brochure) {
            return {
              value: brochure.id,
              label: brochure.title
            };
          });
        })
        .catch(error => {
          console.error(error);
        });
    },

    fetchEvents() {
      this.featureableType = "App\\Models\\Event";
      if (this.events !== null) {
        return;
      }

      getEvents()
        .then(response => {
          this.events = response.data.data.map(function(event) {
            return {
              value: event.id,
              label: event.name
            };
          });
        })
        .catch(error => {
          console.error(error);
        });
    },

    fetchReviews() {
      this.featureableType = "App\\Models\\Review";
      if (this.reviews !== null) {
        return;
      }

      getReviews()
        .then(response => {
          this.reviews = response.data.data.map(function(review) {
            return {
              value: review.id,
              label: review.title
            };
          });
        })
        .catch(error => {
          console.error(error);
        });
    },

    fetchForms() {
      this.featureableType = "App\\Models\\Form";
      if (this.forms !== null) {
        return;
      }

      getForms()
        .then(response => {
          this.forms = response.data.data.map(function(form) {
            return {
              value: form.id,
              label: form.name
            };
          });
        })
        .catch(error => {
          console.error(error);
        });
    },

    fetchSpecialOffers() {
      if (this.specialOffers !== null) {
        return;
      }

      getSpecialOffers()
        .then(response => {
          this.specialOffers = response.data.data;
        })
        .catch(error => {
          console.error(error);
        });
    },

    fetchVideos() {
      this.featureableType = "App\\Models\\Video";
      if (this.videos !== null) {
        return;
      }

      getVideos()
        .then(response => {
          this.videos = response.data.data.map(function(video) {
            return {
              value: video.id,
              label: video.title
            };
          });
        })
        .catch(error => {
          console.error(error);
        });
    }
  }
};
</script>
