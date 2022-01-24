<template>
  <div>
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
      <single-page-field
        ref="pageField"
        :field-name="pageFieldName"
        :label="pageFieldLabel"
        :initial-page-id="initialPageId"
        :site-id="siteId"
      ></single-page-field>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    initialPageId: {
      type: Number,
      default: null
    },
    initialSiteId: {
      type: Number
    },
    pageFieldName: {
      type: String,
      default: "parent_id"
    },
    pageFieldLabel: {
      type: String,
      default: "parent"
    },
    sites: {
      type: Array,
      required: true
    }
  },

  data: function() {
    return {
      siteId: this.initialSiteId
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
