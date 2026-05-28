<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import StatsGrid from "@/Components/StatsGrid.vue";
import DevicePowerButton from "@/Components/DeviceToggleButton.vue";
import ToastManager from "@/Components/ToastManager.vue";
import { Power, Zap, Activity, Clock, DollarSign, Cpu, Plus } from "@lucide/vue";

const props = defineProps({
  devices: Array,
  dashboardStats: Array,
});

// Formatar moeda brasileira
const formatCurrency = (value) => {
  return new Intl.NumberFormat("pt-BR", {
    style: "currency",
    currency: "BRL",
  }).format(value);
};

const iconMap = {
  active_devices: {
    icon: Power,
    iconBg: "bg-emerald-500/10",
    iconColor: "text-emerald-300",
  },

  consumption: {
    icon: Zap,
    iconBg: "bg-sky-500/10",
    iconColor: "text-sky-300",
  },

  events: {
    icon: Activity,
    iconBg: "bg-violet-500/10",
    iconColor: "text-violet-300",
  },

  cost: {
    icon: DollarSign,
    iconBg: "bg-emerald-500/10",
    iconColor: "text-emerald-300",
  },
};

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

const dashboardStats = props.dashboardStats.map((item) => ({
  ...item,
  ...iconMap[item.type],
}));
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <ToastManager />
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-white">Dashboard</h2>
    </template>

    <div class="py-10">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-8">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <StatsGrid :stats="dashboardStats" />
        </div>

        <!-- Dispositivos -->
        <div class="bg-slate-900 border border-slate-800 shadow-slate-950/40 rounded-3xl">
          <div class="flex justify-between p-6 border-b border-slate-800">
            <div class="flex items-center gap-2">
              <Cpu :size="20" class="text-slate-300" />
              <h3 class="text-lg font-semibold text-white">Dispositivos</h3>
            </div>
            <div>
              <Link :href="route('devices.create')" class="flex items-center gap-2 text-slate-300 hover:text-white">
                <Plus
                  :size="16"
                  class="text-slate-400 hover:text-white cursor-pointer"
                />
                <h3 class="text-lg font-semibold">Cadastrar novo</h3>
              </Link>
            </div>
          </div>

          <div class="divide-y divide-slate-800">
            <div
              v-for="device in props.devices"
              :key="device.id"
              class="block hover:bg-slate-900/90 transition"
            >
              <div class="flex items-center justify-between p-6">
                <div class="flex items-center gap-3">
                  <div>
                    <device-power-button
                      :device-id="device.id"
                      :device-status="device.status"
                      @command-executed="handleCommandExecuted"
                      @notification="handleNotification"
                    />
                  </div>

                  <div>
                    <Link
                      :href="route('devices.show', device.id)"
                      class="hover:underline"
                    >
                      <p class="font-medium text-white">
                        {{ device.name }}
                      </p>
                    </Link>
                    <p class="text-sm text-slate-400 flex items-center gap-1">
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
                        ? 'bg-emerald-500/10 text-emerald-300'
                        : 'bg-slate-800 text-slate-400'
                    "
                  >
                    <div
                      class="w-1.5 h-1.5 rounded-full"
                      :class="device.status === 'online' ? 'bg-emerald-400' : 'bg-slate-500'"
                    ></div>
                    {{ device.status }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Eventos Recentes -->
        <div class="bg-slate-900 border border-slate-800 shadow-slate-950/40 rounded-3xl">
          <div class="p-6 border-b border-slate-800">
            <div class="flex items-center gap-2">
              <Activity :size="20" class="text-slate-300" />
              <h3 class="text-lg font-semibold text-white">Eventos recentes</h3>
            </div>
          </div>

          <div class="divide-y divide-slate-800">
            <div
              v-for="event in events"
              :key="event.time"
              class="flex justify-between items-center p-6 text-sm hover:bg-slate-900/90 transition"
            >
              <div class="flex items-center gap-3">
                <div class="w-2 h-2 bg-emerald-400 rounded-full"></div>
                <div>
                  <span class="font-medium text-white">{{ event.device }}</span>
                  <span class="text-slate-400"> — {{ event.event }}</span>
                </div>
              </div>
              <span class="text-slate-400 flex items-center gap-1">
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
