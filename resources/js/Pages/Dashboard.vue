<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import {
  Power,
  Zap,
  Activity,
  Clock,
  TrendingUp,
  Calendar,
  DollarSign,
  Gauge,
  Timer,
  Cpu,
  Home,
} from "@lucide/vue";

const { devices } = defineProps({
  devices: Array,
});

// Formatar moeda brasileira
const formatCurrency = (value) => {
  return new Intl.NumberFormat("pt-BR", {
    style: "currency",
    currency: "BRL",
  }).format(value);
};

// Estatísticas calculadas
const stats = [
  {
    name: "Dispositivos ativos",
    value: devices.filter((d) => d.status === "online").length,
    icon: Power,
    iconBg: "bg-green-50",
    iconColor: "text-green-600",
    suffix: "",
    prefix: "",
  },
  {
    name: "Consumo total",
    value: "420",
    icon: Zap,
    iconBg: "bg-blue-50",
    iconColor: "text-blue-600",
    suffix: "W",
    prefix: "",
  },
  {
    name: "Eventos hoje",
    value: 12,
    icon: Activity,
    iconBg: "bg-purple-50",
    iconColor: "text-purple-600",
    suffix: "",
    prefix: "",
  },
  {
    name: "Custo estimado",
    value: 2.45,
    icon: DollarSign,
    iconBg: "bg-green-50",
    iconColor: "text-green-600",
    suffix: "",
    prefix: "R$",
    format: true,
  },
];

const events = [
  {
    device: "Tomada Sala",
    event: "Dispositivo ligado",
    time: "10:32",
  },
  {
    device: "Tomada TV",
    event: "Desligamento remoto",
    time: "09:50",
  },
  {
    device: "Tomada Servidor",
    event: "Consumo alto detectado",
    time: "09:10",
  },
];
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">Dashboard</h2>
    </template>

    <div class="py-10">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-8">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div
            v-for="stat in stats"
            :key="stat.name"
            class="bg-white shadow rounded-lg p-6 hover:shadow-md transition"
          >
            <div class="flex items-center justify-between mb-3">
              <div :class="[stat.iconBg, 'p-2 rounded-lg']">
                <component :is="stat.icon" :size="20" :class="stat.iconColor" />
              </div>
              <span class="text-xs text-gray-400">Hoje</span>
            </div>
            <p class="text-sm text-gray-500 mb-1">{{ stat.name }}</p>
            <p class="text-2xl font-bold text-gray-800">
              <template v-if="stat.format">
                {{ formatCurrency(stat.value) }}
              </template>
              <template v-else>
                {{ stat.value }}
                <span v-if="stat.suffix" class="text-sm font-normal text-gray-500">{{
                  stat.suffix
                }}</span>
              </template>
            </p>
          </div>
        </div>

        <!-- Dispositivos -->
        <div class="bg-white shadow rounded-lg">
          <div class="p-6 border-b">
            <div class="flex items-center gap-2">
              <Cpu :size="20" class="text-gray-500" />
              <h3 class="text-lg font-semibold">Dispositivos</h3>
            </div>
          </div>

          <div class="divide-y">
            <Link
              v-for="device in devices"
              :key="device.id"
              :href="route('devices.show', device.id)"
              class="block hover:bg-gray-50 transition"
            >
              <div class="flex items-center justify-between p-6">
                <div class="flex items-center gap-3">
                  <div
                    class="p-2 rounded-lg"
                    :class="device.status === 'online' ? 'bg-green-50' : 'bg-gray-100'"
                  >
                    <Power
                      :size="20"
                      :class="
                        device.status === 'online' ? 'text-green-600' : 'text-gray-400'
                      "
                    />
                  </div>
                  <div>
                    <p class="font-medium text-gray-800">
                      {{ device.name }}
                    </p>
                    <p class="text-sm text-gray-500 flex items-center gap-1">
                      <Zap :size="12" />
                      Consumo: {{ device.power ?? "—" }}
                      <span v-if="device.power" class="text-xs">W</span>
                    </p>
                  </div>
                </div>

                <div class="flex items-center gap-3">
                  <span
                    class="px-3 py-1 text-sm rounded-full inline-flex items-center gap-2"
                    :class="
                      device.status === 'online'
                        ? 'bg-green-100 text-green-700'
                        : 'bg-gray-200 text-gray-700'
                    "
                  >
                    <div
                      class="w-1.5 h-1.5 rounded-full"
                      :class="device.status === 'online' ? 'bg-green-600' : 'bg-gray-500'"
                    ></div>
                    {{ device.status }}
                  </span>
                </div>
              </div>
            </Link>
          </div>
        </div>

        <!-- Eventos Recentes -->
        <div class="bg-white shadow rounded-lg">
          <div class="p-6 border-b">
            <div class="flex items-center gap-2">
              <Activity :size="20" class="text-gray-500" />
              <h3 class="text-lg font-semibold">Eventos recentes</h3>
            </div>
          </div>

          <div class="divide-y">
            <div
              v-for="event in events"
              :key="event.time"
              class="flex justify-between items-center p-6 text-sm hover:bg-gray-50 transition"
            >
              <div class="flex items-center gap-3">
                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                <div>
                  <span class="font-medium">{{ event.device }}</span>
                  <span class="text-gray-600"> — {{ event.event }}</span>
                </div>
              </div>
              <span class="text-gray-500 flex items-center gap-1">
                <Clock :size="12" />
                {{ event.time }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
