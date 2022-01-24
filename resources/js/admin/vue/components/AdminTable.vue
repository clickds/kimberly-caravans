<template>
  <div class="my-10">
    <div v-if="enableBulkDelete && selectedIds.length > 0" class="flex flex-row justify-end">
      <form :action="bulkDeleteUrl" method="POST">
        <slot name="delete-method"></slot>
        <slot name="csrf"></slot>
        <input
          type="hidden"
          v-for="(id, index) in selectedIds"
          :key="index"
          :name="`${bulkDeleteInputName}[]`"
          :value="id"
        />
        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded-full inline-block ml-1">
          Delete selected ({{ selectedIds.length }})
        </button>
      </form>
    </div>
    <table class="admin-table w-full mt-5">
      <thead>
        <td v-if="enableBulkDelete">
          <input type="checkbox" @input="handleSelectAllRecordsChange($event.target.checked)" :checked="allSelected" />
        </td>
        <td v-for="(heading, index) in headings" :key="index">{{ heading }}</td>
      </thead>
      <tr v-for="id in recordIds" :key="id">
        <td>
          <input type="checkbox" @input="handleRecordSelectChange(id, $event.target.checked)" :checked="selectedIds.includes(id)" />
        </td>
        <slot :name="`row-cells-${id}`"></slot>
      </tr>
    </table>
  </div>
</template>

<script>
export default {
  props: {
    headings: {
      type: Array,
      required: true,
    },
    enableBulkDelete: {
      type: Boolean,
      default: false,
    },
    bulkDeleteUrl: {
      type: String,
    },
    bulkDeleteInputName: {
      type: String,
    },
    recordIds: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      selectedIds: [],
    };
  },
  computed: {
    allSelected: function () {
      let sortedSelectedIds = this.selectedIds.slice().sort();
      let sortedRecordIds = this.recordIds.slice().sort();

      return sortedSelectedIds.length === sortedRecordIds.length && sortedSelectedIds.every((value, index) => {
          return value ===  sortedRecordIds[index];
      });
    }
  },
  methods: {
    handleRecordSelectChange: function (id, checked) {
      if (checked) {
        this.selectedIds.push(id);
      } else {
        this.selectedIds = this.selectedIds.filter((selectedId) => selectedId !== id);
      }
    },
    handleSelectAllRecordsChange: function (checked) {
      if (checked) {
        this.selectedIds = this.recordIds;
      } else {
        this.selectedIds = [];
      }
    },
  },
};
</script>
