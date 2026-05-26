<!-- resources/js/components/ToastManager.vue -->
<template>
  <Toast ref="toastComponent" />
</template>

<script>
import Toast from "./Toast.vue";

export default {
  name: "ToastManager",
  components: { Toast },

  mounted() {
    // Expõe métodos globalmente
    window.toast = {
      success: (message, options = {}) => this.showToast("success", message, options),
      error: (message, options = {}) => this.showToast("error", message, options),
      warning: (message, options = {}) => this.showToast("warning", message, options),
      info: (message, options = {}) => this.showToast("info", message, options),
    };
  },

  methods: {
    showToast(type, message, options = {}) {
      return this.$refs.toastComponent.showToast({
        type,
        message,
        description: options.description,
        duration: options.duration || 5000,
      });
    },
  },
};
</script>
