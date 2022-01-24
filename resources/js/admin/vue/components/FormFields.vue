<template>
  <div>
    <input type="hidden" name="input_name" :value="inputName" />
    <div class="mb-4">
      <label for="name">Name</label>
      <input name="name" v-model="name" type="text" placeholder="Name" required />
    </div>

    <div class="mb-4">
      <label for="label">Label</label>
      <input name="label" v-model="label" type="text" placeholder="Label" required />
    </div>

    <div class="mb-4">
      <label class="inline-flex items-center">
        <input type="hidden" name="required" value="0" />
        <input name="required" value="1" type="checkbox" v-model="required" />
        <span class="ml-2 text-shiraz">Required</span>
      </label>
    </div>

    <div class="mb-4">
      <label for="position">Position</label>
      <input type="number" name="position" v-model="position" />
    </div>

    <div class="mb-4">
      <div v-if="canSelectWidth">
        <label for="width">Width</label>
        <select name="width" v-model="width">
          <option v-for="width in widths" :key="width" :value="width">{{ width }}</option>
        </select>
      </div>
      <input v-else type="hidden" name="width" value="Full" />
    </div>

    <div class="mb-4">
      <label for="type">Type</label>
      <select name="type" v-model="type">
        <option v-for="(name, value) in types" :key="name" :value="value">{{ name }}</option>
      </select>
    </div>

    <div>
      <label for="crm_field_name">CRM Field Name (Campaign Monitor)</label>
      <select name="crm_field_name" v-model="crmFieldName">
        <option value>N/A</option>
        <option v-for="option in crmFieldNames" :key="option" :value="option">{{ option }}</option>
      </select>
    </div>

    <fieldset v-show="optionsRequired">
      <legend>Options</legend>
      <div class="flex -mx-2 my-4" v-for="(option, index) in options" :key="index">
        <div class="w-3/4 mx-2">
          <input type="text" name="options[]" v-model="option.value" :key="index" />
        </div>
        <div class="w-1/4 mx-2">
          <button
            class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
            @click.prevent="removeOption(index)"
          >Remove</button>
        </div>
      </div>

      <button
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
        @click.prevent="addOption"
      >Add Option</button>
    </fieldset>
  </div>
</template>

<script>
export default {
  props: {
    crmFieldNames: {
      type: Array,
      required: true
    },
    initialCrmFieldName: {
      type: String,
      default: ""
    },
    initialName: {
      type: String,
      default: ""
    },
    initialLabel: {
      type: String,
      default: ""
    },
    initialOptions: {
      type: Array,
      default: () => []
    },
    initialPosition: {
      type: Number,
      default: 0
    },
    initialRequired: {
      type: Boolean,
      default: false
    },
    initialType: {
      type: String,
      default: ""
    },
    initialWidth: {
      type: String,
      default: ""
    },
    types: {
      type: Object,
      required: true
    },
    typesWithOptions: {
      type: Array,
      required: true
    },
    widths: {
      type: Array,
      required: true
    }
  },

  data: function() {
    return {
      crmFieldName: this.initialCrmFieldName,
      label: this.initialLabel,
      name: this.initialName,
      options: [],
      position: this.initialPosition,
      required: this.initialRequired,
      type: this.initialType,
      width: this.initialWidth
    };
  },

  created() {
    this.initialOptions.forEach(option => {
      this.options.push({ value: option });
    });
  },

  computed: {
    canSelectWidth: function() {
      return !["multiple-checkboxes", "textarea", "radio-buttons"].includes(
        this.type
      );
    },

    inputName: function() {
      return this.label
        .toLowerCase()
        .replace(/ /g, "_") // Replace space with underscore
        .replace(/[^a-z0-9_]/gi, ""); // Replace anything else with nothing
    },

    optionsRequired: function() {
      return this.typesWithOptions.includes(this.type);
    }
  },

  methods: {
    addOption: function() {
      this.options.push({
        value: ""
      });
    },

    removeOption: function(index) {
      this.options.splice(index, 1);
    }
  }
};
</script>
