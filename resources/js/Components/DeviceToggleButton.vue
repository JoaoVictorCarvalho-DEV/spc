<!-- resources/js/components/DevicePowerButton.vue -->
<template>
  <button
    type="button"
    @click="toggleDevice"
    :disabled="loading"
    class="p-2 rounded-lg transition-colors duration-200 relative"
    :class="buttonClasses"
  >
    <Power :size="20" :class="iconClasses" :stroke-width="loading ? 1.5 : 2" />

    <div
      v-if="loading"
      class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-50 rounded-lg"
    >
      <div
        class="animate-spin rounded-full h-4 w-4 border-2 border-gray-600 border-t-transparent"
      ></div>
    </div>
  </button>
</template>

<script>
import { Power } from "@lucide/vue";

export default {
  name: "DevicePowerButton",

  components: { Power },

  props: {
    deviceId: {
      type: [Number, String],
      required: true,
    },
    deviceStatus: {
      type: String,
      required: true,
      validator: (value) => ["online", "offline"].includes(value),
    },
  },

  data() {
    return {
      loading: false,
      currentStatus: this.deviceStatus,
    };
  },

  computed: {
    buttonClasses() {
      return {
        "hover:bg-gray-100": !this.loading,
        "cursor-not-allowed opacity-50": this.loading,
        "bg-green-50": this.currentStatus === "online" && !this.loading,
        "hover:bg-green-100": this.currentStatus === "online" && !this.loading,
      };
    },

    iconClasses() {
      return {
        "text-green-600": this.currentStatus === "online",
        "text-gray-400": this.currentStatus === "offline",
        "animate-pulse": this.loading,
      };
    },
  },

  methods: {
    async toggleDevice() {
      this.loading = true;

      try {
        const response = await fetch(`/api/devices/${this.deviceId}/commands/toggle`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content,
            Accept: "application/json",
          },
        });

        const data = await response.json();

        // Tratamento para 409 - Comando pendente
        if (response.status === 409) {
          // Usando o toast global
          window.toast?.warning(
            `Comando pendente: ${data.pending_command.command}. Aguarde execução.`,
            {
              description: data.pending_command.time_elapsed
                ? `Aguardando há ${data.pending_command.time_elapsed}`
                : "Novo comando será permitido após execução do anterior",
              duration: 8000,
            }
          );

          // Emite evento também
          this.$emit("notification", {
            type: "warning",
            message: `Comando pendente: ${data.pending_command.command}`,
            description: data.pending_command.time_elapsed,
          });

          return;
        }

        // Tratamento para outros erros
        if (!response.ok) {
          throw new Error(data.error || "Erro ao enviar comando");
        }

        // Sucesso
        this.currentStatus = data.command.command === "turn_off" ? "offline" : "online";

        // Toast de sucesso
        window.toast?.success("Comando enviado com sucesso!", {
          description: `Dispositivo será ${
            this.currentStatus === "online" ? "ligado" : "desligado"
          }`,
          duration: 3000,
        });

        this.$emit("command-executed", {
          deviceId: this.deviceId,
          command: data.command,
          newStatus: this.currentStatus,
        });
      } catch (error) {
        console.error("Erro:", error);

        // Toast de erro
        window.toast?.error("Erro ao enviar comando", {
          description: error.message || "Verifique sua conexão e tente novamente",
          duration: 5000,
        });

        this.$emit("notification", {
          type: "error",
          message: error.message,
        });
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>
