<template>
  <img loading="lazy" :srcset="srcSet" :src="src" :width="width" />
</template>

<script>
export default {
  props: {
    alt: {
      type: String,
      required: true,
    },
    srcSet: {
      type: String,
      required: true,
    },
    src: {
      type: String,
      required: true,
    },
    width: {
      type: Number,
      required: true,
    },
  },
};
</script>