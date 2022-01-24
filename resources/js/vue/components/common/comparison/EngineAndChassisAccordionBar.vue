<template>
  <AccordionBar title="Engine & Chassis">
    <div class="flex flex-row">
      <div class="w-1/2 font-bold">Conversion:</div>
      <div class="w-1/2">{{ conversion }}</div>
    </div>
    <div class="flex flex-row">
      <div class="w-1/2 font-bold">Chassis:</div>
      <div class="w-1/2">{{ chassis }}</div>
    </div>
    <div class="flex flex-row">
      <div class="w-1/2 font-bold">Fuel:</div>
      <div class="w-1/2">{{ fuel }}</div>
    </div>
    <div class="flex flex-row">
      <div class="w-1/2 font-bold">Transmission:</div>
      <div class="w-1/2">{{ transmission }}</div>
    </div>
    <div class="flex flex-row">
      <div class="w-1/2 font-bold">Engine Size:</div>
      <div class="w-1/2">{{ engineSize }}cc</div>
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
      conversion: {
        required: true,
        type: String
      },
      chassis: {
        required: true,
        type: String
      },
      fuel: {
        required: true,
        type: String
      },
      transmission: {
        required: true,
        type: String
      },
      engineSize: {
        required: true,
        type: String
      },
    },
  }
</script>