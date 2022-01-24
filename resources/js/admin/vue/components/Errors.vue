<template>
  <div>
    <div class="admin-form-errors" v-show="showErrors">
      <p>Errors:</p>
      <ul>
        <li :key="error" v-for="error in errors">{{ error }}</li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    errorBag: {
      type: Object,
      required: true
    }
  },

  computed: {
    showErrors: function() {
      return Object.keys(this.errorBag).length > 0;
    }
  },

  created() {
    this.fetchErrorsFromBag();
  },

  data: function() {
    return {
      errors: []
    };
  },

  methods: {
    fetchErrorsFromBag: function() {
      Object.keys(this.errorBag).forEach(fieldName => {
        let fieldErrorBag = this.errorBag[fieldName];
        this.errors = this.errors.concat(Object.values(fieldErrorBag));
      });
    }
  }
};
</script>