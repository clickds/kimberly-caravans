<template>
  <div class="custom-dropdown" v-click-outside="handleClickOutside">
    <div class="placeholder-container" :class="{ loading: loading }">
      <input
        :disabled="loading"
        type="text"
        readonly
        :placeholder="placeholder"
        class="w-4/5 bg-transparent"
        @click.prevent="open = true"
      />
      <i @click="handleArrowClick" class="fas icon" :class="[open ? 'fa-angle-up' : 'fa-angle-down']"></i>
    </div>
    <ul class="dropdown-container" role="listbox" :class="{ open: open }">
      <div class="options-container">
        <li
          class="dropdown-option"
          role="option"
          :class="{ loading: loading, selected: selectedOptions.length === 0 }"
          @click="handleAllClick"
        >
          <span class="label">All</span>
          <i
            class="far"
            :class="[
              selectedOptions.length === 0
                ? 'fa-check-square text-endeavour'
                : 'fa-square text-gallery'
            ]"
            aria-hidden="false"
          ></i>
        </li>
        <li
          v-for="(option, index) in options"
          :key="index"
          @click="updateSelectedOptions(option.name)"
          class="dropdown-option"
          role="option"
          :class="{ loading: loading, selected: isOptionSelected(option.name) }"
        >
          <span class="label">{{ option.name }}</span>
          <i
            class="far"
            :class="[
              isOptionSelected(option.name)
                ? 'fa-check-square text-endeavour'
                : 'fa-square text-gallery'
            ]"
            aria-hidden="false"
          ></i>
        </li>
      </div>
      <button class="dropdown-done-button" type="button" @click.prevent="handleDoneClick">Done</button>
    </ul>
  </div>
</template>

<script>
import ClickOutside from "vue-click-outside";
import { isEqual } from 'lodash-es';

export default {
  props: {
    placeholder: {
      type: String,
      required: true
    },
    filterName: {
      type: String,
      required: true
    },
    options: {
      type: Array,
      default: []
    },
    initialSelectedOptions: {
      type: Array,
    },
    loading: {
      type: Boolean,
      required: true,
    },
  },

  data: function() {
    return {
      open: false,
      selectedOptions: [...this.initialSelectedOptions],
    };
  },

  methods: {
    toggleOpen: function() {
      this.open = !this.open;
    },
    isOptionSelected: function (option) {
      return this.selectedOptions.includes(option);
    },
    handleAllClick: function (filterName) {
      this.selectedOptions = [];
    },
    updateSelectedOptions: function(option) {
      if (this.loading) {
        return;
      }

      if (this.isOptionSelected(option)) {
        this.selectedOptions = this.selectedOptions.filter(selectedOption => option !== selectedOption);
      } else {
        this.selectedOptions.push(option);
      }
    },
    handleDoneClick: function () {
      if (this.open) {
        this.open = false;
      }
      this.setSelectedOptionsAndPerformSearch();
    },
    handleClickOutside: function () {
      if (this.open) {
        this.open = false;
        this.setSelectedOptionsAndPerformSearch();
      }
    },
    handleArrowClick: function () {
      this.open = !this.open;
      this.setSelectedOptionsAndPerformSearch();
    },
    setSelectedOptionsAndPerformSearch: function () {
      if (isEqual(this.selectedOptions, this.initialSelectedOptions)) {
        return;
      }

      this.$emit('selected-options-updated', this.filterName, this.selectedOptions);
    },
  },
  watch: {
    initialSelectedOptions: function (newSelectedOptions) {
      this.selectedOptions = [...newSelectedOptions];
    },
  },
  directives: {
    ClickOutside,
  },
};
</script>
