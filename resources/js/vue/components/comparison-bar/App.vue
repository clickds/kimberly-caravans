<template>
  <div class="font-heading">
    <CompareContainer />
  </div>
</template>

<script>
  import CompareContainer from "./ComparisonContainer";
  import { mapActions } from "vuex";

  export default {
    components: {
      CompareContainer,
    },
    props: {
      caravanComparisonPageUrl: {
        type: String,
      },
      motorhomeComparisonPageUrl: {
        type: String,
      },
    },
    methods: {
      ...mapActions('comparison', [
        'initialise',
        'initialiseComparisonUrls',
      ]),
    },
    created() {
      let caravanComparisonUrl = '' === this.caravanComparisonPageUrl ? null : this.caravanComparisonPageUrl;
      let motorhomeComparisonUrl = '' === this.motorhomeComparisonPageUrl ? null : this.motorhomeComparisonPageUrl;

      let comparisonUrls = {
        caravanComparisonPageUrl: caravanComparisonUrl,
        motorhomeComparisonPageUrl: motorhomeComparisonUrl,
      };

      this.initialise();
      this.initialiseComparisonUrls(comparisonUrls);
    },
  }
</script>
