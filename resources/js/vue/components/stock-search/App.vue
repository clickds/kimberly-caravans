<template>
  <div>
    <FiltersContainer />
    <div class="container mx-auto px-standard">
      <div v-if="loading">
        <div class="flex flex-row justify-center text-shiraz">
          <LoadingSpinner width="250px" height="250px" />
        </div>
      </div>
      <div v-else-if="error">
        <div
          class="flex flex-row justify-center p-16 text-3xl"
        >Something has gone wrong, please try again.</div>
      </div>
      <div v-else>
        <div v-if="stockItems.length !== 0">
          <div class="my-4 flex flex-wrap -mx-2 items-center">
            <div class="px-2 w-full md:w-1/2 mb-4 md:mb-0">
              <ResultCount></ResultCount>
            </div>

            <hr class="w-full md:hidden bg-gallery my-4" />

            <div class="px-2 w-full md:w-1/2">
              <Ordering></Ordering>
            </div>
          </div>

          <hr class="bg-gallery my-4" />

          <div class="my-4 flex flex-wrap -mx-2 items-center">
            <div class="px-2 w-full md:w-3/4">
              <Pagination></Pagination>
            </div>

            <hr class="w-full md:hidden bg-gallery my-4 mx-2" />

            <div class="px-2 w-full md:w-1/4">
              <PerPageSelection></PerPageSelection>
            </div>
          </div>

          <hr class="bg-gallery my-4" />

          <StockItems :stock-search-links="stockSearchLinks"></StockItems>

          <hr class="bg-gallery my-4" />

          <div class="my-4 flex flex-wrap -mx-2 items-center">
            <div class="px-2 w-full md:w-3/4 mb-4 mb:mb-0">
              <Pagination :scroll-to-top-on-click="true"></Pagination>
            </div>

            <hr class="w-full md:hidden bg-gallery my-4" />

            <div class="px-2 w-full md:w-1/4">
              <PerPageSelection :scroll-to-top-on-click="true"></PerPageSelection>
            </div>
          </div>

          <hr class="bg-gallery my-4" />
        </div>
        <div v-else>
          <div
            class="flex flex-row justify-center p-16 text-3xl"
          >No results found, try adjusting the selected criteria.</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import FiltersContainer from "./filters/FiltersContainer";
import LoadingSpinner from "../common/LoadingSpinner";
import Pagination from "./pagination/Pagination";
import PerPageSelection from "./pagination/PerPageSelection";
import Ordering from "./Ordering";
import ResultCount from "./ResultCount";
import StockItems from "./stock-items/StockItems";
import { mapActions } from "vuex";
import { mapState } from "vuex";

export default {
  components: {
    StockItems,
    ResultCount,
    FiltersContainer,
    LoadingSpinner,
    Pagination,
    PerPageSelection,
    Ordering,
  },

  props: {
    initialStockType: String,
    initialFilters: Array,
    stockSearchLinks: Array,
    url: String
  },

  computed: mapState(["error", "loading", "stockItems"]),

  methods: {
    ...mapActions(["initialise", "initialiseAfterHistoryChange"]),
  },

  created() {
    window.onpopstate = this.reinitialiseAfterHistoryChange;

    this.initialise({
      stockType: this.initialStockType,
      filters: this.initialFilters,
      url: this.url
    });
  },
};
</script>
