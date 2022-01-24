<template>
  <div class="comparison-table">
    <table>
      <tr>
        <th></th>
        <td v-for="stockItem in stockItems">
          <div class="text-xl font-bold mb-5">{{ stockItem.display_name }}</div>
        </td>
      </tr>
      <tr class="bg-white">
        <th></th>
        <td v-for="stockItem in stockItems">
          <img
            class="mb-5"
            v-if="stockItem.images.length > 0"
            :src="stockItem.images[0]"
          />
        </td>
      </tr>
      <tr>
        <th></th>
        <td v-for="stockItem in stockItems">
          <img
            class="mb-5"
            v-if="stockItem.floorplan_images.length > 0"
            :src="stockItem.floorplan_images[0]"
          />
        </td>
      </tr>
      <tr class="bg-white">
        <th></th>
        <td v-for="stockItem in stockItems">
          <a
            :href="stockItem.detail_page_url"
            class="mb-3 button-shiraz block p-3"
            >View this model</a
          >
        </td>
      </tr>
      <tr>
        <th></th>
        <td v-for="stockItem in stockItems">
          <button
            v-on:click="
              $emit('remove-stock-item-from-comparison', stockItem.id)
            "
            class="text-endeavour underline"
          >
            Remove from compare
          </button>
        </td>
      </tr>
      <tr>
        <th>Price</th>
        <td v-for="stockItem in stockItems">
          {{ getFormattedPrice(stockItem.currency_symbol, stockItem.price) }}
        </td>
      </tr>
      <tr v-if="showMotorhomeTableRows">
        <th>Chassis</th>
        <td v-for="stockItem in stockItems">
          {{ stockItem.chassis_manufacturer }}
        </td>
      </tr>
      <tr>
        <th>Berths</th>
        <td v-for="stockItem in stockItems">
          {{ stockItem.berths.join(", ") }}
        </td>
      </tr>
      <tr v-if="showMotorhomeTableRows">
        <th>Designated Seats</th>
        <td v-for="stockItem in stockItems">
          {{ stockItem.designated_seats.join(", ") }}
        </td>
      </tr>
      <tr v-if="showMotorhomeTableRows">
        <th>Conversion</th>
        <td v-for="stockItem in stockItems">{{ stockItem.conversion }}</td>
      </tr>
      <tr v-if="showMotorhomeTableRows">
        <th>Mileage</th>
        <td v-for="stockItem in stockItems">{{ stockItem.mileage }}</td>
      </tr>
      <tr v-if="showMotorhomeTableRows">
        <th>Transmission</th>
        <td v-for="stockItem in stockItems">{{ stockItem.transmission }}</td>
      </tr>
      <tr v-if="showMotorhomeTableRows">
        <th>Engine Size</th>
        <td v-for="stockItem in stockItems">{{ stockItem.engine_size }}cc</td>
      </tr>
      <tr v-if="showMotorhomeTableRows">
        <th>Fuel</th>
        <td v-for="stockItem in stockItems">{{ stockItem.fuel }}</td>
      </tr>
      <tr v-if="showMotorhomeTableRows">
        <th>Registration</th>
        <td v-for="stockItem in stockItems">
          {{ stockItem.registration_number || "N/A" }}
        </td>
      </tr>
      <tr>
        <th>Registration Date</th>
        <td v-for="stockItem in stockItems">
          {{ getFormattedDate(stockItem.registration_date) }}
        </td>
      </tr>
      <tr>
        <th>Layout</th>
        <td v-for="stockItem in stockItems">{{ stockItem.layout_name }}</td>
      </tr>
      <tr>
        <th>Width</th>
        <td v-for="stockItem in stockItems">
          {{ formatAttribute(stockItem.width, "m") }}
        </td>
      </tr>
      <tr>
        <th>Length</th>
        <td v-for="stockItem in stockItems">
          {{ formatAttribute(stockItem.length, "m") }}
        </td>
      </tr>
      <tr>
        <th>Unladen Weight (MRO)</th>
        <td v-for="stockItem in stockItems">
          {{ formatAttribute(stockItem.mro, "kg") }}
        </td>
      </tr>
      <tr>
        <th>Max Weight (MTPLM)</th>
        <td v-for="stockItem in stockItems">
          {{ formatAttribute(stockItem.mtplm, "kg") }}
        </td>
      </tr>
      <tr>
        <th>Payload</th>
        <td v-for="stockItem in stockItems">
          {{ formatAttribute(stockItem.payload, "kg") }}
        </td>
      </tr>
      <tr>
        <th>Description</th>
        <td v-for="stockItem in stockItems">
          <p>{{ stockItem.description }}</p>
        </td>
      </tr>
      <tr>
        <th>Features</th>
        <td v-for="stockItem in stockItems">
          <ul>
            <li v-for="feature in stockItem.features">{{ feature.name }}</li>
          </ul>
        </td>
      </tr>
      <tr>
        <th></th>
        <td v-for="stockItem in stockItems">
          <a
            :href="stockItem.detail_page_url"
            class="mb-3 button-shiraz block p-3"
            >View this model</a
          >
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
import { formatJsonDate, formatPrice } from "../../../utilities/helpers";

export default {
  props: {
    stockItems: {
      type: Array,
    },
    stockType: {
      type: String,
    },
  },
  computed: {
    showMotorhomeTableRows: function () {
      return "motorhome" === this.stockType;
    },
  },
  methods: {
    getFormattedDate: (dateString) => {
      return formatJsonDate(dateString);
    },
    getFormattedPrice: (currencySymbol, price) => {
      return formatPrice(currencySymbol, price);
    },

    formatAttribute: (attributeValue, suffix) => {
      if (attributeValue) {
        return attributeValue + suffix;
      }
      return "TBA";
    },
  },
};
</script>