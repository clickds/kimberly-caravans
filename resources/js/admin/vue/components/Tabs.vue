<template>
  <div class="tabs">
    <ul class="flex border-b">
      <li
        v-for="(item, index) in navigation"
        :key="item.slotName"
        class="tab-nav-item"
        :class="{ active: isActive(index) }"
      >
        <a @click.prevent="setActive(index)" href="#">{{ item.title }}</a>
      </li>
    </ul>
    <div>
      <div
        v-for="(item, index) in navigation"
        :key="item.slotName"
        class="tab-content"
        :class="{ active: isActive(index) }"
      >
        <slot :name="item.slotName"></slot>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    navigation: {
      type: Array,
      required: true
    }
  },

  data: function() {
    return {
      activeTabIndex: 0
    };
  },

  methods: {
    setActive: function(index) {
      this.activeTabIndex = index;
    },

    isActive: function(index) {
      return index === this.activeTabIndex;
    }
  }
};
</script>
