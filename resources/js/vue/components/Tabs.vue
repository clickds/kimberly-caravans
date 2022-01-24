<template>
  <div class="tabs" ref="tabContainer">
    <div class="lg:hidden container mx-auto px-standard py-4">
      <div v-for="(tab, index) in tabs" :key="tab" class="mb-2">
        <div class="bg-endeavour text-white container mx-auto px-standard py-2">
          <a
            @click.prevent="toggleAccordion(index)"
            :id="tabId(tab)"
            class="cursor-pointer flex items-center"
            aria-label="Toggle menu"
          >
            <div class="p-1 capitalize text-center flex-grow">{{ tab }}</div>
            <i class="fa" :class="[ accordionIsOpen(index) ? 'fa-chevron-up' : 'fa-chevron-down' ]"></i>
          </a>
        </div>
        <div class="print:block" :class="[ accordionIsOpen(index) ? 'block' : 'hidden' ]">
          <slot :name="slotName(tab)"></slot>
        </div>
      </div>
    </div>

    <div class="hidden lg:block">
      <div
        class="z-10 tabs__navigation tabs__navigation--bg-solid tabs__navigation--bg-solid-endeavour bg-alabaster"
        :class="{ 'tabs__navigation-raised': raised, 'sticky': sticky }"
        :style="sticky ? `top: ${getOffsetTop()}px;` : ''"
      >
        <div class="container mx-auto px-standard">
          <ul class="flex overflow-x-auto text-lg font-medium text-gray-700">
            <li
              v-for="(tab, index) in tabs"
              :key="tab"
              class="cursor-pointer"
              :id="tabId(tab)"
              :class="{ active: isActive(index) }"
              @click="setActive(index)"
            >
              <div class="name capitalize">{{ tab }}</div>
            </li>
          </ul>
        </div>
      </div>
      <div class="tabs__content">
        <div
          v-for="(tab, index) in tabs"
          :key="tab"
          :class="isActive(index) ? 'flex' : 'hidden'"
          class="bg-white flex flex-wrap tab print:block"
        >
          <slot :name="slotName(tab)"></slot>
        </div>
      </div>
      <div v-if="showSecondaryNavigation" class="bg-endeavour">
        <div
          class="container mx-auto py-4 px-standard flex font-heading font-semibold text-4xl text-white capitalize"
        >
          <div class="w-1/3">
            <div v-if="previousTab" @click="setPreviousTabActive" class="cursor-pointer">
              <i class="fas fa-chevron-left"></i>
              {{ previousTab }}
            </div>
          </div>
          <div class="w-1/3 text-center">{{ currentTab }}</div>
          <div class="w-1/3 text-right">
            <div v-if="nextTab" @click="setNextTabActive" class="cursor-pointer">
              {{ nextTab }}
              <i class="fas fa-chevron-right"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import slugify from "slugify";

export default {
  props: {
    firstAccordionOpen: {
      type: Boolean,
      default: false,
    },
    tabs: {
      type: Array,
      required: true,
    },
    raised: {
      type: Boolean,
      default: false,
    },
    showSecondaryNavigation: {
      type: Boolean,
      default: false,
    },
    sticky: {
      type: Boolean,
      default: false,
    },
    offsetTopElementId: {
      type: String,
    }
  },

  data: function () {
    return {
      activeTabIndex: 0,
      openAccordionIndices: this.firstAccordionOpen ? [0] : [],
      offsetTopSticky: 0,
    };
  },

  mounted() {
    let element = document.getElementById(this.offsetTopElementId);

    if (element) {
      new ResizeObserver((entries) => {
        this.offsetTopSticky = entries[0].contentRect.height;
      }).observe(element);
    }

    if (!this.tabs.includes("finance calculator")) {
      return;
    }

    const calculatePaymentButtons = document.querySelectorAll(
      ".calculate-payment"
    );

    calculatePaymentButtons.forEach((button) => {
      button.addEventListener(
        "click",
        function (event) {
          this.scrollToFinanceCalculatorTab(event);
        }.bind(this),
        false
      );
    });
  },

  computed: {
    nextTabIndex: function () {
      if (this.activeTabIndex + 1 !== this.tabs.length) {
        return this.activeTabIndex + 1;
      }
      return null;
    },

    previousTabIndex: function () {
      if (this.activeTabIndex > 0) {
        return this.activeTabIndex - 1;
      }
      return null;
    },

    nextTab: function () {
      if (this.nextTabIndex !== null) {
        return this.tabs[this.nextTabIndex];
      }
      return null;
    },

    previousTab: function () {
      if (this.previousTabIndex !== null) {
        return this.tabs[this.previousTabIndex];
      }
      return null;
    },

    currentTab: function () {
      return this.tabs[this.activeTabIndex];
    },
  },

  methods: {
    scrollToFinanceCalculatorTab: function (event) {
      event.preventDefault();
      this.setActiveByTabName("finance calculator");
      this.$el.scrollIntoView({ behavior: "smooth" });
    },

    setActive: function (index) {
      this.activeTabIndex = index;
    },

    setActiveByTabName: function (tabName) {
      const index = this.tabs.indexOf(tabName);
      this.setActive(index);
      this.toggleAccordion(index);
    },

    isActive: function (index) {
      return index === this.activeTabIndex;
    },

    slotName: function (tab) {
      return this.slugTabName(tab);
    },

    tabId: function (tab) {
      return this.slugTabName(tab);
    },

    slugTabName: function (tab) {
      return slugify(tab, {
        replacement: "-",
        remove: undefined,
        lower: true,
        strict: true,
      });
    },

    accordionIsOpen: function (index) {
      return this.openAccordionIndices.includes(index);
    },

    toggleAccordion: function (index) {
      if (this.accordionIsOpen(index)) {
        const openAccordionIndex = this.openAccordionIndices.indexOf(index);
        this.openAccordionIndices.splice(openAccordionIndex, 1);
      } else {
        this.openAccordionIndices = [index];
      }
    },

    scrollTabContentIntoView: function () {
      var stickyHeaderHeight = document.querySelector('#js-navigation-and-comparison-container').offsetHeight;
      var distanceToTop = window.pageYOffset + this.$refs.tabContainer.getBoundingClientRect().top;
      window.scroll(0, distanceToTop - stickyHeaderHeight);
    },

    setNextTabActive: function () {
      this.setActive(this.nextTabIndex);
      this.scrollTabContentIntoView();
    },

    setPreviousTabActive: function () {
      this.setActive(this.previousTabIndex);
      this.scrollTabContentIntoView();
    },

    getOffsetTop: function () {
      return this.offsetTopSticky;
    }
  },
};
</script>
