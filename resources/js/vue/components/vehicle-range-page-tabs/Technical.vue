<template>
  <div class="w-full">
    <div class="w-full bg-white">
      <div class="container mx-auto py-5 px-standard">
        <h1 class="py-3 md:py-6 text-center text-endeavour">
          {{ selectedModel.formattedName }} Technical Specification
        </h1>
        <div class="flex flex-row items-center justify-center space-x-5">
          <div class="text-endeavour font-bold text-xl">Model select</div>
          <select v-model="selectedModel" class="p-3 border">
            <option
              v-for="(model, index) in models"
              :key="index"
              :value="model"
            >
              {{ model.formattedName }}
            </option>
          </select>
        </div>
      </div>
    </div>
    <div class="w-full bg-gallery">
      <div class="container mx-auto py-5 px-standard technical-tab">
        <div class="flex flex-wrap -mx-2">
          <div class="p-2 mb-2 w-full md:w-1/2">
            <div class="bg-white p-4 h-full">
              <h2 class="section-heading">Vehicle Dimensions & Weights</h2>

              <div class="mb-5">
                <h5 class="table-heading">Model</h5>
                <div class="table-row border-b border-tundora" v-if="isCaravan">
                  <div>Axles</div>
                  <div>{{ selectedModel.axles }}</div>
                </div>
                <div
                  class="table-row border-b border-tundora"
                  v-if="isMotorhome"
                >
                  <div>Seat belts (including driver)</div>
                  <div>{{ selectedModel.seatString }}</div>
                </div>
                <div class="table-row border-b border-tundora">
                  <div>Berths (sleeping positions)</div>
                  <div>{{ selectedModel.berthString }}</div>
                </div>
              </div>

              <div class="mb-5">
                <h5 class="table-heading">Dimensions & Weights</h5>
                <div class="table-row border-b border-tundora">
                  <div>Overall length</div>
                  <div>{{ selectedModel.formattedLength }}</div>
                </div>
                <div class="table-row border-b border-tundora">
                  <div>Overall width{{ isMotorhome ? ' (mirrors folded)' : '' }}</div>
                  <div>{{ selectedModel.formattedWidth }}</div>
                </div>
                <div class="table-row border-b border-tundora">
                  <div>Overall height{{ isMotorhome && selectedModel.height_includes_aerial ? ' (including aerial)' : '' }}</div>
                  <div>{{ selectedModel.formattedHeight }}</div>
                </div>
                <div class="table-row border-b border-tundora" v-if="isMotorhome && selectedModel.high_line_height">
                  <div>High line height</div>
                  <div>{{ selectedModel.formattedHighLineHeight }}</div>
                </div>
                <div class="table-row border-b border-tundora">
                  <div>MTPLM (a)*</div>
                  <div>{{ selectedModel.formattedMtplm }}</div>
                </div>
                <div class="table-row border-b border-tundora">
                  <div>Mass in running order (b)*</div>
                  <div>{{ selectedModel.formattedMro }}</div>
                </div>
                <div class="table-row border-b border-tundora">
                  <div>Maximum user payload (a-b)</div>
                  <div>{{ selectedModel.formattedPayload }}</div>
                </div>
                <div class="table-row border-b border-tundora" v-if="isMotorhome">
                  <div>Gross train weight</div>
                  <div>{{ selectedModel.formattedGrossTrainWeight }}</div>
                </div>
                <div class="table-row border-b border-tundora" v-if="isMotorhome">
                  <div>Maximum trailer weight</div>
                  <div>{{ selectedModel.formattedMaximumTrailerWeight }}</div>
                </div>
              </div>

              <div v-if="isMotorhome && selectedModel.optional_weights.length > 0">
                <h5 class="table-heading">Optional Weights</h5>
                <div
                  class="table-row border-b border-tundora"
                  v-for="(optionalWeight, index) in selectedModel.optional_weights"
                  :key="index"
                >
                  <div>{{ optionalWeight.name }}</div>
                  <div class="wysiwyg" v-html="optionalWeight.value"></div>
                </div>
              </div>
            </div>
          </div>

          <div class="p-2 mb-2 w-full md:w-1/2">
            <div class="bg-white p-4 h-full">
              <div class="mb-5" v-if="selectedModel.bedSizes">
                <h2 class="section-heading">Bed Sizes</h2>
                <h5 class="table-heading">Beds</h5>
                <div
                  class="table-row border-b border-tundora"
                  v-for="(bedSize, index) in selectedModel.bedSizes"
                  :key="index"
                >
                  <div>{{ bedSize.bed_description.name }}</div>
                  <div class="wysiwyg">
                    {{ bedSize.details }}
                  </div>
                </div>
              </div>
              <div v-if="isMotorhome">
                <h2 class="section-heading">Engine & Gearbox Data</h2>
                <h5 class="table-heading">Engine</h5>
                <div class="table-row border-b border-tundora">
                  <div>Chassis</div>
                  <div>{{ selectedModel.chassis_manufacturer }}</div>
                </div>
                <div class="table-row border-b border-tundora">
                  <div>Engine size</div>
                  <div>{{ selectedModel.engine_size }}</div>
                </div>
                <div class="table-row border-b border-tundora">
                  <div>Gearbox</div>
                  <div>{{ selectedModel.transmission }}</div>
                </div>
                <div class="table-row border-b border-tundora">
                  <div>Engine power</div>
                  <div>{{ selectedModel.engine_power }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="w-full container mx-auto px-standard">
        <slot></slot>
      </div>

      <div
        class="w-full container mx-auto px-standard py-5"
        v-if="rangeSpecificationSmallPrints"
      >
        <div
          class="wysiwyg"
          v-for="(specSmallPrint, index) in rangeSpecificationSmallPrints"
          :key="index"
          v-html="specSmallPrint.content"
        ></div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    vehicleType: {
      type: String,
      required: true,
    },
    models: {
      type: Array,
      required: true,
    },
    rangeSpecificationSmallPrints: {
      type: Array,
      required: true,
    },
  },
  data: function () {
    return {
      selectedModel: this.models[0],
      motorhomeVehicleType: "motorhome",
      caravanVehicleType: "caravan",
    };
  },
  computed: {
    isMotorhome: function () {
      return this.motorhomeVehicleType === this.vehicleType;
    },
    isCaravan: function () {
      return this.caravanVehicleType === this.vehicleType;
    },
  },
};
</script>
