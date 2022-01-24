<template>
  <div>
    <input
        class="w-full p-1 bg-white border border-solid border-tundora"
        :class="{ 'cursor-not-allowed bg-gray-200': loading}"
        type="text"
        placeholder="Stock Search"
        :disabled="loading"
        :value="searchTerm"
        @input="handleSearchTermChange"
    />
  </div>
</template>

<script>
  import { mapActions, mapState, mapGetters } from "vuex";

  export default {
    data: function () {
      return {
        searchTimer: null,
      };
    },
    computed: {
      ...mapState(["loading", "searchTerm"]),
    },
    methods: {
      ...mapActions([
        "applySearchTerm",
        "retrieveStockItems",
      ]),
      handleSearchTermChange: function (event) {
        if (!this.loading) {
          clearTimeout(this.searchTimer);

          this.searchTimer = setTimeout(() => {
            this.applySearchTerm(event.target.value);
            this.retrieveStockItems();
          }, 750);
        }
      },
    },
  }
</script>