<template>
  <div class="sticky md:unset" :style="`top: ${offsetTop}px;`">
    <div class="bg-alabaster md:hidden">
      <a
        @click.prevent="toggleOpen"
        class="block text-center h-10 text-endeavour text-xl flex justify-center items-center"
      >
        Filters ({{ getSelectedFiltersCount }})
        <i v-if="open" class="ml-4 fas text-3xl fa-angle-up"></i>
        <i v-else class="ml-4 fas text-3xl fa-angle-down"></i>
      </a>
    </div>
    <div class="md:block " :class="{ hidden: !open }">
      <div class="h-1/2-screen overflow-scroll w-full bg-gallery py-6 md:h-auto md:overflow-unset">
        <div class="container mx-auto px-standard">
          <div class="flex flex-col">
            <SelectedFilters
              class="md:hidden mb-6"
              :selected-options="selectedOptions"
              :selected-ranges="selectedRanges"
              :search-term="searchTerm"
              v-on:remove-option-filter="handleRemoveOptionFilter"
              v-on:remove-range-filter="handleRemoveRangeFilter"
              v-on:remove-search-term="handleRemoveSearchTerm"
              v-on:clear-all-filters="handleClearAllFilters"
            />
            <div class="w-full mb-6">
              <div
                class="grid grid-cols-1 gap-2 md:gap-6"
                :class="gridColumnClasses(optionFilters)"
              >
                <FilterSelect
                  v-for="(filter, index) in optionFilters"
                  :key="index"
                  :placeholder="filter.displayName"
                  :filter-name="filter.name"
                  :options="filter.options"
                  :initial-selected-options="getSelectedOptionsForFilter(filter.name)"
                  v-on:selected-options-updated="handleSelectChange"
                  :loading="loading"
                  v-bind:is-disabled="loading || error"
                />
                <FilterSearch />
              </div>
            </div>
            <div class="w-full">
              <div
                class="grid grid-cols-1 gap-2 md:gap-6"
                :class="gridColumnClasses(optionFilters)"
              >
                <FilterRange
                  v-for="(filter, index) in rangeFilters"
                  :key="index"
                  :label="filter.displayName"
                  :filter-name="filter.name"
                  :minimum="filter.min"
                  :maximum="filter.max"
                  :increments="filter.increments"
                  class="px-2"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
      <button @click="handleViewResultsClick" class="sticky bottom-0 w-full text-center p-3 bg-endeavour text-white md:hidden">View results</button>
    </div>
    <SelectedFilters
      class="hidden md:flex container mx-auto px-standard py-2 bg-white"
      :selected-options="selectedOptions"
      :selected-ranges="selectedRanges"
      :search-term="searchTerm"
      v-on:remove-option-filter="handleRemoveOptionFilter"
      v-on:remove-range-filter="handleRemoveRangeFilter"
      v-on:remove-search-term="handleRemoveSearchTerm"
      v-on:clear-all-filters="handleClearAllFilters"
    />
  </div>
</template>

<script>
import FilterSelect from "./FilterSelect";
import { mapState, mapActions, mapGetters } from "vuex";
import FilterRange from "./FilterRange";
import FilterSearch from "./FilterSearch";
import SelectedFilters from "./SelectedFilters";
import { isEqual } from 'lodash-es';

export default {
  components: {
    FilterRange,
    FilterSelect,
    FilterSearch,
    SelectedFilters
  },

  data: function () {
    return {
      open: false,
      offsetTop: 0,
    };
  },

  computed: {
    ...mapState([
      "filters",
      "loading",
      "error",
      "comparisonIds",
      "selectedOptions",
      "selectedRanges",
      "searchTerm"
    ]),
    ...mapGetters([
      "getSelectedOptionsForFilter",
      "getSelectedFiltersCount"
    ]),
    optionFilters: function () {
      return this.filters.filter((filter) => {
        return filter.type === "options";
      });
    },
    rangeFilters: function () {
      return this.filters.filter((filter) => {
        return filter.type === "range";
      });
    },
  },

  methods: {
    ...mapActions([
      "applyOptionFilter",
      "removeOptionFilter",
      "removeRangeFilter",
      "retrieveStockItems",
      "resetAllFilters",
      "applySearchTerm",
    ]),
    gridColumnClasses: function (filters) {
      // filters count + 1 Accounts for stock search being part of the grid.
      if ((filters.length + 1) % 5 === 0) {
        return "md:grid-cols-3 lg:grid-cols-5";
      }
      return "md:grid-cols-4";
    },
    toggleOpen: function () {
      this.open = !this.open;
    },
    handleSelectChange: function (filterName, selectedOptions) {
      let currentSelectedOptions = this.getSelectedOptionsForFilter(filterName);

      if (isEqual(selectedOptions, currentSelectedOptions)) {
        return;
      }

      let optionsToAdd = selectedOptions.filter(option => !currentSelectedOptions.includes(option));
      let optionsToRemove = currentSelectedOptions.filter(option => !selectedOptions.includes(option));

      optionsToAdd.map(option => this.applyOptionFilter({ name: filterName, value: option }));
      optionsToRemove.map(option => this.removeOptionFilter({ name: filterName, value: option }));

      this.retrieveStockItems();
    },
    handleRemoveOptionFilter: function (name, value) {
      this.removeOptionFilter({ name, value });
      this.retrieveStockItems();
    },
    handleRemoveRangeFilter: function (name) {
      this.removeRangeFilter(name);
      this.retrieveStockItems();
    },
    handleRemoveSearchTerm: function () {
      this.applySearchTerm('');
      this.retrieveStockItems();
    },
    handleClearAllFilters: function () {
      this.resetAllFilters();
      this.retrieveStockItems();
    },
    handleViewResultsClick: function () {
      this.toggleOpen();
      window.scrollTo(0, 0);
    }
  },
  mounted() {
    let element = document.getElementById('js-navigation-and-comparison-container');

    if (element) {
      new ResizeObserver((entries) => {
        this.offsetTop = entries[0].contentRect.height;
      }).observe(element);
    }
  },
};
</script>
