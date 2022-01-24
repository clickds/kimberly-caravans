<template>
    <fieldset>
        <legend>Fieldsets</legend>

        <draggable v-model="selectedFieldsets" handle=".handle">
            <div
                class="mb-2"
                v-for="(fieldset, index) in selectedFieldsets"
                :key="fieldset.id"
            >
                <div class="-mx-2 flex flex-wrap">
                    <div class="px-2 w-full md:w-2/3 lg:w-3/4 flex">
                        <div class="handle cursor-pointer w-8 text-center">
                            <i class="fas fa-grip-lines"></i>
                        </div>
                        <div class="flex-grow">
                            <input
                                type="hidden"
                                :name="'fieldset_ids[' + index + ']'"
                                :value="fieldset.id"
                            />
                            {{ fieldset.name }}
                        </div>
                    </div>
                    <div class="px-2 w-full md:w-1/3 lg:w-1/4">
                        <button
                            @click.prevent="removeFieldset(fieldset.id)"
                            class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 ml-2 rounded"
                        >
                            Remove
                        </button>
                    </div>
                </div>
            </div>
        </draggable>

        <div v-if="unselectedFieldsets.length > 0" class="flex flex-wrap -mx-2">
            <div class="px-2 w-full md:w-2/3 lg:w-3/4">
                <select
                    name="new_fieldset"
                    id="new_fieldset"
                    v-model="newFieldsetId"
                >
                    <option
                        v-for="fieldset in unselectedFieldsets"
                        :key="fieldset.id"
                        :value="fieldset.id"
                        >{{ fieldset.name }}</option
                    >
                </select>
            </div>
            <div class="px-2 w-full md:w-1/3 lg:w-1/4">
                <button
                    @click.prevent="addNewFieldset"
                    class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 ml-2 rounded"
                >
                    Add
                </button>
            </div>
        </div>
    </fieldset>
</template>

<script>
import draggable from "vuedraggable";
export default {
    components: {
        draggable
    },

    props: {
        currentFieldsetIds: {
            type: Array,
            required: true
        },
        fieldsets: {
            type: Array,
            required: true
        }
    },

    created() {
        this.currentFieldsetIds.forEach(fieldsetId =>
            this.addToSelectedFieldset(fieldsetId)
        );
    },

    data: function() {
        return {
            newFieldsetId: null,
            selectedFieldsets: []
        };
    },

    computed: {
        unselectedFieldsets: function() {
            return this.fieldsets.filter(
                fieldset => !this.selectedFieldsets.includes(fieldset)
            );
        }
    },

    methods: {
        addNewFieldset: function() {
            this.addToSelectedFieldset(this.newFieldsetId);
        },

        addToSelectedFieldset: function(fieldsetId) {
            const fieldset = this.fieldsets.find(
                fieldset => fieldset.id == fieldsetId
            );
            if (fieldset) {
                this.selectedFieldsets.push(fieldset);
            }
        },

        removeFieldset: function(fieldsetId) {
            const index = this.selectedFieldsets.findIndex(fieldset => {
                return fieldset.id == fieldsetId;
            });

            if (index !== -1) {
                this.selectedFieldsets.splice(index, 1);
            }
        }
    }
};
</script>
