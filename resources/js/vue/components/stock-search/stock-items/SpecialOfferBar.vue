<template>
  <a :href="linkUrl" :disabled="linkUrl == null"
    class="h-8 mb-1 py-1 px-2 text-center text-base flex flex-row justify-between"
        :class="`bg-${backgroundColour} text-${textColour}`"
      >
        <div
          class="w-6 flex flex-row justify-start"
          v-html="iconSvg"
        ></div>
        <div class="flex flex-col justify-center">{{ barText }}</div>
        <div
          class="w-6 flex flex-row justify-end"
          v-html="iconSvg"
        ></div>
  </a>
</template>

<script>
export default {
  props: [
    "backgroundColour",
    "barText",
    "iconSvg",
    "textColour",
    "pages"
  ],

  computed: {
    linkUrl: function () {
      const firstPage = this.pages[0];

      if (firstPage) {
        return firstPage.link;
      }
      return null;
    }
  },
}
</script>
