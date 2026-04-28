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
  type: "",
  location: "",
  unit: "",
  min_value: null,
  max_value: null,
  status: "active",
  device_id: null,
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
  form.post(route("sensors.store"), {
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
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
          Novo Sensor
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
              <p class="text-sm font-medium text-green-800">Sensor cadastrado com sucesso!</p>
              <p class="text-xs text-green-600 mt-1">O sensor foi adicionado ao sistema.</p>
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
                  <h3 class="text-lg font-semibold text-gray-800">Informações do Sensor</h3>
                  <p class="text-sm text-gray-500 mt-1">Preencha os dados abaixo para cadastrar um novo sensor</p>
                </div>
              </div>
            </div>

            <div class="p-6 space-y-6">
              <!-- Nome do Sensor -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Nome do Sensor *
                </label>
                <input
                  v-model="form.name"
                  type="text"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                  placeholder="Ex: Sensor de Temperatura Sala"
                  :class="{ 'border-red-500': form.errors.name }"
                />
                <p v-if="form.errors.name" class="mt-1 text-sm text-red-600 flex items-center gap-1">
                  <AlertCircle :size="12" />
                  {{ form.errors.name }}
                </p>
              </div>

              <!-- Tipo do Sensor e Localização -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Tipo do Sensor *
                  </label>
                  <select
                    v-model="form.type"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                    :class="{ 'border-red-500': form.errors.type }"
                  >
                    <option value="">Selecione o tipo</option>
                    <option v-for="type in sensorTypes" :key="type.value" :value="type.value">
                      {{ type.label }}
                    </option>
                  </select>
                  <p v-if="form.errors.type" class="mt-1 text-sm text-red-600">{{ form.errors.type }}</p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Localização *
                  </label>
                  <select
                    v-model="form.location"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                    :class="{ 'border-red-500': form.errors.location }"
                  >
                    <option value="">Selecione a localização</option>
                    <option v-for="loc in locations" :key="loc.value" :value="loc.value">
                      {{ loc.label }}
                    </option>
                  </select>
                  <p v-if="form.errors.location" class="mt-1 text-sm text-red-600">{{ form.errors.location }}</p>
                </div>
              </div>

              <!-- Unidade de Medida -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Unidade de Medida *
                </label>
                <select
                  v-model="form.unit"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                  :class="{ 'border-red-500': form.errors.unit }"
                >
                  <option value="">Selecione a unidade</option>
                  <option v-for="unit in units" :key="unit.value" :value="unit.value">
                    {{ unit.label }}
                  </option>
                </select>
                <p v-if="form.errors.unit" class="mt-1 text-sm text-red-600">{{ form.errors.unit }}</p>
              </div>

              <!-- Valores Mínimo e Máximo -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Valor Mínimo
                  </label>
                  <input
                    v-model="form.min_value"
                    type="number"
                    step="any"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                    placeholder="Ex: 0"
                  />
                  <p class="mt-1 text-xs text-gray-500">Valor mínimo esperado para leitura</p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Valor Máximo
                  </label>
                  <input
                    v-model="form.max_value"
                    type="number"
                    step="any"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                    placeholder="Ex: 100"
                  />
                  <p class="mt-1 text-xs text-gray-500">Valor máximo esperado para leitura</p>
                </div>
              </div>

              <!-- Status -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Status
                </label>
                <div class="flex gap-6">
                  <label class="flex items-center gap-2">
                    <input
                      v-model="form.status"
                      type="radio"
                      value="active"
                      class="w-4 h-4 text-blue-600 focus:ring-blue-500"
                    />
                    <span class="text-sm text-gray-700">Ativo</span>
                  </label>
                  <label class="flex items-center gap-2">
                    <input
                      v-model="form.status"
                      type="radio"
                      value="inactive"
                      class="w-4 h-4 text-gray-400 focus:ring-gray-500"
                    />
                    <span class="text-sm text-gray-700">Inativo</span>
                  </label>
                </div>
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
