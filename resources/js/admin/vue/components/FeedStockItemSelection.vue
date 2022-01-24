<template>
  <fieldset class="mb-6 border p-3">
    <legend class="text-xl mb-2">{{ legend }}</legend>
    <div class="mb-6">
      <div class="flex -mx-2">
        <div class="px-2 flex-grow">
          <input type="text" v-model="keywords" placeholder="Search for a model name or unique code" />
        </div>
        <div class="px-2">
          <button
            @click.prevent="clearSearch"
            class="p-2 bg-blue-500 hover:bg-blue-700 text-white rounded"
          >Clear</button>
        </div>
      </div>
      <div v-if="searchResults.length > 0" class="my-2">
        <h4>Search Results</h4>

        <div
          v-for="(result, index) in searchResults"
          :key="result.id"
          class="flex items-center -mx-2 mb-2 py-2 border-b"
        >
          <div class="px-2 flex-grow">{{ result.model }} - {{ result.unique_code }}</div>
          <div class="px-2">
            <button
              @click.prevent="addResultToStockItems(result, index)"
              class="p-2 rounded bg-blue-500 hover:bg-blue-700 text-white"
            >Add</button>
          </div>
        </div>
      </div>
    </div>

    <div class="flex flex-row justify-end" v-if="selectedStockItemIds.length > 0">
      <button
        @click.prevent="removeSelectedStockItems"
        class="p-2 bg-red-500 hover:bg-red-700 rounded text-white"
      >
        Remove selected ({{ selectedStockItemIds.length }})
      </button>
    </div>
    <table class="admin-table w-full mt-5">
      <thead>
        <td>
          <input
            type="checkbox"
            @input="handleSelectAllRecordsChange($event.target.checked)"
            :checked="allSelected"
            v-if="stockItems.length > 0"
          />
        </td>
        <td>Stock Ref</td>
        <td>Model</td>
        <td>Actions</td>
      </thead>
      <tr v-for="(stockItem, index) in stockItems" :key="index">
        <input type="hidden" :name="fieldName" :value="stockItem.id" />
        <td>
          <input
            type="checkbox"
            @input="handleRecordSelectChange(stockItem.id, $event.target.checked)"
            :checked="selectedStockItemIds.includes(stockItem.id)"
          />
        </td>
        <td>{{ stockItem.unique_code }}</td>
        <td>{{ stockItem.model }}</td>
        <td>
          <button
            @click.prevent="removeStockItem(index)"
            class="p-2 bg-red-500 hover:bg-red-700 rounded text-white"
          >
            Remove
          </button>
        </td>
      </tr>
      <tr v-if="stockItems.length === 0">
        <td colspan="4" class="text-center text-xl">No stock items selected</td>
      </tr>
    </table>
  </fieldset>
</template>

<script>
import { searchFeedStockItems } from "../utilities/api";
export default {
  props: {
    currentStockItems: {
      type: Array,
      default: [],
    },
    fieldName: {
      type: String,
      required: true,
    },
    legend: {
      type: String,
      required: true,
    },
    searchUrl: {
      type: String,
      required: true,
    },
  },

  data: function () {
    return {
      keywords: "",
      searchResults: [],
      stockItems: [],
      selectedStockItemIds: [],
    };
  },

  created() {
    this.currentStockItems.forEach((currentItem) => {
      this.stockItems.push(currentItem);
    });
  },

  computed: {
    existingIds: function () {
      return this.stockItems.map((item) => {
        return item.id;
      });
    },
    allSelected: function () {
      let sortedSelectedStockItems = this.selectedStockItemIds.slice().sort();
      let sortedStockItems = this.stockItems.map((stockItem) => stockItem.id).slice().sort();

      return sortedSelectedStockItems.length === sortedStockItems.length && sortedSelectedStockItems.every((value, index) => {
          return value ===  sortedStockItems[index];
      });
    }
  },

  watch: {
    keywords: function (value) {
      if (value.length < 3) {
        return;
      }
      this.updateSearchResults();
    },
  },

  methods: {
    handleRecordSelectChange: function (id, checked) {
      if (checked) {
        this.selectedStockItemIds.push(id);
      } else {
        this.selectedStockItemIds = this.selectedStockItemIds.filter((selectedId) => selectedId !== id);
      }
    },
    handleSelectAllRecordsChange: function (checked) {
      if (checked) {
        this.selectedStockItemIds = this.stockItems.map((stockItem) => stockItem.id);
      } else {
        this.selectedStockItemIds = [];
      }
    },
    removeSelectedStockItems: function () {
      this.stockItems = this.stockItems.filter(
        (stockItem) => !this.selectedStockItemIds.includes(stockItem.id)
      );

      this.selectedStockItemIds = [];
    },
    addResultToStockItems: function (result, index) {
      this.searchResults.splice(index, 1);
      this.stockItems.push(result);
    },
    removeStockItem: function (index) {
      let stockItem = this.stockItems[index];

      this.stockItems.splice(index, 1);

      this.selectedStockItemIds = this.selectedStockItemIds.filter(
        (id) => id !== stockItem.id
      );
    },
    clearSearch: function () {
      this.keywords = "";
      this.searchResults = [];
    },
    updateSearchResults: function () {
      searchFeedStockItems(this.searchUrl, {
        keywords: this.keywords,
        exclude_ids: this.existingIds,
      })
      .then((response) => {
        this.searchResults = response.data;
      })
      .catch((error) => {
        this.searchResults = [];
        console.error(error);
      });
    },
  },
};
</script>