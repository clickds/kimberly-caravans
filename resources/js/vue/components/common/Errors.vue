<template>
  <div v-if="this.allErrors.length > 0" class="bg-red-300 p-4 mb-5">
    <h3 class="text-sm leading-5 font-medium text-red-800">The form contains the following errors:</h3>
    <div class="mt-2 text-sm leading-5 text-red-700">
      <ul class="list-disc pl-5">
        <li class="mt-1" v-for="error in allErrors" :key="error">{{ error }}</li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    errors: {
      type: Object,
      required: true
    }
  },

  computed: {
    allErrors: function() {
      const allErrors = [];

      for (const field in this.errors) {
        this.errors[field].forEach(error => {
          allErrors.push(error);
        });
      }

      return allErrors;
    }
  }
};
</script>
