<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import {
  Power,
  Zap,
  Activity,
  Calendar,
  Clock,
  TrendingUp,
  DollarSign,
  Timer,
  Gauge,
} from "@lucide/vue";

const props = defineProps({
  device: Object,
  events: Array,
  stats: Object,
});

// Formatar moeda brasileira
const formatCurrency = (value) => {
  return new Intl.NumberFormat("pt-BR", {
    style: "currency",
    currency: "BRL",
  }).format(value);
};

// Formatar tempo (segundos para HH:MM:SS)
const formatTime = (seconds) => {
  if (!seconds) return "0h 0min";
  const hours = Math.floor(seconds / 3600);
  const minutes = Math.floor((seconds % 3600) / 60);
  return `${hours}h ${minutes}min`;
};
</script>

<template>
  <Head :title="device.name" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ device.name }}
      </h2>
    </template>

    <div class="py-10">
      <div class="mx-auto max-w-5xl sm:px-6 lg:px-8 space-y-8">
        <!-- Header Card - Design moderno azul -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 shadow-lg rounded-xl p-6">
          <div class="flex items-center justify-between">
            <!-- Lado esquerdo - Status e Consumo Atual -->
            <div class="space-y-3">
              <!-- Status badge -->
              <div class="flex items-center gap-2">
                <div class="bg-white/20 rounded-full p-1">
                  <Activity :size="14" class="text-white" />
                </div>
                <span class="text-blue-100 text-sm">Status atual</span>
              </div>

              <!-- Status com indicador -->
              <div class="flex items-center gap-3">
                <div class="relative">
                  <div
                    class="w-3 h-3 rounded-full"
                    :class="
                      device.status === 'online'
                        ? 'bg-green-400 animate-pulse'
                        : 'bg-gray-300'
                    "
                  ></div>
                  <div
                    class="w-3 h-3 rounded-full absolute inset-0"
                    :class="
                      device.status === 'online'
                        ? 'bg-green-400 animate-ping opacity-75'
                        : ''
                    "
                  ></div>
                </div>
                <span class="text-white text-xl font-semibold capitalize">
                  {{ device.status }}
                </span>
              </div>

              <!-- Consumo atual -->
              <div class="pt-2">
                <div class="flex items-center gap-2 text-blue-100 mb-1">
                  <Zap :size="16" />
                  <span class="text-sm">Consumo atual</span>
                </div>
                <div class="flex items-baseline gap-1">
                  <span class="text-4xl font-bold text-white">
                    {{ stats.consumo_atual?.toFixed(2) ?? "0" }}
                  </span>
                  <span class="text-blue-100">Watts</span>
                </div>
              </div>
            </div>

            <!-- Ícone decorativo -->
            <div class="bg-white/10 rounded-full p-4 backdrop-blur-sm">
              <Zap :size="48" class="text-white" />
            </div>
          </div>
        </div>

        <!-- Grid de Métricas - Todas as estatísticas em um layout organizado -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <!-- Consumo Hoje -->
          <div class="bg-white shadow rounded-lg p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
              <div class="bg-blue-50 p-2 rounded-lg">
                <Zap :size="20" class="text-blue-600" />
              </div>
              <span class="text-xs text-gray-400">Hoje</span>
            </div>
            <p class="text-sm text-gray-500 mb-1">Consumo</p>
            <p class="text-2xl font-bold text-gray-800">
              {{ stats.consumo_atual ?? "—" }}
              <span class="text-sm font-normal text-gray-500">W</span>
            </p>
          </div>

          <!-- Consumo Ontem -->
          <div class="bg-white shadow rounded-lg p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
              <div class="bg-gray-50 p-2 rounded-lg">
                <Calendar :size="20" class="text-gray-600" />
              </div>
              <span class="text-xs text-gray-400">Ontem</span>
            </div>
            <p class="text-sm text-gray-500 mb-1">Consumo</p>
            <p class="text-2xl font-bold text-gray-800">
              {{ stats.consumo_ontem ?? "—" }}
              <span class="text-sm font-normal text-gray-500">kWh</span>
            </p>
          </div>

          <!-- Custo Hoje -->
          <div class="bg-white shadow rounded-lg p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
              <div class="bg-green-50 p-2 rounded-lg">
                <DollarSign :size="20" class="text-green-600" />
              </div>
              <span class="text-xs text-gray-400">Estimado</span>
            </div>
            <p class="text-sm text-gray-500 mb-1">Custo hoje</p>
            <p class="text-2xl font-bold text-green-600">
              {{ formatCurrency(stats.custo_hoje ?? 0) }}
            </p>
          </div>

          <!-- Pico Hoje -->
          <div class="bg-white shadow rounded-lg p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
              <div class="bg-orange-50 p-2 rounded-lg">
                <TrendingUp :size="20" class="text-orange-600" />
              </div>
              <span class="text-xs text-gray-400">Máximo</span>
            </div>
            <p class="text-sm text-gray-500 mb-1">Pico hoje</p>
            <p class="text-2xl font-bold text-orange-600">
              {{ stats.pico_hoje ?? "—" }}
              <span class="text-sm font-normal text-gray-500">W</span>
            </p>
          </div>
        </div>

        <!-- Segunda linha de métricas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <!-- Eventos Hoje -->
          <div class="bg-white shadow rounded-lg p-6 hover:shadow-md transition">
            <div class="flex items-center gap-3 mb-3">
              <div class="bg-purple-50 p-2 rounded-lg">
                <Activity :size="20" class="text-purple-600" />
              </div>
              <div>
                <p class="text-sm text-gray-500">Eventos hoje</p>
                <p class="text-2xl font-bold text-gray-800">
                  {{ stats.eventos_hoje }}
                </p>
              </div>
            </div>
          </div>

          <!-- Tempo Ligado Hoje -->
          <div class="bg-white shadow rounded-lg p-6 hover:shadow-md transition">
            <div class="flex items-center gap-3 mb-3">
              <div class="bg-indigo-50 p-2 rounded-lg">
                <Timer :size="20" class="text-indigo-600" />
              </div>
              <div>
                <p class="text-sm text-gray-500">Tempo ligado hoje</p>
                <p class="text-2xl font-bold text-gray-800">
                  {{ formatTime(stats.tempo_ligado_hoje) }}
                </p>
              </div>
            </div>
          </div>

          <!-- Potência Média -->
          <div class="bg-white shadow rounded-lg p-6 hover:shadow-md transition">
            <div class="flex items-center gap-3 mb-3">
              <div class="bg-red-50 p-2 rounded-lg">
                <Gauge :size="20" class="text-red-600" />
              </div>
              <div>
                <p class="text-sm text-gray-500">Potência média</p>
                <p class="text-2xl font-bold text-gray-800">
                  {{ stats.potencia_media_ligado ?? "—" }}
                  <span class="text-sm font-normal text-gray-500">W</span>
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Desligamentos Programados -->
        <div class="bg-white shadow rounded-lg p-6 hover:shadow-md transition">
          <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
              <div class="bg-gray-100 p-3 rounded-lg">
                <Clock :size="24" class="text-gray-600" />
              </div>
              <div>
                <p class="text-sm text-gray-500">Desligamentos programados</p>
                <p class="text-2xl font-bold text-gray-800">
                  {{ stats.desligamentos_programados }}
                </p>
              </div>
            </div>
            <button
              class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition flex items-center gap-2"
            >
              <Clock :size="16" />
              + Novo agendamento
            </button>
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
              :key="event.id"
              class="flex justify-between items-center p-6 text-sm hover:bg-gray-50 transition"
            >
              <div class="flex items-center gap-3">
                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                {{ event.description }}
              </div>
              <span class="text-gray-500">
                {{ new Date(event.created_at).toLocaleTimeString() }}
              </span>
            </div>
            <div v-if="events.length === 0" class="p-6 text-center text-gray-500">
              Nenhum evento recente
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
