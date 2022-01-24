<template>
  <div>
    <div class="pt-10 pl-10">Add upto four of your previous employments. Please list them in date order.</div>
    <div class="field-group" v-for="(previousEmployment, index) in previousEmployments">
      <div class="input-row grid grid-cols-1 gap-10 md:grid-cols-3">
        <div class="input-group">
          <label :for="`employment_history[${index}][position]`"><sup>*</sup> Position</label>
          <input :name="`employment_history[${index}][position]`" v-model="previousEmployments[index].position" type="text" required />
        </div>

        <div class="input-group">
          <label :for="`employment_history[${index}][start_date]`"><sup>*</sup> Start date</label>
          <input :name="`employment_history[${index}][start_date]`" v-model="previousEmployments[index].start_date" type="Date" required />
        </div>

        <div class="input-group">
          <label :for="`employment_history[${index}][end_date]`">End date</label>
          <input :name="`employment_history[${index}][end_date]`" v-model="previousEmployments[index].end_date" type="Date" />
        </div>
      </div>

      <div class="input-group input-row">
        <label :for="`employment_history[${index}][reasons_for_leaving]`"><sup>*</sup> Name and address of employer</label>
        <textarea :name="`employment_history[${index}][employer_details]`" v-model="previousEmployments[index].employer_details" required></textarea>
      </div>

      <div class="input-group input-row">
        <label :for="`employment_history[${index}][reasons_for_leaving]`"><sup>*</sup> Reasons for leaving?</label>
        <textarea :name="`employment_history[${index}][reasons_for_leaving]`" v-model="previousEmployments[index].reasons_for_leaving" required></textarea>
      </div>

      <button @click.prevent="removePreviousEmployment(index)" v-if="previousEmployments.length > 1" class="button-shiraz p-3 mb-5">Remove this position</button>

      <hr>
    </div>

    <button @click.prevent="addPreviousEmployment" v-if="previousEmployments.length < 4" class="button-endeavour p-3 mb-5 ml-10">Add another position</button>
  </div>
</template>

<script>
  export default {
    props: {
      initialEmploymentHistory: {
        type: Array,
        default: function () {
          return [];
        }
      },
    },

    created() {
      if (0 === this.initialEmploymentHistory.length) {
        this.previousEmployments = [
          {
            start_date: null,
            end_date: null,
            employer_details: '',
            reasons_for_leaving: '',
          }
        ];
      }
    },

    data: function() {
      return {
        previousEmployments: this.initialEmploymentHistory,
      };
    },

    methods: {
      addPreviousEmployment: function() {
        this.previousEmployments.push({
          start_date: null,
          end_date: null,
          employer_details: '',
          reasons_for_leaving: '',
        });
      },

      removePreviousEmployment: function(index) {
        this.previousEmployments.splice(index, 1);
      }
    }
  };
</script>
