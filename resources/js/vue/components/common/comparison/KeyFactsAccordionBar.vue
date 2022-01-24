<template>
  <AccordionBar title="Key Facts">
    <div class="flex flex-row">
      <div class="w-1/2 font-bold">Berths:</div>
      <div class="w-1/2">{{ berths }}</div>
    </div>
    <div class="flex flex-row" v-if="designatedSeats">
      <div class="w-1/2 font-bold">Designated Seats:</div>
      <div class="w-1/2">{{ designatedSeats }}</div>
    </div>
    <div class="flex flex-row">
      <div class="w-1/2 font-bold">Year:</div>
      <div class="w-1/2">{{ year }}</div>
    </div>
    <div class="flex flex-row" v-if="axles">
      <div class="w-1/2 font-bold">Axles:</div>
      <div class="w-1/2">{{ axles }}</div>
    </div>
    <div class="flex flex-row" v-if="registrationDate">
      <div class="w-1/2 font-bold">Registration Date:</div>
      <div class="w-1/2">{{ getFormattedDate(registrationDate) }}</div>
    </div>
    <div class="flex flex-row" v-if="registrationNumber">
      <div class="w-1/2 font-bold">Registration Number:</div>
      <div class="w-1/2">{{ registrationNumber }}</div>
    </div>
  </AccordionBar>
</template>

<script>
  import AccordionBar from "./AccordionBar";
  import { formatJsonDate } from "../../../utilities/helpers";

  export default {
    components: {
      AccordionBar,
    },
    methods: {
      getFormattedDate: (dateString) => {
        return formatJsonDate(dateString);
      },
    },
    props: {
      berths: {
        required: true,
        type: Number,
      },
      year: {
        required: true,
        type: Number,
      },
      axles: {
        type: Number,
      },
      designatedSeats: {
        type: Number,
      },
      registrationDate: {
        type: String,
      },
      registrationNumber: {
        type: String,
      },
    },
  }
</script>