<template>
  <div v-if="loading">
    <LoadingSpinner width="45px" height="45px" />
  </div>
  <div v-else>
    <div v-if="inComparisonList" @click="removeFromComparison" class="cursor-pointer">
      <div
        v-if="isCompact"
        class="flex justify-between items-center text-shiraz hover:text-monarch"
      >
        <span class="text-xs mr-2">Remove from compare</span>
        <i class="fas fa-minus-circle text-3xl"></i>
      </div>
      <div v-else class="bg-shiraz font-heading font-semibold text-white py-2 px-4 rounded">
        Compare
        <i class="fas fa-minus"></i>
      </div>
    </div>
    <div v-else-if="comparisonList.length < 3" @click="addToComparison" class="cursor-pointer">
      <div
        v-if="isCompact"
        class="flex justify-between items-center text-endeavour hover:text-regal-blue"
      >
        <span class="text-xs mr-2">Add to compare</span>
        <i class="fas fa-plus-circle text-3xl"></i>
      </div>
      <div v-else class="bg-endeavour font-heading font-semibold text-white py-2 px-4 rounded">
        Compare
        <i class="fas fa-plus"></i>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";
import LoadingSpinner from "../common/LoadingSpinner";

export default {
  components: { LoadingSpinner },
  props: {
    id: Number,
    price: {
      type: Number,
      default: null,
    },
    stockType: String,
    buttonType: {
      type: String,
      default: "compact",
    },
  },
  computed: {
    ...mapState("comparison", ["loading", "caravans", "motorhomes"]),

    isCompact: function () {
      return this.buttonType === "compact";
    },

    inComparisonList: function () {
      return this.comparisonList.find((stockItem) => stockItem.id === this.id);
    },

    comparisonList: function () {
      if (this.stockType === "caravan") {
        return this.caravans;
      }

      if (this.stockType === "motorhome") {
        return this.motorhomes;
      }

      return [];
    },
  },
  methods: {
    ...mapActions("comparison", [
      "addCaravan",
      "removeCaravan",
      "addMotorhome",
      "removeMotorhome",
    ]),

    addToComparison: function () {
      if (this.stockType === "caravan") {
        this.addCaravan(this.id);
      } else {
        this.addMotorhome(this.id);
      }
    },

    removeFromComparison: function () {
      if (this.stockType === "caravan") {
        this.removeCaravan(this.id);
      } else {
        this.removeMotorhome(this.id);
      }
    },
  },
};
</script>
