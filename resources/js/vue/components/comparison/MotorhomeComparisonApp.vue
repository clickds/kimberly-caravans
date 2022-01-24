<template>
  <div class="container mx-auto py-10">
    <div class="text-center py-10">
      <h1 class="text-endeavour">Compare Motorhomes</h1>
    </div>
    <div v-if="loading">
      <div class="flex flex-row justify-center text-shiraz">
        <LoadingSpinner width="250px" height="250px" />
      </div>
    </div>
    <div v-else>
      <div v-if="motorhomes.length > 1">
        <div>
          <a
            v-if="motorhomeStockSearchPageUrl !== ''"
            :href="motorhomeStockSearchPageUrl"
            class="text-xl button-endeavour text-center block w-full p-3 mb-5"
          >Return to stock</a>
          <ComparisonTable
            stock-type="motorhome"
            :stockItems="motorhomes"
            v-on:remove-stock-item-from-comparison="removeMotorhome($event)"
          />
          <a
            v-if="motorhomeStockSearchPageUrl !== ''"
            :href="motorhomeStockSearchPageUrl"
            class="text-xl button-endeavour text-center block w-full p-3 mt-5"
          >Return to stock</a>
        </div>
      </div>
      <div v-else>
        <div
          class="mb-10 flex flex-row justify-center px-16 text-xl md:text-2xl lg:text-3xl"
        >Add at least two motorhomes to your shortlist to compare them.</div>
        <a
          v-if="motorhomeStockSearchPageUrl !== ''"
          :href="motorhomeStockSearchPageUrl"
          class="text-xl button-endeavour text-center block w-full p-3"
        >Return to stock</a>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";
import ComparisonTable from "../common/comparison/ComparisonTable";
import LoadingSpinner from "../common/LoadingSpinner";

export default {
  components: {
    ComparisonTable,
    LoadingSpinner,
  },
  props: {
    motorhomeStockSearchPageUrl: {
      type: String,
    },
  },
  computed: {
    ...mapState("comparison", ["loading", "motorhomes"]),
  },
  methods: {
    ...mapActions("comparison", ["initialise", "removeMotorhome"]),
  },
  created() {
    this.initialise();
  },
};
</script>
