<template>
  <AccordionBar title="Weight & Dimensions">
    <div class="flex flex-row">
      <div class="w-1/2 font-bold">Layout:</div>
      <div class="w-1/2">{{ layout }}</div>
    </div>
    <div class="flex flex-row justify-between">
      <div class="w-1/2 font-bold">Width:</div>
      <div class="w-1/2">{{ width }}m</div>
    </div>
    <div class="flex flex-row justify-between">
      <div class="w-1/2 font-bold">Length:</div>
      <div class="w-1/2">{{ length }}m</div>
    </div>
    <div class="flex flex-row justify-between">
      <div class="w-1/2 font-bold">MRO:</div>
      <div class="w-1/2">{{ mro }}kg</div>
    </div>
    <div class="flex flex-row justify-between">
      <div class="w-1/2 font-bold">MTPLM:</div>
      <div class="w-1/2">{{ mtplm }}kg</div>
    </div>
    <div class="flex flex-row justify-between">
      <div class="w-1/2 font-bold">Est Payload:</div>
      <div class="w-1/2">{{ estPayload }}kg</div>
    </div>
  </AccordionBar>
</template>

<script>
  import AccordionBar from "./AccordionBar";

  export default {
    components: {
      AccordionBar,
    },
    props: {
      layout: {
        required: true,
        type: String
      },
      width: {
        required: true,
        type: String,
      },
      length: {
        required: true,
        type: String,
      },
      mro: {
        required: true,
        type: Number,
      },
      mtplm: {
        required: true,
        type: Number,
      },
      estPayload: {
        required: true,
        type: Number,
      },
    },
  }
</script>