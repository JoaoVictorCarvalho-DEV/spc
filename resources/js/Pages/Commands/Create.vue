<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import {
  Thermometer,
  Droplet,
  Wind,
  Gauge,
  Activity,
  Save,
  X,
  Zap,
  AlertCircle,
  Command,
  CheckCircle,
  Clock,
} from "@lucide/vue";
import { ref, computed } from "vue";

const props = defineProps({
  device: Object,
  available_commands: Array
});

const form = useForm({
  command: "",
  execute_at: "",
});

// Estados de UI
const showSuccess = ref(false);
const showError = ref(false);
const errorMessage = ref("");
const isLoading = ref(false);

// Computed para data mínima
const minDateTime = computed(() => {
  const now = new Date();
  now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
  return now.toISOString().slice(0, 16);
});

const submit = () => {
  isLoading.value = true;

  form.post(route("commands.storeForm", props.device.id), {
    preserveScroll: true,
    onSuccess: (page) => {
      isLoading.value = false;
      showSuccess.value = true;
      errorMessage.value = "";
      form.reset();

      setTimeout(() => {
        showSuccess.value = false;
      }, 5000);
    },
    onError: (errors) => {
      isLoading.value = false;
      showError.value = true;

      // Formatar mensagens de erro
      if (errors.error) {
        errorMessage.value = errors.error;
      } else if (errors.command) {
        errorMessage.value = errors.command;
        form.errors.command = errors.command;
      } else if (errors.execute_at) {
        errorMessage.value = errors.execute_at;
        form.errors.execute_at = errors.execute_at;
      } else {
        errorMessage.value = "Ocorreu um erro ao processar o agendamento. Verifique os dados e tente novamente.";
      }

      setTimeout(() => {
        showError.value = false;
      }, 5000);
    },
  });
};

const cancel = () => {
  router.visit(route('devices.show', props.device.id));
};
</script>

<template>
  <Head title="Novo Agendamento" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-white">
          Novo agendamento para {{ device.name }}
        </h2>
        <button
          @click="cancel"
          class="flex items-center gap-2 px-4 py-2 text-sm text-slate-300 hover:text-white transition"
        >
          <X :size="16" />
          Cancelar
        </button>
      </div>
    </template>

    <div class="py-10">
      <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
        <!-- Mensagem de erro -->
        <div
          v-if="showError"
          class="mb-6 bg-red-500/10 border-l-4 border-red-400 p-4 rounded-3xl shadow-slate-950/40 animate-fade-in"
        >
          <div class="flex items-start gap-3">
            <div class="bg-red-600/10 p-1 rounded-full flex-shrink-0">
              <AlertCircle :size="16" class="text-red-300" />
            </div>
            <div class="flex-1">
              <p class="text-sm font-medium text-red-200">
                Erro ao cadastrar agendamento
              </p>
              <p class="text-xs text-red-300 mt-1">
                {{ errorMessage }}
              </p>
            </div>
            <button
              @click="showError = false"
              class="text-red-300 hover:text-red-200"
            >
              <X :size="14" />
            </button>
          </div>
        </div>

        <!-- Mensagem de sucesso -->
        <div
          v-if="showSuccess"
          class="mb-6 bg-emerald-500/10 border-l-4 border-emerald-400 p-4 rounded-3xl shadow-slate-950/40 animate-fade-in"
        >
          <div class="flex items-start gap-3">
            <div class="bg-emerald-600/10 p-1 rounded-full flex-shrink-0">
              <CheckCircle :size="16" class="text-emerald-300" />
            </div>
            <div class="flex-1">
              <p class="text-sm font-medium text-emerald-200">
                Agendamento cadastrado com sucesso!
              </p>
              <p class="text-xs text-emerald-300 mt-1">
                O comando será executado na data e horário programados.
              </p>
            </div>
            <button
              @click="showSuccess = false"
              class="text-emerald-300 hover:text-emerald-200"
            >
              <X :size="14" />
            </button>
          </div>
        </div>

        <!-- Formulário -->
        <form @submit.prevent="submit" class="space-y-6">
          <div
            class="bg-slate-900 border border-slate-800 shadow-slate-950/40 rounded-3xl overflow-hidden"
          >
            <div class="p-6 border-b border-slate-800 bg-slate-950">
              <div class="flex items-center gap-3">
                <div class="bg-emerald-500/10 p-2 rounded-lg">
                  <Clock :size="20" class="text-emerald-300" />
                </div>
                <div>
                  <h3 class="text-lg font-semibold text-white">
                    Informações do agendamento
                  </h3>
                  <p class="text-sm text-slate-400 mt-1">
                    Agende um comando para ser executado no dispositivo.
                  </p>
                </div>
              </div>
            </div>

            <div class="p-6 space-y-6">
              <!-- Seletor de Comando -->
              <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                  Comando *
                </label>
                <select
                  v-model="form.command"
                  class="w-full px-4 py-2 border border-slate-700 rounded-lg bg-slate-900 text-slate-100 focus:ring-emerald-500 focus:border-emerald-500 transition disabled:opacity-50"
                  :class="{ 'border-red-500': form.errors.command }"
                  :disabled="isLoading"
                >
                  <option disabled value="">Selecione um comando</option>
                  <option
                    v-for="cmd in available_commands"
                    :key="cmd.value"
                    :value="cmd.value"
                  >
                    {{ cmd.label }}
                  </option>
                </select>

                <div v-if="form.errors.command" class="mt-1 text-sm text-red-400 flex items-center gap-1">
                  <AlertCircle :size="12" />
                  {{ form.errors.command }}
                </div>
              </div>

              <!-- Data/Hora de Execução -->
              <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                  Executar em: *
                </label>
                <input
                  v-model="form.execute_at"
                  type="datetime-local"
                  :min="minDateTime"
                  class="w-full px-4 py-2 border border-slate-700 rounded-lg bg-slate-900 text-slate-100 placeholder-slate-500 focus:ring-emerald-500 focus:border-emerald-500 transition disabled:opacity-50"
                  :class="{ 'border-red-500': form.errors.execute_at }"
                  :disabled="isLoading"
                />

                <div class="mt-1 flex items-center justify-between">
                  <p v-if="form.errors.execute_at" class="text-sm text-red-400 flex items-center gap-1">
                    <AlertCircle :size="12" />
                    {{ form.errors.execute_at }}
                  </p>
                  <p v-else class="text-xs text-slate-500">
                    Data e hora em que o comando será executado
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Botões de ação -->
          <div class="flex items-center justify-end gap-4">
            <button
              type="button"
              @click="cancel"
              class="px-6 py-2 border border-slate-700 rounded-lg text-slate-300 hover:bg-slate-800 transition disabled:opacity-50"
              :disabled="isLoading"
            >
              Cancelar
            </button>

            <button
              type="submit"
              :disabled="isLoading || !form.command || !form.execute_at"
              class="flex items-center gap-2 px-6 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <Save :size="18" />
              {{ isLoading ? "Processando..." : "Agendar Comando" }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
@keyframes fade-in {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fade-in 0.3s ease-out;
}
</style>
