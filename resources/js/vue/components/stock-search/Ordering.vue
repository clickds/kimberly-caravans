<template>
  <div class="flex font-heading font-semibold md:justify-end">
    <span>Order:</span>
    <ul class="ml-2 flex">
      <li v-for="option in orderOptions" :key="option.value" class="ml-2">
        <span v-if="currentOption(option)" class="text-shiraz">{{ option.label }}</span>
        <a
          v-else
          @click.prevent="selectOrder(option.value)"
          class="text-endeavour cursor-pointer underline"
        >{{ option.label }}</a>
      </li>
    </ul>
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";

export default {
  computed: mapState(["order", "orderOptions"]),

  methods: {
    ...mapActions(["selectOrder"]),

    currentOption(option) {
      return this.order === option.value;
    }
  }
};
</script>