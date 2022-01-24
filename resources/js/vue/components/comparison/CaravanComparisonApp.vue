<template>
  <div class="container mx-auto py-10">
    <div class="text-center py-10">
      <h1 class="text-endeavour">Compare Caravans</h1>
    </div>
    <div v-if="loading">
      <div class="flex flex-row justify-center text-shiraz">
        <LoadingSpinner width="250px" height="250px" />
      </div>
    </div>
    <div v-else>
      <div v-if="caravans.length > 1">
        <div>
          <a
            v-if="caravanStockSearchPageUrl !== ''"
            :href="caravanStockSearchPageUrl"
            class="text-xl button-endeavour text-center block w-full p-3 mb-5"
          >Return to stock</a>
          <ComparisonTable
            stock-type="caravan"
            :stockItems="caravans"
            v-on:remove-stock-item-from-comparison="removeCaravan($event)"
          />
          <a
            v-if="caravanStockSearchPageUrl !== ''"
            :href="caravanStockSearchPageUrl"
            class="text-xl button-endeavour text-center block w-full p-3 mt-5"
          >Return to stock</a>
        </div>
      </div>
      <div v-else>
        <div
          class="mb-10 flex flex-row justify-center px-16 text-xl md:text-2xl lg:text-3xl"
        >Add at least two caravans to your shortlist to compare them.</div>
        <a
          v-if="caravanStockSearchPageUrl !== ''"
          :href="caravanStockSearchPageUrl"
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
    caravanStockSearchPageUrl: {
      type: String,
    },
  },
  computed: {
    ...mapState("comparison", ["loading", "caravans"]),
  },
  methods: {
    ...mapActions("comparison", ["initialise", "removeCaravan"]),
  },
  created() {
    this.initialise();
  },
};
</script>
