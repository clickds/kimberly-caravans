<template>
  <div class="h-locations-map w-full">
    <GmapMap
      ref="gmap"
      :zoom="12"
      :center="center"
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
        v-for="(location, index) in locations"
        :position="location.location"
        :clickable="true"
        :draggable="false"
        :key="index"
        @click="toggleInfoWindow(location, index)"
      />
      <gmap-info-window
        :options="infoWindowOptions"
        :position="infoWindowPosition"
        :opened="infoWindowOpen"
        @closeclick="handleInfoWindowClose"
      >
        <div v-html="infoWindowContent"></div>
      </gmap-info-window>
    </GmapMap>
  </div>
</template>

<script>
import { gmapApi } from "vue2-google-maps";

export default {
  props: {
    locations: {
      type: Array,
      required: true,
    },
  },
  mounted() {
    this.$refs.gmap.$mapPromise.then((map) => {
      this.map = map;
      this.bounds = new google.maps.LatLngBounds();

      for (let location of this.locations) {
        this.bounds.extend(location.location);
      }

      this.map.fitBounds(this.bounds);
    });
  },
  computed: {
    google: gmapApi,
    infoWindowPosition: function () {
      return this.selectedInfoWindowIndex !== null
        ? this.locations[this.selectedInfoWindowIndex].location
        : { lat: 0, lng: 0 };
    },
    infoWindowContent: function () {
      return this.selectedInfoWindowIndex !== null
        ? this.locations[this.selectedInfoWindowIndex].infoWindowContent
        : '';
    },
  },
  data: function () {
    return {
      center: { lat: 55.3781, lng: 3.4360 },
      map: null,
      bounds: null,
      selectedInfoWindowIndex: null,
      infoWindowOpen: false,
      infoWindowOptions: {
        pixelOffset: {
          width: 0,
          height: -35,
        },
      },
    };
  },
  methods: {
    toggleInfoWindow: function (location, index) {
      if (this.selectedInfoWindowIndex === index) {
        this.infoWindowOpen = !this.infoWindowOpen;
        return;
      }

      this.selectedInfoWindowIndex = index;
      this.infoWindowOpen = true;
    },
    handleInfoWindowClose: function () {
      this.infoWindowOpen = false;
      this.map.fitBounds(this.bounds);
    },
  },
};
</script>
