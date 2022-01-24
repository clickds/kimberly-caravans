<template>
  <div class="w-full flex md:justify-end font-heading font-semibold">
    <span>Show:</span>
    <ul class="flex">
      <li v-for="option in perPageOptions" :key="option">
        <span class="ml-4 text-shiraz" v-if="currentOption(option)">{{ option }}</span>
        <a
          v-else
          @click.prevent="handleClick(option)"
          class="ml-4 text-endeavour underline cursor-pointer"
        >{{ option }}</a>
      </li>
    </ul>
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";

export default {
  props: {
    scrollToTopOnClick: {
      type: Boolean,
      default: false,
    },
  },

  computed: mapState(["pagination", "perPageOptions"]),

  methods: {
    ...mapActions(["selectPerPage"]),

    currentOption(option) {
      return this.pagination.per_page == option;
    },

    handleClick: function (option) {
      if (this.scrollToTopOnClick) {
        this.scrollToTop();
      }

      this.selectPerPage(option);
    },

    scrollToTop: function () {
      window.scrollTo(0,0);
    }
  }
};
</script>
