<template>
    <div class="bg-endeavour border-b border-color-white">
        <div class="relative flex flex-col">
            <div
                @click="onClick"
                class="container mx-auto h-10 bg-endeavour w-full text-white flex flex-row items-center px-5 cursor-pointer"
            >
                <LoadingSpinner width="50px" height="50px" v-if="loading" />
                <div class="w-full flex flex-row justify-between text-base md:text-xl" v-else>
                    <div>Compare upto 3 caravans ({{ caravans.length }})</div>
                    <div class="flex flex-row items-center space-x-5">
                        <span class="cursor-pointer underline text-base" @click="removeAllCaravans" v-if="caravans.length > 0">Clear all</span>
                        <i
                            class="fas text-3xl"
                            v-bind:class="isOpen ? 'fa-angle-up' : 'fa-angle-down'"
                        ></i>
                    </div>
                </div>
            </div>
            <div
                class="h-screen overflow-y-scroll w-full bg-alabaster flex-grow md:absolute z-20 md:left-0 md:top-full md:h-auto md:overflow-auto"
                v-bind:class="{ hidden: !isOpen || loading }"
            >
                <div class="container mx-auto flex flex-col">
                    <CaravanComparisonList />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import CaravanComparisonList from "./CaravanComparisonList";
import { mapState, mapActions } from "vuex";
import LoadingSpinner from "../../common/LoadingSpinner";

export default {
    components: {
        LoadingSpinner,
        CaravanComparisonList
    },
    computed: {
        ...mapState("comparison", ["loading", "caravans"])
    },
    data() {
        return {
            isOpen: false
        };
    },
    methods: {
        ...mapActions("comparison", ["removeAllCaravans"]),
        onClick: function() {
            let initialValue = this.isOpen;
            this.isOpen = !initialValue;
        }
    }
};
</script>
