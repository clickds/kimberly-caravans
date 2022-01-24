<template>
  <div>
    <strong>Currently displaying:</strong>
    <span class="text-endeavour">{{ pagination.from }}</span> to
    <span class="text-endeavour">{{ pagination.to }}</span> of
    <span class="text-endeavour">{{ pagination.total }}</span> items
  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  computed: mapState(["pagination"])
};
</script>