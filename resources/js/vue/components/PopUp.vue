<template>
  <modal v-if="showModal" @close="closeModal">
    <div slot="body">
      <a :href="getLinkUrl()" :target="getLinkTarget()">
        <img :src="mobileImageUrl" class="md:hidden" />
        <img :src="desktopImageUrl" class="hidden md:block" />
      </a>
    </div>
  </modal>
</template>

<script>
import Modal from "./common/Modal";
import { markPopUpAsDismissed } from "./../utilities/api";

export default {
  components: {
    modal: Modal,
  },

  props: {
    id: {
      type: Number,
      required: true,
    },
    pageUrl: {
      type: String,
      default: '',
    },
    externalUrl: {
      type: String,
      default: '',
    },
    desktopImageUrl: {
      type: String,
      required: true,
    },
    mobileImageUrl: {
      type: String,
      required: true,
    }
  },

  data: function() {
    return {
      showModal: true,
    }
  },

  methods: {
    closeModal: function(event) {
      this.showModal = false;
      this.updateDismissedPopUpsCookie();
    },
    updateDismissedPopUpsCookie: function() {
      let dismissedPopUpIds = JSON.parse(this.$cookies.get('dismissedPopUpIds'));

      if (!dismissedPopUpIds) {
        this.$cookies.set('dismissedPopUpIds', JSON.stringify([this.id]));
      } else {
        dismissedPopUpIds.push(this.id);
        let uniqueDismissedPopUpIds = [...new Set(dismissedPopUpIds)];
        this.$cookies.set('dismissedPopUpIds', JSON.stringify(uniqueDismissedPopUpIds));
      }
    },
    getLinkUrl: function() {
      return '' !== this.pageUrl ? this.pageUrl : this.externalUrl;
    },
    getLinkTarget: function() {
      return '' !== this.pageUrl ? '_self' : '_blank';
    },
  },
};
</script>