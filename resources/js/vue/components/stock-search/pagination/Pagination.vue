<template>
  <ul class="pagination">
    <PaginationItem
      v-if="notOnFirstPage"
      additional-classes="first"
      :page-number="1"
      @pagination-item-clicked="handlePaginationItemClicked"
    >
      <i class="fas fa-angle-double-left mr-1"></i>
      First
    </PaginationItem>
    <PaginationItem
      v-if="notOnFirstPage"
      additional-classes="previous"
      :page-number="previousPageNumber"
      @pagination-item-clicked="handlePaginationItemClicked"
    >
      <i class="fas fa-angle-left mr-1"></i>
      Previous
    </PaginationItem>
    <PaginationItem
      v-for="number in paginationRange"
      :key="number"
      :page-number="number"
      @pagination-item-clicked="handlePaginationItemClicked"
    />
    <PaginationItem
      v-if="notOnLastPage"
      additional-classes="next"
      :page-number="nextPageNumber"
      @pagination-item-clicked="handlePaginationItemClicked"
    >
      <i class="fas fa-angle-right mr-1"></i>
      Next
    </PaginationItem>
    <PaginationItem
      v-if="notOnLastPage"
      additional-classes="last"
      :page-number="pagination.last_page"
      @pagination-item-clicked="handlePaginationItemClicked"
    >
      <i class="fas fa-angle-double-right mr-1"></i>
      Last
    </PaginationItem>
  </ul>
</template>

<script>
import PaginationItem from "./PaginationItem";
import { mapState } from "vuex";

export default {
  components: {
    PaginationItem
  },

  props: {
    scrollToTopOnClick: {
      type: Boolean,
      default: false,
    },
  },

  computed: {
    ...mapState(["pagination"]),

    notOnFirstPage: function() {
      return this.pagination.current_page != 1;
    },

    notOnLastPage: function() {
      return this.pagination.current_page != this.pagination.last_page;
    },

    previousPageNumber: function() {
      return this.pagination.current_page - 1;
    },

    nextPageNumber: function() {
      return this.pagination.current_page + 1;
    },

    paginationRange: function() {
      let startPageNumber;
      let endPageNumber;
      if (this.pagination.current_page <= 2) {
        startPageNumber = 1;
        endPageNumber = Math.min(5, this.pagination.last_page);
      } else if (
        this.pagination.current_page >=
        this.pagination.last_page - 1
      ) {
        endPageNumber = this.pagination.last_page;
        startPageNumber = Math.max(1, this.pagination.last_page - 5);
      } else {
        startPageNumber = this.pagination.current_page - 2;
        endPageNumber = this.pagination.current_page + 2;
      }

      return this.makeRange(startPageNumber, endPageNumber);
    }
  },

  methods: {
    makeRange: function(start, end) {
      let range = [];
      for (let index = start; index < end + 1; index++) {
        range.push(index);
      }
      return range;
    },

    handlePaginationItemClicked: function () {
      if (!this.scrollToTopOnClick) {
        return;
      }

      this.scrollToTop();
    },

    scrollToTop: function () {
      window.scrollTo(0,0);
    }
  }
};
</script>