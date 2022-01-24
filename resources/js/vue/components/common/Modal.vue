<template>
  <transition name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <a class="close-button text-white" @click.prevent="$emit('close')">
          <i class="far fa-2x fa-times-circle" aria-label="close"></i>
        </a>
        <div class="modal-container">
          <div class="modal-header">
            <slot name="header"></slot>
          </div>

          <div class="modal-body">
            <slot name="body"></slot>
          </div>

          <div class="modal-footer">
            <slot name="footer"></slot>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  data: function() {
    return {
      showModal: false
    };
  }
};
</script>
