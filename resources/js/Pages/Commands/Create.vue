<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
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
} from "@lucide/vue";
import { ref } from "vue";

const form = useForm({
  command: "",
  execute_at: "",
});

const showSuccess = ref(false);

const sensorTypes = [
  { value: "temperature", label: "Temperatura", icon: Thermometer },
  { value: "humidity", label: "Umidade", icon: Droplet },
  { value: "pressure", label: "Pressão", icon: Gauge },
  { value: "air_quality", label: "Qualidade do Ar", icon: Wind },
  { value: "power", label: "Energia", icon: Zap },
  { value: "motion", label: "Movimento", icon: Activity },
];

const locations = [
  { value: "living_room", label: "Sala de Estar" },
  { value: "bedroom", label: "Quarto" },
  { value: "kitchen", label: "Cozinha" },
  { value: "bathroom", label: "Banheiro" },
  { value: "office", label: "Escritório" },
  { value: "garage", label: "Garagem" },
  { value: "garden", label: "Jardim" },
];

const units = [
  { value: "°C", label: "Graus Celsius (°C)" },
  { value: "%", label: "Porcentagem (%)" },
  { value: "hPa", label: "Hectopascal (hPa)" },
  { value: "ppm", label: "Partes por milhão (ppm)" },
  { value: "W", label: "Watts (W)" },
  { value: "V", label: "Volts (V)" },
  { value: "A", label: "Amperes (A)" },
];

const submit = () => {
  form.post(route("devices.store"), {
    onSuccess: () => {
      showSuccess.value = true;
      setTimeout(() => {
        showSuccess.value = false;
      }, 3000);
      form.reset();
    },
  });
};
</script>

<template>
  <Head title="Novo Agendamento" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
          Novo agendamento
        </h2>
        <button
          @click="$inertia.visit(route('sensors.index'))"
          class="flex items-center gap-2 px-4 py-2 text-sm text-gray-600 hover:text-gray-800 transition"
        >
          <X :size="16" />
          Cancelar
        </button>
      </div>
    </template>

    <div class="py-10">
      <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
        <!-- Mensagem de sucesso -->
        <div
          v-if="showSuccess"
          class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg shadow-sm animate-fade-in"
        >
          <div class="flex items-center gap-3">
            <div class="bg-green-100 p-1 rounded-full">
              <Save :size="16" class="text-green-600" />
            </div>
            <div>
              <p class="text-sm font-medium text-green-800">
                Agendamento cadastrado com sucesso!
              </p>
              <p class="text-xs text-green-600 mt-1">
                Agendamento foi adicionado ao sistema.
              </p>
            </div>
          </div>
        </div>

        <!-- Formulário -->
        <form @submit.prevent="submit" class="space-y-6">
          <!-- Card principal -->
          <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="p-6 border-b bg-gradient-to-r from-gray-50 to-white">
              <div class="flex items-center gap-3">
                <div class="bg-blue-100 p-2 rounded-lg">
                  <Activity :size="20" class="text-blue-600" />
                </div>
                <div>
                  <h3 class="text-lg font-semibold text-gray-800">
                    Informações do agendamento
                  </h3>
                  <p class="text-sm text-gray-500 mt-1">
                    Preencha os dados abaixo para fazer um novo agendamento para o
                    dispositivo.
                  </p>
                </div>
              </div>
            </div>

            <div class="p-6 space-y-6">
              <!-- Nome do Sensor -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Comando *
                </label>
                <input
                  v-model="form.command"
                  type="text"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                  placeholder="Ex: Sensor de Temperatura da Sala"
                  :class="{ 'border-red-500': form.errors.command }"
                />
                <p
                  v-if="form.errors.command"
                  class="mt-1 text-sm text-red-600 flex items-center gap-1"
                >
                  <AlertCircle :size="12" />
                  {{ form.errors.command }}
                </p>
              </div>

              <!-- Localização do sensor -->

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Executar em: *
                </label>
                <input
                  v-model="form.execute_at"
                  type="text"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                  placeholder="Ex: Sala de Estar / Quarto ..."
                  :class="{ 'border-red-500': form.errors.execute_at }"
                />
                <p
                  v-if="form.errors.execute_at"
                  class="mt-1 text-sm text-red-600 flex items-center gap-1"
                >
                  <AlertCircle :size="12" />
                  {{ form.errors.execute_at }}
                </p>
              </div>
            </div>
          </div>

          <!-- Botões de ação -->
          <div class="flex items-center justify-end gap-4">
            <button
              type="button"
              @click="$inertia.visit(route('sensors.index'))"
              class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition"
            >
              Cancelar
            </button>
            <button
              type="submit"
              :disabled="form.processing"
              class="flex items-center gap-2 px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <Save :size="18" />
              {{ form.processing ? "Salvando..." : "Cadastrar Sensor" }}
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
