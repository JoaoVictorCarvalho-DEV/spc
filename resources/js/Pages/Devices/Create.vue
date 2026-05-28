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
} from "@lucide/vue";
import { ref } from "vue";

const form = useForm({
  name: "",
  location: "",
  esp_identifier: "",
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
  <Head title="Novo Sensor" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-white">Novo Sensor</h2>
        <button
          @click="$inertia.visit(route('sensors.index'))"
          class="flex items-center gap-2 px-4 py-2 text-sm text-slate-300 hover:text-white transition"
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
          class="mb-6 bg-emerald-500/10 border-l-4 border-emerald-400 p-4 rounded-3xl shadow-slate-950/40 animate-fade-in"
        >
          <div class="flex items-center gap-3">
            <div class="bg-emerald-600/10 p-1 rounded-full">
              <Save :size="16" class="text-emerald-300" />
            </div>
            <div>
              <p class="text-sm font-medium text-emerald-200">
                Sensor cadastrado com sucesso!
              </p>
              <p class="text-xs text-emerald-300 mt-1">
                O sensor foi adicionado ao sistema.
              </p>
            </div>
          </div>
        </div>

        <!-- Formulário -->
        <form @submit.prevent="submit" class="space-y-6">
          <!-- Card principal -->
          <div
            class="bg-slate-900 border border-slate-800 shadow-slate-950/40 rounded-3xl overflow-hidden"
          >
            <div class="p-6 border-b border-slate-800 bg-slate-950">
              <div class="flex items-center gap-3">
                <div class="bg-emerald-500/10 p-2 rounded-lg">
                  <Activity :size="20" class="text-emerald-300" />
                </div>
                <div>
                  <h3 class="text-lg font-semibold text-white">Informações do Sensor</h3>
                  <p class="text-sm text-slate-400 mt-1">
                    Preencha os dados abaixo para cadastrar um novo sensor
                  </p>
                </div>
              </div>
            </div>

            <div class="p-6 space-y-6">
              <!-- Nome do Sensor -->
              <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                  Nome do Sensor *
                </label>
                <input
                  v-model="form.name"
                  type="text"
                  class="w-full px-4 py-2 border border-slate-700 rounded-lg bg-slate-900 text-slate-100 placeholder-slate-500 focus:ring-emerald-500 focus:border-emerald-500 transition"
                  placeholder="Ex: Sensor de Temperatura da Sala"
                  :class="{ 'border-red-500': form.errors.name }"
                />
                <p
                  v-if="form.errors.name"
                  class="mt-1 text-sm text-red-400 flex items-center gap-1"
                >
                  <AlertCircle :size="12" />
                  {{ form.errors.name }}
                </p>
              </div>

              <!-- Localização do sensor -->

              <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                  Localização do sensor *
                </label>
                <input
                  v-model="form.location"
                  type="text"
                  class="w-full px-4 py-2 border border-slate-700 rounded-lg bg-slate-900 text-slate-100 placeholder-slate-500 focus:ring-emerald-500 focus:border-emerald-500 transition"
                  placeholder="Ex: Sala de Estar / Quarto ..."
                  :class="{ 'border-red-500': form.errors.location }"
                />
                <p
                  v-if="form.errors.location"
                  class="mt-1 text-sm text-red-400 flex items-center gap-1"
                >
                  <AlertCircle :size="12" />
                  {{ form.errors.location }}
                </p>
              </div>

              <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                  Identificador do Sensor *
                </label>
                <input
                  v-model="form.esp_identifier"
                  type="text"
                  class="w-full px-4 py-2 border border-slate-700 rounded-lg bg-slate-900 text-slate-100 placeholder-slate-500 focus:ring-emerald-500 focus:border-emerald-500 transition"
                  placeholder="Ex: SEN-001"
                  :class="{ 'border-red-500': form.errors.esp_identifier }"
                />
                <p
                  v-if="form.errors.esp_identifier"
                  class="mt-1 text-sm text-red-400 flex items-center gap-1"
                >
                  <AlertCircle :size="12" />
                  {{ form.errors.esp_identifier }}
                </p>
              </div>
            </div>
          </div>

          <!-- Botões de ação -->
          <div class="flex items-center justify-end gap-4">
            <button
              type="button"
              @click="$inertia.visit(route('sensors.index'))"
              class="px-6 py-2 border border-slate-700 rounded-lg text-slate-300 hover:bg-slate-800 transition"
            >
              Cancelar
            </button>
            <button
              type="submit"
              :disabled="form.processing"
              class="flex items-center gap-2 px-6 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed"
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
