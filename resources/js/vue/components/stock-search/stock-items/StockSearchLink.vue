<template>
  <a :href="link" class="block h-full w-full image-center">
    <ResponsiveImage :alt="image.alt" :src="image.src" :src-set="image.srcSet" :width="image.width" class="hidden md:block"></ResponsiveImage>
    <img :src="mobileImage.src" :alt="mobileImage.alt" class="md:hidden" />
  </a>
</template>

<script>
import ResponsiveImage from "../../common/ResponsiveImage";

export default {
  components: {
    ResponsiveImage,
  },

  props: {
    id: {
      type: Number,
      required: true,
    },
    image: {
      type: Object,
      default: () => {
        return {};
      },
    },
    mobileImage: {
      type: Object,
      default: () => {
        return {};
      },
    },
    link: {
      type: String,
      required: true,
    },
    name: {
      type: String,
      required: true,
    },
  },
};
</script>