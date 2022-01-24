<template>
  <div v-if="showSelectedFilters" class="flex flex-col space-y-3 md:flex-row md:items-center md:space-x-5">
    <template v-for="(selectedOptionGroup, optionGroupIndex) in selectedOptions">
      <div
        v-for="(selectedOption, selectedOptionIndex) in selectedOptionGroup"
        :key="`selected-option-${selectedOptionIndex}`"
        class="md:hidden"
      >
        <div class="p-3 flex flex-row justify-between items-center bg-white border">
          <div>
            <strong class="mr-1">{{ getFilterByName(optionGroupIndex).displayName }}: </strong><span class="name">{{ selectedOption }}</span>
          </div>
          <i
            @click.stop="$emit('remove-option-filter', optionGroupIndex, selectedOption)"
            class="icon fa fa-times-circle"
            aria-label="remove category filter"
            aria-hidden="false"
          ></i>
        </div>
      </div>
    </template>
    <template v-for="(selectedRange, selectedRangeIndex) in selectedRanges">
      <template v-if="selectedRange.hasOwnProperty('min') || selectedRange.hasOwnProperty('max')">
        <div class="p-3 flex flex-row justify-between items-center bg-white border md:hidden">
          <div>
            <strong class="mr-1">{{ getFilterByName(selectedRangeIndex).displayName }}: </strong><span class="name">{{ getRangeText(selectedRange) }}</span>
          </div>
          <i
            @click.stop="$emit('remove-range-filter', selectedRangeIndex)"
            class="icon fa fa-times-circle"
            aria-label="remove category filter"
            aria-hidden="false"
          ></i>
        </div>
      </template>
    </template>

    <template v-if="searchTerm">
      <div class="p-3 flex flex-row justify-between items-center bg-white border md:hidden">
        <div>
          <strong class="mr-1">Stock Search: </strong><span class="name">{{ searchTerm }}</span>
        </div>
        <i
          @click="$emit('remove-search-term')"
          class="icon fa fa-times-circle"
          aria-label="remove category filter"
          aria-hidden="false"
        ></i>
      </div>
    </template>

    <div class="cursor-pointer font-bold text-endeavour underline w-20" @click="$emit('clear-all-filters')">Clear all</div>
    <ul class="hidden selected-filters md:flex items-center">
      <div
        v-for="(selectedOptionGroup, optionGroupIndex) in selectedOptions"
        :key="`selected-option-group-${optionGroupIndex}`"
        class="flex flex-row"
      >
        <li v-for="(selectedOption, selectedOptionIndex) in selectedOptionGroup" :key="`selected-option-${selectedOptionIndex}`">
          <div class="filter-option mr-2 mb-2">
            <strong class="mr-1">{{ getFilterByName(optionGroupIndex).displayName }}: </strong><span class="name">{{ selectedOption }}</span>
            <i
              @click.stop="$emit('remove-option-filter', optionGroupIndex, selectedOption)"
              class="icon fa fa-times-circle"
              aria-label="remove category filter"
              aria-hidden="false"
            ></i>
          </div>
        </li>
      </div>
      <div
        v-for="(selectedRange, selectedRangeIndex) in selectedRanges"
        :key="`selected-range-${selectedRangeIndex}`"
        class="flex flex-row"
      >
        <li v-if="selectedRange.hasOwnProperty('min') || selectedRange.hasOwnProperty('max')">
          <div
            class="filter-option mr-2 mb-2"
          >
            <strong class="mr-1">{{ getFilterByName(selectedRangeIndex).displayName }}: </strong><span class="name">{{ getRangeText(selectedRange) }}</span>
            <i
              @click.stop="$emit('remove-range-filter', selectedRangeIndex)"
              class="icon fa fa-times-circle"
              aria-label="remove category filter"
              aria-hidden="false"
            ></i>
          </div>
        </li>
      </div>
      <div
        v-if="searchTerm"
        class="flex flex-row"
      >
        <li>
          <div
            class="filter-option mr-2 mb-2"
          >
            <strong class="mr-1">Stock Search: </strong><span class="name">{{ searchTerm }}</span>
            <i
              @click="$emit('remove-search-term')"
              class="icon fa fa-times-circle"
              aria-label="remove category filter"
              aria-hidden="false"
            ></i>
          </div>
        </li>
      </div>
    </ul>
  </div>
</template>

<script>
  import { mapGetters, mapActions } from "vuex";

  export default {
    computed: {
      ...mapGetters([
        "getFilterByName"
      ]),
      showSelectedFilters: function () {
        let hasSelectedOption = Object.keys(this.selectedOptions)
          .filter(selectedOptionGroup => this.selectedOptions[selectedOptionGroup].length > 0)
          .length > 0;

        let hasSelectedRange = Object.keys(this.selectedRanges)
          .filter(range => this.selectedRanges[range].hasOwnProperty('min') || this.selectedRanges[range].hasOwnProperty('max'))
          .length > 0;

        let hasSearchTerm = this.searchTerm !== '';

        return hasSelectedOption || hasSelectedRange || hasSearchTerm;
      },
    },
    props: {
      selectedOptions: {
        type: Object,
      },
      selectedRanges: {
        type: Object,
      },
      searchTerm: {
        type: String,
      },
    },
    methods: {
      getRangeText: function (range) {
        if (range.min && range.max) {
          return `${range.min} - ${range.max}`;
        }
        if (range.min && !range.max) {
          return `${range.min}+`;
        }
        if (!range.min && range.max) {
          return `Upto ${range.max}`;
        }
      },
    },
  };
</script>
