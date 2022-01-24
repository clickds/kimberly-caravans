<template>
  <div v-if="hasItemsInComparison">
    <CaravanComparisonContainer />
    <MotorhomeComparisonContainer />
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
import CaravanComparisonContainer from "./caravan-comparison/CaravanComparisonContainer";
import MotorhomeComparisonContainer from "./motorhome-comparison/MotorhomeComparisonContainer";

export default {
  components: {
    MotorhomeComparisonContainer,
    CaravanComparisonContainer
  },
  computed: {
    ...mapState("comparison", ["caravans", "motorhomes"]),

    hasItemsInComparison: function() {
      return this.caravans.length > 0 || this.motorhomes.length > 0;
    }
  }
};
</script>