<template>
  <div>
    <input type="hidden" :name="hiddenFieldName" :value="id" :key="id" v-for="id in pageIds" />
    <label @click="focus">{{ label }}</label>
    <v-select
      ref="select"
      multiple
      v-model="pages"
      :options="pageOptions"
      @search="fetchPageOptions"
    />
  </div>
</template>

<script>
import { getPages, searchPages } from "../utilities/api";

export default {
  props: {
    fieldName: {
      type: String,
      default: "page_ids"
    },
    label: {
      type: String,
      default: "Pages"
    },
    initialPageIds: {
      type: Array,
      default: function() {
        return [];
      }
    },
    siteId: {
      type: Number,
      default: null
    }
  },

  data: function() {
    return {
      pages: [],
      pageOptions: []
    };
  },

  mounted() {
    if (this.initialPageIds.length > 0) {
      this.fetchPages();
    }
  },

  computed: {
    addSiteToOptionLabel: function() {
      if (this.siteId) {
        return false;
      }
      return true;
    },

    pageIds: function() {
      return this.pages.map(page => page.id);
    },

    hiddenFieldName: function() {
      return this.fieldName + "[]";
    }
  },

  methods: {
    buildOption(page) {
      let label = page.name;
      if (this.addSiteToOptionLabel) {
        label += ` (${page.site.country})`;
      }
      return {
        id: page.id,
        label: label
      };
    },

    fetchPages: function() {
      let params = {
        ids: this.initialPageIds
      };
      if (this.siteId) {
        params.site_id = this.siteId;
      }
      getPages(params)
        .then(response => {
          response.data.data.forEach(page => {
            const option = this.buildOption(page);
            this.pageOptions.push(option);
            this.pages.push(option);
          });
        })
        .catch(error => {
          console.error(error);
        });
    },

    fetchPageOptions: function(searchTerm, loading) {
      if (searchTerm.length < 3) {
        return;
      }
      let params = {
        search_term: searchTerm
      };
      if (this.siteId) {
        params.site_id = this.siteId;
      }
      searchPages(params)
        .then(response => {
          const pageOptions = [];
          response.data.data.forEach(page => {
            const option = this.buildOption(page);
            pageOptions.push(option);
          });
          this.pageOptions = pageOptions;
        })
        .catch(error => {
          console.error(error);
        });
    },

    focus: function() {
      this.$refs.select.$refs.search.focus();
    }
  }
};
</script>