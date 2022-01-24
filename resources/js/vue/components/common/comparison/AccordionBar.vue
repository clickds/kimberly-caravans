<template>
  <div>
    <div @click="onClick" class="h-10 bg-endeavour w-full text-white flex flex-row items-center px-2 cursor-pointer">
      <p class="w-full text-xl">{{ title }}</p>
      <i class="fas float-right text-3xl" v-bind:class="isOpen ? 'fa-angle-up' : 'fa-angle-down'" ></i>
    </div>

    <div class="w-full p-2" v-bind:class="{ 'hidden': !isOpen }">
      <slot></slot>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      title: {
        required: true,
        type: String
      }
    },
    data() {
      return {
        isOpen: false
      }
    },
    methods: {
      onClick: function () {
        let initialValue = this.isOpen;
        this.isOpen = !initialValue;
      },
    },
  }
</script>