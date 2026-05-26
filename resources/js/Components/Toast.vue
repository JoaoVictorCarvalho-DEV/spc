<!-- resources/js/components/Toast.vue -->
<template>
  <div class="fixed bottom-4 right-4 z-50 space-y-2">
    <TransitionGroup name="toast" tag="div">
      <div
        v-for="toast in toasts"
        :key="toast.id"
        :class="[
          'flex items-center p-4 rounded-lg shadow-lg min-w-[300px] max-w-md',
          'transform transition-all duration-300',
          toastClasses(toast.type),
        ]"
        @mouseenter="pauseTimer(toast.id)"
        @mouseleave="resumeTimer(toast.id)"
      >
        <!-- Ícone -->
        <div class="flex-shrink-0 mr-3">
          <component :is="getIcon(toast.type)" :size="20" />
        </div>

        <!-- Conteúdo -->
        <div class="flex-1">
          <p class="text-sm font-medium">{{ toast.message }}</p>
          <p v-if="toast.description" class="text-xs mt-1 opacity-75">
            {{ toast.description }}
          </p>
        </div>

        <!-- Botão fechar -->
        <button
          @click="removeToast(toast.id)"
          class="flex-shrink-0 ml-4 text-white opacity-75 hover:opacity-100 transition"
        >
          <X :size="16" />
        </button>
      </div>
    </TransitionGroup>
  </div>
</template>

<script>
import { CheckCircle, AlertCircle, Info, XCircle, X } from "@lucide/vue";
import { getCurrentInstance } from "vue";

export default {
  name: "Toast",

  components: {
    CheckCircle,
    AlertCircle,
    Info,
    XCircle,
    X,
  },

  data() {
    return {
      toasts: [],
      nextId: 1,
    };
  },

  mounted() {
    // Método 1: Expor globalmente
    window.$toast = this.showToast;

    // Método 2: Usar event bus com mitt (recomendado para Vue 3)
    // Verifique se existe um event bus global
    if (window.eventBus) {
      window.eventBus.on("show-toast", this.showToast);
    }
  },

  beforeDestroy() {
    delete window.$toast;
    if (window.eventBus) {
      window.eventBus.off("show-toast", this.showToast);
    }
  },

  methods: {
    showToast({ type, message, description, duration = 5000 }) {
      const id = this.nextId++;
      const toast = {
        id,
        type,
        message,
        description,
        duration,
        timer: null,
      };

      this.toasts.push(toast);
      this.startTimer(toast);

      return id;
    },

    startTimer(toast) {
      if (toast.duration !== 0) {
        toast.timer = setTimeout(() => {
          this.removeToast(toast.id);
        }, toast.duration);
      }
    },

    pauseTimer(id) {
      const toast = this.toasts.find((t) => t.id === id);
      if (toast && toast.timer) {
        clearTimeout(toast.timer);
        toast.timer = null;
      }
    },

    resumeTimer(id) {
      const toast = this.toasts.find((t) => t.id === id);
      if (toast && !toast.timer && toast.duration !== 0) {
        toast.timer = setTimeout(() => {
          this.removeToast(toast.id);
        }, toast.duration);
      }
    },

    removeToast(id) {
      const index = this.toasts.findIndex((t) => t.id === id);
      if (index !== -1) {
        const toast = this.toasts[index];
        if (toast.timer) {
          clearTimeout(toast.timer);
        }
        this.toasts.splice(index, 1);
      }
    },

    toastClasses(type) {
      const classes = {
        success: "bg-green-500 text-white",
        error: "bg-red-500 text-white",
        warning: "bg-yellow-500 text-white",
        info: "bg-blue-500 text-white",
      };
      return classes[type] || classes.info;
    },

    getIcon(type) {
      const icons = {
        success: "CheckCircle",
        error: "XCircle",
        warning: "AlertCircle",
        info: "Info",
      };
      return icons[type] || icons.info;
    },
  },
};
</script>

<style scoped>
.toast-enter-active,
.toast-leave-active {
  transition: all 0.3s ease;
}

.toast-enter-from {
  transform: translateX(100%);
  opacity: 0;
}

.toast-leave-to {
  transform: translateX(100%);
  opacity: 0;
}

.toast-move {
  transition: transform 0.3s ease;
}
</style>
