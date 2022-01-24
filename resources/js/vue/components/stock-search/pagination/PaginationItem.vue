<template>
  <li
    class="pagination-item"
    :class="[
      additionalClasses,
      pagination.current_page === pageNumber ? 'active': ''
    ]"
    :aria-current="pagination.current_page === pageNumber ? 'page': ''"
    @click="onClick(pageNumber)"
  >
    <span v-if="pageNumber == pagination.current_page">
      <slot>{{ pageNumber }}</slot>
    </span>
    <a v-else @click.prevent="onClick(pageNumber)">
      <slot>{{ pageNumber }}</slot>
    </a>
  </li>
</template>

<script>
import { mapActions, mapState } from "vuex";

export default {
  props: {
    pageNumber: Number,
    additionalClasses: {
      type: String,
      default: ""
    }
  },

  computed: mapState(["pagination"]),

  methods: {
    ...mapActions(["selectPage"]),

    onClick: function(pageNumber) {
      this.$emit('pagination-item-clicked');
      this.selectPage(pageNumber);
    }
  }
};
</script>
