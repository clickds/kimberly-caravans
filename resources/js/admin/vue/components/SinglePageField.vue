<template>
  <div>
    <input type="hidden" :name="fieldName" v-model="pageId" />
    <label @click="focus">{{ label }}</label>
    <v-select ref="select" v-model="page" :options="pageOptions" @search="fetchPageOptions" />
  </div>
</template>

<script>
import { getPage, searchPages } from "../utilities/api";
export default {
  props: {
    fieldName: {
      type: String,
      default: "page_id"
    },
    label: {
      type: String,
      default: "Page"
    },
    initialPageId: {
      type: Number,
      default: null
    },
    siteId: {
      type: Number,
      default: null
    }
  },

  mounted() {
    if (this.initialPageId) {
      this.fetchPage();
    }
  },

  data: function() {
    return {
      page: null,
      pageOptions: []
    };
  },

  computed: {
    pageId: function() {
      if (this.page) {
        return this.page.id;
      }
      return null;
    },

    addSiteToOptionLabel: function() {
      if (this.siteId) {
        return false;
      }
      return true;
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

    clearOptions: function() {
      this.page = null;
      this.pageOptions = [];
    },

    fetchPage: function() {
      getPage(this.initialPageId)
        .then(response => {
          const option = this.buildOption(response.data.data);
          this.pageOptions.push(option);
          this.page = option;
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