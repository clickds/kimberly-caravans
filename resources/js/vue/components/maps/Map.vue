<template>
  <div class="h-google-map w-full">
    <GmapMap
      ref="gmap"
      :zoom="12"
      :center="{ lat: latitude, lng: longitude}"
      map-type-id="terrain"
      class="w-full h-full"
      :options="{
        zoomControl: true,
        mapTypeControl: false,
        scaleControl: false,
        streetViewControl: false,
        rotateControl: false,
        fullscreenControl: false,
        disableDefaultUI: false,
      }"
    >
      <GmapMarker
        :position="{ lat: latitude, lng: longitude }"
        :clickable="false"
        :draggable="false"
      />
    </GmapMap>
  </div>
</template>

<script>
import { gmapApi } from "vue2-google-maps";

export default {
  props: {
    latitude: {
      type: Number,
      required: true,
    },
    longitude: {
      type: Number,
      required: true,
    },
  },
  computed: {
    google: gmapApi,
  },
};
</script>
