<template>
  <div>
    <span>{{ label }}</span>
    <vue-slider
      ref="slider"
      :value="getSliderValues"
      :min="minimum"
      :max="maximum"
      :marks="true"
      :hide-label="true"
      :interval="increments"
      :tooltip-formatter="formatValue"
      tooltip-placement="bottom"
      v-on:drag-end="onDragEnd"
      :drag-on-click="true"
      :disabled="loading"
    ></vue-slider>
  </div>
</template>

<script>
  import { mapGetters, mapActions, mapState } from "vuex";
  import VueSlider from "vue-slider-component";

  export default {
    components: {
      VueSlider
    },
    data: function () {
      return {
        value: [this.minimum, this.maximum],
      }
    },
    props: {
      label: {
        type: String,
        required: true
      },
      filterName: {
        type: String,
        required: true,
      },
      minimum: {
        type: Number,
        required: true,
      },
      maximum: {
        type: Number,
        required: true,
      },
      increments: {
        type: Number,
        required: true,
      },
    },
    computed: {
      ...mapState(["loading"]),
      ...mapGetters(["getSelectedRangeForFilter"]),
      getSliderValues: function () {
        let rangeValues = this.getSelectedRangeForFilter(this.filterName);
        let min = this.minimum;
        let max = this.maximum;

        if (rangeValues.hasOwnProperty("min")) {
          min = rangeValues.min;
        }
        if (rangeValues.hasOwnProperty("max")) {
          max = rangeValues.max;
        }

        return [min, max];
      }
    },
    methods: {
      ...mapActions([
        "applyRangeFilter",
        "removeRangeFilter",
        "retrieveStockItems",
      ]),
      onDragEnd: function () {
        let sliderValues = this.$refs.slider.getValue();
        let rangeFilterData = { name: this.filterName };

        // If the slider is set to the min and max, remove the filter.
        if (this.minimum === sliderValues[0] && this.maximum === sliderValues[1]) {
          this.removeRangeFilter(this.filterName);
        }

        if (sliderValues[0] > this.minimum) {
          rangeFilterData.min = sliderValues[0];
        }

        if (sliderValues[1] < this.maximum) {
          rangeFilterData.max = sliderValues[1];
        }

        this.applyRangeFilter(rangeFilterData);
        this.retrieveStockItems();
      },
      formatValue: function(value) {
        if (value === this.minimum) {
          return `Upto ${value}`;
        } else if (value === this.maximum) {
          return `${value} +`;
        } else {
          return value;
        }
      },
    },
  }
</script>