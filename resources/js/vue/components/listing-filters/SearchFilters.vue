<template>
  <form action method="get" ref="form">
    <div class="pt-4 md:pt-10 bg-gallery">
      <div class="container mx-auto flex flex-row justify-center px-standard">
        <div class="hidden text-lg text-endeavour mr-5 md:block">Filter results by category:</div>
        <div class="flex flex-grow flex-wrap -mx-2">
          <div class="px-2 w-full md:w-1/2 lg:w-1/3 xl:w-1/5" v-if="categories.length > 0">
            <custom-select
              label="name"
              :options="categories"
              placeholder="Category"
              :initialSelectedValues="selectedCategories"
              v-on:selectedValuesUpdated="handleSelectedCategoriesUpdated"
            />
            <input
              v-for="id in selectedCategories"
              :key="id"
              type="hidden"
              name="categories[]"
              :value="id"
            />
            <input type="hidden" name="query" :value="query" />
            <input type="hidden" name="per_page" :value="perPage" v-if="perPage" />
          </div>
        </div>
      </div>
    </div>
    <div v-if="hasSelectedFilters" class="container mx-auto px-standard py-2 bg-white flex flex-row items-center space-x-5">
      <span
        class="underline text-endeavour cursor-pointer"
        @click="handleClearAll"
      >
        Clear all
      </span>
      <ul class="selected-filters">
        <li v-for="category in selectedCategories" :key="category" class="px-2 mb-2">
          <div class="filter-option">
            <span class="font-bold mr-1">Category:</span>
            <span class="name">{{ category }}</span>
            <i
              @click="handleRemoveCategory(category)"
              class="icon fa fa-times-circle"
              aria-label="remove category filter"
              aria-hidden="false"
            ></i>
          </div>
        </li>
      </ul>
    </div>
  </form>
</template>

<script>
import CustomSelect from "../common/CustomSelect";

export default {
  components: {
    CustomSelect,
  },
  props: {
    categories: {
      type: Array,
      default: [],
    },
    query: {
      type: String,
      required: true,
    },
    perPage: {
      type: Number,
      required: true,
    },
  },

  data: function () {
    // Laravel's pagination links add indexes to array style URL query params, e.g. video_category_ids[0]
    // We need to remove them to allow this component to function as expected.
    const searchString = decodeURI(window.location.search);
    const regex = /\[([0-9]+)\]/g;
    const sanitisedSearchString = searchString.replaceAll(regex, '[]');
    const urlParams = new URLSearchParams(sanitisedSearchString);

    return {
      selectedCategories: urlParams.getAll('categories[]'),
    };
  },

  computed: {
    hasSelectedFilters: function () {
      return this.selectedCategories.length > 0;
    },
  },

  methods: {
    submitForm: function () {
      this.$refs.form.submit();
    },

    setSelectedCategories: async function (categories) {
      this.selectedCategories = categories;
    },

    handleSelectedCategoriesUpdated: async function (selectedCategories) {
      await this.setSelectedCategories(selectedCategories);
      this.submitForm();
    },

    handleRemoveCategory: async function(categoryToRemove) {
      const index = this.selectedCategories.indexOf(categoryToRemove);
      if (index !== -1) {
        await this.setSelectedCategories(
          this.selectedCategories.filter(category => category !== categoryToRemove)
        );
      }

      this.submitForm();
    },

    handleClearAll: async function() {
      await this.setSelectedCategories([]);
      this.submitForm();
    },
  },
};
</script>
