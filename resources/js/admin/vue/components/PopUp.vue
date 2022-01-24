<template>
  <form method="post" :action="url" enctype="multipart/form-data" class="admin-form">
    <input name="_method" type="hidden" :value="method" />
    <input type="hidden" name="_token" :value="csrfToken" />

    <errors :error-bag="errors"></errors>

    <div>
      <label for="name">Name</label>
      <input name="name" type="text" placeholder="Name" v-model="name" required />
    </div>

    <div>
      <label for="site_id">Site</label>
      <select name="site_id" v-model="siteId" @change="clearOptions">
        <option :value="null" disabled>Select a site</option>
        <option v-for="site in sites" :key="site.id" :value="site.id">
          {{
          site.country
          }}
        </option>
      </select>
    </div>

    <div v-if="siteId">
      <fieldset class="border-2 p-3">
        <legend>Where should this pop up show?</legend>

        <div class="flex flex-row mb-5 flex-wrap">
          <div class="mr-5 flex flex-row items-center">
            <input type="hidden" name="appears_on_all_pages" value="0" />
            <label for="appears_on_all_pages" class="mr-2">All pages</label>
            <input id="appears_on_all_pages" name="appears_on_all_pages" type="checkbox" value="1" :checked="appearsOnAllPages" />
          </div>
          <div class="mr-5 flex flex-row items-center">
            <input type="hidden" name="appears_on_new_motorhome_pages" value="0" />
            <label for="appears_on_new_motorhome_pages" class="mr-2">New motorhome pages</label>
            <input id="appears_on_new_motorhome_pages" name="appears_on_new_motorhome_pages" type="checkbox" value="1" :checked="appearsOnNewMotorhomePages" />
          </div>

          <div class="mr-5 flex flex-row items-center">
            <input type="hidden" name="appears_on_new_caravan_pages" value="0" />
            <label for="appears_on_new_caravan_pages" class="mr-2">New caravan pages</label>
            <input id="appears_on_new_caravan_pages" name="appears_on_new_caravan_pages" type="checkbox" value="1" :checked="appearsOnNewCaravanPages" />
          </div>

          <div class="mr-5 flex flex-row items-center">
            <input type="hidden" name="appears_on_used_motorhome_pages" value="0" />
            <label for="appears_on_used_motorhome_pages" class="mr-2">Used motorhome pages</label>
            <input id="appears_on_used_motorhome_pages" name="appears_on_used_motorhome_pages" type="checkbox" value="1" :checked="appearsOnUsedMotorhomePages" />
          </div>

          <div class="flex flex-row items-center">
            <input type="hidden" name="appears_on_used_caravan_pages" value="0" />
            <label for="appears_on_used_caravan_pages" class="mr-2">Used caravan pages</label>
            <input id="appears_on_used_caravan_pages" name="appears_on_used_caravan_pages" type="checkbox" value="1" :checked="appearsOnUsedCaravanPages" />
          </div>
        </div>

        <multiple-page-field
          ref="pagesField"
          field-name="appears_on_page_ids"
          label="A selection of pages"
          :initial-page-ids="initialAppearsOnPageIds"
          :site-id="siteId"
        ></multiple-page-field>

        <fieldset class="mt-5 border-2 p-3">
          <legend>A selection of caravan ranges</legend>

          <div class="flex -mx-2">
            <div v-for="caravanRange in caravanRanges" :key="caravanRange.id" class="mx-2 w-1/4">
              <label>
                <input
                  type="checkbox"
                  v-model="caravanRangeIds"
                  name="caravan_range_ids[]"
                  :value="caravanRange.id"
                />
                {{ caravanRange.name }} ({{ caravanRange.manufacturer.name }})
              </label>
            </div>
          </div>
        </fieldset>

        <fieldset class="mt-5 border-2 p-3">
          <legend>A selection of motorhome ranges</legend>

          <div class="flex -mx-2">
            <div v-for="motorhomeRange in motorhomeRanges" :key="motorhomeRange.id" class="mx-2 w-1/4">
              <label>
                <input
                  type="checkbox"
                  v-model="motorhomeRangeIds"
                  name="motorhome_range_ids[]"
                  :value="motorhomeRange.id"
                />
                {{ motorhomeRange.name }} ({{ motorhomeRange.manufacturer.name }})
              </label>
            </div>
          </div>
        </fieldset>
      </fieldset>
    </div>

    <div v-if="siteId">
      <fieldset class="border-2 p-3">
        <legend>Where should this pop up link to?</legend>

        <single-page-field ref="pageField" :initial-page-id="initialPageId" :site-id="siteId"></single-page-field>

        <div class="mt-5">
          <label for="external_url">External Url (If a page has been selected, that will take priority)</label>
          <input
            name="external_url"
            type="text"
            placeholder="https://www.google.co.uk"
            v-model="externalUrl"
          />
        </div>
      </fieldset>
    </div>

    <div>
      <img :src="mobileThumbUrl" alt="Mobile Thumb" v-if="mobileThumbUrl" />
      <label for="mobile_image">Mobile Image</label>
      <input name="mobile_image" type="file" />
    </div>

    <div>
      <img :src="desktopThumbUrl" alt="Desktop Thumb" v-if="desktopThumbUrl" />
      <label for="desktop_image">Desktop Image</label>
      <input name="desktop_image" type="file" />
    </div>

    <div>
      <label for="expired_at">Expired At</label>
      <datetime input-id="expired_at" hidden-name="expired_at" v-model="expiredAt"></datetime>
    </div>

    <div>
      <label for="published_at">Published At</label>
      <datetime input-id="published_at" hidden-name="published_at" v-model="publishedAt"></datetime>
    </div>

    <div>
      <input type="hidden" name="live" value="0" />
      <label for="live">Live</label>
      <input id="live" name="live" type="checkbox" value="1" :checked="live" />
    </div>

    <div class="flex items-center justify-between">
      <button
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
        type="submit"
      >{{ submitButtonText }}</button>
    </div>
  </form>
</template>

<script>
import errors from "./Errors.vue";

export default {
  components: {
    errors
  },

  props: {
    caravanRanges: {
      type: Array,
      default: []
    },
    motorhomeRanges: {
      type: Array,
      default: []
    },
    csrfToken: {
      type: String,
      required: true
    },
    desktopThumbUrl: {
      type: String
    },
    errors: {
      type: Object,
      required: true
    },
    exists: {
      type: Boolean,
      required: true
    },
    initialAppearsOnAllPages: {
      type: Boolean,
      default: false,
    },
    initialAppearsOnNewCaravanPages: {
      type: Boolean,
      default: false,
    },
    initialAppearsOnNewMotorhomePages: {
      type: Boolean,
      default: false,
    },
    initialAppearsOnUsedCaravanPages: {
      type: Boolean,
      default: false,
    },
    initialAppearsOnUsedMotorhomePages: {
      type: Boolean,
      default: false,
    },
    initialAppearsOnPageIds: {
      type: Array,
      default: []
    },
    initialCaravanRangeIds: {
      type: Array,
      default: []
    },
    initialMotorhomeRangeIds: {
      type: Array,
      default: []
    },
    initialExternalUrl: {
      type: String
    },
    initialExpiredAt: {
      type: String
    },
    initialLive: {
      type: Boolean,
      required: true
    },
    initialName: {
      type: String,
      required: true
    },
    initialPageId: {
      type: Number,
      default: null
    },
    initialPublishedAt: {
      type: String
    },
    initialSiteId: {
      type: Number
    },
    mobileThumbUrl: {
      type: String
    },
    sites: {
      type: Array,
      required: true
    },
    url: {
      type: String,
      required: true
    }
  },

  data: function() {
    return {
      caravanRangeIds: this.initialCaravanRangeIds,
      motorhomeRangeIds: this.initialMotorhomeRangeIds,
      externalUrl: this.initialExternalUrl,
      expiredAt: this.initialExpiredAt,
      live: this.initialLive,
      name: this.initialName,
      publishedAt: this.initialPublishedAt,
      siteId: this.initialSiteId,
      appearsOnAllPages: this.initialAppearsOnAllPages,
      appearsOnNewMotorhomePages: this.initialAppearsOnNewMotorhomePages,
      appearsOnNewCaravanPages: this.initialAppearsOnNewCaravanPages,
      appearsOnUsedMotorhomePages: this.initialAppearsOnUsedMotorhomePages,
      appearsOnUsedCaravanPages: this.initialAppearsOnUsedCaravanPages,
    };
  },

  computed: {
    method: function() {
      if (this.exists) {
        return "PUT";
      }
      return "POST";
    },

    submitButtonText: function() {
      if (this.exists) {
        return "Update";
      }
      return "Create";
    }
  },

  methods: {
    clearOptions: function() {
      const pageField = this.$refs.pageField;

      if (pageField) {
        pageField.clearOptions();
      }
    }
  }
};
</script>
