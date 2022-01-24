<template>
  <div class="custom-dropdown" v-click-outside="handleClickOutside">
    <div class="placeholder-container">
      <input
        type="text"
        class="w-4/5 bg-transparent"
        readonly
        :placeholder="placeholder"
        @click.prevent="open = true"
      />
      <i @click="toggleOpen" class="fas icon" :class="[open ? 'fa-angle-up' : 'fa-angle-down']"></i>
    </div>
    <ul class="dropdown-container" role="listbox" :class="{ open: open }">
      <div class="options-container">
        <div
          class="dropdown-option"
          role="option"
          :class="{ selected: selectedValues.length === 0 }"
          @click="clearSelectedValues"
        >
          <span class="label">All</span>
          <i
            class="far"
            :class="[
              selectedValues.length === 0
                ? 'fa-check-square text-endeavour'
                : 'fa-square text-gallery'
            ]"
            aria-hidden="false"
          ></i>
        </div>
        <div
          v-for="option in options"
          :key="getOptionKey(option)"
          @click.prevent="updateSelectedValues(option)"
          class="dropdown-option"
          role="option"
          :class="{ selected: isOptionSelected(option) }"
        >
          <span class="label">{{ getOptionLabel(option) }}</span>
          <i
            class="far"
            :class="[
              isOptionSelected(option)
                ? 'fa-check-square text-endeavour'
                : 'fa-square text-gallery'
              ]"
            aria-hidden="false"
          ></i>
        </div>
      </div>
      <button class="dropdown-done-button" type="button" @click.prevent="handleDoneClick">Done</button>
    </ul>
  </div>
</template>

<script>
import ClickOutside from "vue-click-outside";
import { isEqual } from 'lodash-es';

export default {
  model: {
    prop: "initialSelectedValues",
    event: "selectedValuesUpdated"
  },
  props: {
    /**
     * Tells what key to use when generating option values when each `option` is an object.
     * @type {String}
     */
    keyName: {
      type: String,
      default: "id"
    },
    /**
     * Tells what key to use when generating option labels when each `option` is an object.
     * @type {String}
     */
    label: {
      type: String,
      default: "label"
    },
    options: {
      type: Array,
      default: []
    },
    placeholder: {
      type: String,
      required: true
    },
    initialSelectedValues: {
      type: Array,
      default: function() {
        return [];
      }
    }
  },

  data: function() {
    return {
      open: false,
      selectedValues: this.initialSelectedValues,
    };
  },

  methods: {
    closeDropdown: function() {
      this.open = false;
    },

    isOptionSelected: function(option) {
      const keyName = this.getOptionKey(option);
      return this.selectedValues.includes(keyName);
    },

    getOptionKey: function(option) {
      if (typeof option === "string") {
        return option;
      }
      return option[this.keyName];
    },

    getOptionLabel: function(option) {
      if (typeof option === "string") {
        return option;
      }
      return option[this.label];
    },

    toggleOpen: function() {
      this.open = !this.open;
    },

    clearSelectedValues: function () {
      this.selectedValues = [];
    },
    updateSelectedValues: function(option) {
      const keyName = this.getOptionKey(option);
      const index = this.selectedValues.indexOf(keyName);
      if (index === -1) {
        this.selectedValues = this.selectedValues.concat(keyName);
      } else {
        this.selectedValues = this.selectedValues.splice(index, 1);
      }
    },

    handleDoneClick: function () {
      this.toggleOpen();

      if (!isEqual(this.selectedValues.sort(), this.initialSelectedValues.sort())) {
        this.$emit("selectedValuesUpdated", this.selectedValues);
      }
    },
    handleClickOutside: function () {
      if (!this.open) {
        return;
      }

      this.open = false;

      if (!isEqual(this.selectedValues.sort(), this.initialSelectedValues.sort())) {
        this.$emit("selectedValuesUpdated", this.selectedValues);
      }
    },
  },
  directives: {
    ClickOutside,
  },
};
</script>
