<template>
  <form action method="get" ref="form">
    <div class="pt-4 bg-gallery">
      <div class="container mx-auto px-standard">
        <div class="flex flex-wrap -mx-2">
          <div class="mb-2 px-2 w-full md:w-1/2 lg:w-1/3 lg:w-1/5" v-if="categories.length > 0">
            <custom-select
              label="name"
              :options="categories"
              placeholder="Category"
              :initialSelectedValues="selectedCategoryIds"
              v-on:selectedValuesUpdated="handleSelectedCategoriesUpdated"
            />
            <input
              v-for="id in selectedCategoryIds"
              :key="id"
              type="hidden"
              name="review_category_id_in[]"
              :value="id"
            />
          </div>

          <div class="mb-2 px-2 w-full md:w-1/2 lg:w-1/3 lg:w-1/5" v-if="dealers.length > 0">
            <custom-select
              label="name"
              :options="dealers"
              placeholder="Dealer"
              :initialSelectedValues="selectedDealerIds"
              v-on:selectedValuesUpdated="handleSelectedDealersUpdated"
            />
            <input
              v-for="id in selectedDealerIds"
              :key="id"
              type="hidden"
              name="dealer_id_in[]"
              :value="id"
            />
          </div>

          <div class="mb-2 px-2 w-full md:w-1/2 lg:w-1/3 lg:w-1/5" v-if="rangeNames.length > 0">
            <custom-select
              :options="rangeNames"
              placeholder="Ranges"
              :initialSelectedValues="selectedRangeNames"
              v-on:selectedValuesUpdated="handleSelectedRangesUpdated"
            />
            <input
              v-for="id in selectedRangeNames"
              :key="id"
              type="hidden"
              name="range_names[]"
              :value="id"
            />
          </div>

          <div class="mb-2 px-2 w-full md:w-1/2 lg:w-1/3 lg:w-1/5">
            <datepicker
              :value="dateFrom"
              v-on:selected="handleDateFromUpdated"
              input-class="p-1 w-full border border-dove-gray"
              placeholder="Date From"
              format="YYYY-MM-DD"
              name="published_at_gte"
            >
            </datepicker>
          </div>

          <div class="mb-2 px-2 w-full md:w-1/2 lg:w-1/3 lg:w-1/5">
            <datepicker
              :value="dateTo"
              v-on:selected="handleDateToUpdated"
              input-class="p-1 w-full border border-dove-gray"
              placeholder="Date To"
              format="yyyy-MM-dd"
              name="published_at_lte"
            >
            </datepicker>
          </div>
        </div>
      </div>
    </div>
    <div v-if="hasSelectedFilters" class="container mx-auto px-standard py-2 bg-white flex flex-row items-center space-x-5">
      <span
        class="underline text-endeavour cursor-pointer"
        @click="handleClearAll"
      >
        Clear all
      </span>
      <ul class="selected-filters">
        <li v-for="category in selectedCategories" :key="category.id" class="px-2 mb-2">
          <div class="filter-option">
            <span class="font-bold mr-1">Category:</span>
            <span class="name">{{ category.name }}</span>
            <i
              @click="handleRemoveCategory(category.id)"
              class="icon fa fa-times-circle"
              aria-label="remove category filter"
              aria-hidden="false"
            ></i>
          </div>
        </li>
        <li v-for="dealer in selectedDealers" :key="dealer.id" class="px-2 mb-2">
          <div class="filter-option">
            <span class="font-bold mr-1">Dealer:</span>
            <span class="name">{{ dealer.name }}</span>
            <i
              @click="handleRemoveDealer(dealer.id)"
              class="icon fa fa-times-circle"
              aria-label="remove dealer filter"
              aria-hidden="false"
            ></i>
          </div>
        </li>
        <li v-for="rangeName in selectedRangeNames" :key="rangeName" class="px-2 mb-2">
          <div class="filter-option">
            <span class="font-bold mr-1">Range:</span>
            <span class="name">{{ rangeName }}</span>
            <i
              @click="handleRemoveRange(rangeName)"
              class="icon fa fa-times-circle"
              aria-label="remove range filter"
              aria-hidden="false"
            ></i>
          </div>
        </li>
        <li v-if="dateFrom" class="px-2 mb-2">
          <div class="filter-option">
            <span class="font-bold mr-1">Date From:</span>
            <span class="name">{{ formatDate(dateFrom) }}</span>
            <i
              @click="handleRemoveDateFrom"
              class="icon fa fa-times-circle"
              aria-label="remove category filter"
              aria-hidden="false"
            ></i>
          </div>
        </li>
        <li v-if="dateTo" class="px-2 mb-2">
          <div class="filter-option">
            <span class="font-bold mr-1">Date To:</span>
            <span class="name">{{ formatDate(dateTo) }}</span>
            <i
              @click="handleRemoveDateTo"
              class="icon fa fa-times-circle"
              aria-label="remove category filter"
              aria-hidden="false"
            ></i>
          </div>
        </li>
      </ul>
    </div>
  </form>
</template>

<script>
import Datepicker from 'vuejs-datepicker';
import CustomSelect from "../common/CustomSelect";
import { convertArrayItemsToIntegers } from "../../utilities/helpers";

export default {
  components: {
    CustomSelect,
    Datepicker,
  },
  props: {
    categories: {
      type: Array,
      default: [],
    },
    dealers: {
      type: Array,
      default: [],
    },
    rangeNames: {
      type: Array,
      default: [],
    },
  },

  data: function () {
    return {
      dateFrom: null,
      dateTo: null,
      selectedCategoryIds: [],
      selectedDealerIds: [],
      selectedRangeNames: [],
    };
  },

  created() {
    // Laravel's pagination links add indexes to array style URL query params, e.g. video_category_ids[0]
    // We need to remove them to allow this component to function as expected.
    const searchString = decodeURI(window.location.search);
    const regex = /\[([0-9]+)\]/g;
    const sanitisedSearchString = searchString.replaceAll(regex, '[]');
    const urlParams = new URLSearchParams(sanitisedSearchString);

    this.selectedCategoryIds = convertArrayItemsToIntegers(
      urlParams.getAll("review_category_ids[]")
    );
    this.selectedDealerIds = convertArrayItemsToIntegers(
      urlParams.getAll("dealer_id_in[]")
    );
    this.selectedRangeNames = urlParams.getAll("range_names[]");
    this.dateFrom = urlParams.get("published_at_gte");
    this.dateTo = urlParams.get("published_at_lte");
  },

  computed: {
    hasSelectedFilters: function () {
      if (this.dateFrom || this.dateTo) {
        return true;
      }

      const selectedTotal =
        this.selectedCategoryIds.length +
        this.selectedDealerIds.length +
        this.selectedRangeNames.length;

      return selectedTotal > 0;
    },

    selectedCategories: function () {
      return this.categories.filter((item) => {
        return this.selectedCategoryIds.includes(item.id);
      });
    },

    selectedDealers: function () {
      return this.dealers.filter((item) => {
        return this.selectedDealerIds.includes(item.id);
      });
    },
  },

  methods: {
    formatDate: function (date) {
      return (new Date(date)).toLocaleDateString('en-GB');
    },
    submitForm: function () {
      this.$refs.form.submit();
    },

    setSelectedCategoryIds: async function (categoryIds) {
      this.selectedCategoryIds = categoryIds;
    },
    setSelectedDealerIds: async function (dealerIds) {
      this.selectedDealerIds = dealerIds;
    },
    setSelectedRangeNames: async function (rangeNames) {
      this.selectedRangeNames = rangeNames;
    },
    setDateFrom: async function (dateFrom) {
      this.dateFrom = dateFrom;
    },
    setDateTo: async function (dateTo) {
      this.dateTo = dateTo;
    },

    handleSelectedCategoriesUpdated: async function (selectedCategoryIds) {
      await this.setSelectedCategoryIds(selectedCategoryIds);
      this.submitForm();
    },
    handleSelectedDealersUpdated: async function (selectedDealerIds) {
      await this.setSelectedDealerIds(selectedDealerIds);
      this.submitForm();
    },
    handleSelectedRangesUpdated: async function (rangeNames) {
      await this.setSelectedRangeNames(rangeNames);
      this.submitForm();
    },
    handleDateFromUpdated: async function (dateFrom) {
      await this.setDateFrom(dateFrom);
      this.submitForm();
    },
    handleDateToUpdated: async function (dateTo) {
      await this.setDateTo(dateTo);
      this.submitForm();
    },

    handleRemoveCategory: async function(categoryIdToRemove) {
      const index = this.selectedCategoryIds.indexOf(categoryIdToRemove);
      if (index !== -1) {
        await this.setSelectedCategoryIds(
          this.selectedCategoryIds.filter(categoryId => categoryId !== categoryIdToRemove)
        );
      }

      this.submitForm();
    },
    handleRemoveDealer: async function(dealerIdToRemove) {
      const index = this.selectedDealerIds.indexOf(dealerIdToRemove);
      if (index !== -1) {
        await this.setSelectedDealerIds(
          this.selectedDealerIds.filter(dealerId => delaerId !== dealerIdToRemove)
        );
      }
      this.submitForm();
    },
    handleRemoveRange: async function (rangeNameToRemove) {
      const index = this.selectedRangeNames.indexOf(rangeNameToRemove);
      if (index !== -1) {
        await this.setSelectedRangeNames(
          this.selectedRangeNames.filter(rangeName => rangeName !== rangeNameToRemove)
        );
      }
      this.submitForm();
    },
    handleRemoveDateFrom: async function () {
      await this.setDateFrom(null);
      this.submitForm();
    },
    handleRemoveDateTo: async function () {
      await this.setDateTo(null);
      this.submitForm();
    },

    handleClearAll: async function() {
      await this.setSelectedRangeNames([]);
      await this.setSelectedDealerIds([]);
      await this.setSelectedCategoryIds([]);
      await this.setDateFrom(null);
      await this.setDateTo(null);

      this.submitForm();
    },
  },
};
</script>
