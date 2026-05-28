<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import {
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
      <h2 class="text-xl font-semibold leading-tight text-white">
        {{ device.name }}
      </h2>
    </template>

    <div class="py-10">
      <div class="mx-auto max-w-5xl sm:px-6 lg:px-8 space-y-8">
        <!-- Header Card - Design moderno escuro -->
        <div class="bg-slate-900 border border-slate-800 shadow-slate-950/40 rounded-3xl p-6">
          <div class="flex items-center justify-between">
            <!-- Lado esquerdo - Status e Consumo Atual -->
            <div class="space-y-3">
              <!-- Status badge -->
              <div class="flex items-center gap-2">
                <div class="bg-slate-800/70 rounded-full p-1">
                  <Activity :size="14" class="text-emerald-300" />
                </div>
                <span class="text-emerald-300 text-sm">Status atual</span>
              </div>

              <!-- Status com indicador -->
              <div class="flex items-center gap-3">
                <div class="relative">
                  <div
                    class="w-3 h-3 rounded-full"
                    :class="
                      device.status === 'online'
                        ? 'bg-emerald-400 animate-pulse'
                        : 'bg-slate-600'
                    "
                  ></div>
                  <div
                    class="w-3 h-3 rounded-full absolute inset-0"
                    :class="
                      device.status === 'online'
                        ? 'bg-emerald-400 animate-ping opacity-75'
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
                <div class="flex items-center gap-2 text-emerald-300 mb-1">
                  <Zap :size="16" />
                  <span class="text-sm">Consumo atual</span>
                </div>
                <div class="flex items-baseline gap-1">
                  <span class="text-4xl font-bold text-white">
                    {{ stats.consumo_atual ?? "0" }}
                  </span>
                  <span class="text-emerald-300">Watts</span>
                </div>
              </div>
            </div>

            <!-- Ícone decorativo -->
            <div class="bg-slate-800/70 rounded-full p-4 backdrop-blur-sm">
              <Zap :size="48" class="text-white" />
            </div>
          </div>
        </div>

        <!-- Grid de Métricas - Todas as estatísticas em um layout organizado -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <!-- Consumo Hoje -->
          <div class="bg-slate-900 border border-slate-800 shadow-slate-950/40 rounded-3xl p-6 hover:shadow-emerald-500/10 transition">
            <div class="flex items-center justify-between mb-3">
              <div class="bg-emerald-500/10 p-2 rounded-lg">
                <Zap :size="20" class="text-emerald-300" />
              </div>
              <span class="text-xs text-slate-500">Hoje</span>
            </div>
            <p class="text-sm text-slate-400 mb-1">Consumo</p>
            <p class="text-2xl font-bold text-white">
              {{ stats.consumo_atual ?? "—" }}
              <span class="text-sm font-normal text-slate-500">W</span>
            </p>
          </div>

          <!-- Consumo Ontem -->
          <div class="bg-slate-900 border border-slate-800 shadow-slate-950/40 rounded-3xl p-6 hover:shadow-emerald-500/10 transition">
            <div class="flex items-center justify-between mb-3">
              <div class="bg-slate-800 p-2 rounded-lg">
                <Calendar :size="20" class="text-slate-200" />
              </div>
              <span class="text-xs text-slate-500">Ontem</span>
            </div>
            <p class="text-sm text-slate-400 mb-1">Consumo</p>
            <p class="text-2xl font-bold text-white">
              {{ stats.consumo_ontem ?? "—" }}
              <span class="text-sm font-normal text-slate-500">kWh</span>
            </p>
          </div>

          <!-- Custo Hoje -->
          <div class="bg-slate-900 border border-slate-800 shadow-slate-950/40 rounded-3xl p-6 hover:shadow-emerald-500/10 transition">
            <div class="flex items-center justify-between mb-3">
              <div class="bg-emerald-500/10 p-2 rounded-lg">
                <DollarSign :size="20" class="text-emerald-300" />
              </div>
              <span class="text-xs text-slate-500">Estimado</span>
            </div>
            <p class="text-sm text-slate-400 mb-1">Custo hoje</p>
            <p class="text-2xl font-bold text-emerald-300">
              {{ formatCurrency(stats.custo_hoje ?? 0) }}
            </p>
          </div>

          <!-- Pico Hoje -->
          <div class="bg-slate-900 border border-slate-800 shadow-slate-950/40 rounded-3xl p-6 hover:shadow-emerald-500/10 transition">
            <div class="flex items-center justify-between mb-3">
              <div class="bg-orange-500/10 p-2 rounded-lg">
                <TrendingUp :size="20" class="text-orange-300" />
              </div>
              <span class="text-xs text-slate-500">Máximo</span>
            </div>
            <p class="text-sm text-slate-400 mb-1">Pico hoje</p>
            <p class="text-2xl font-bold text-orange-300">
              {{ stats.pico_hoje ?? "—" }}
              <span class="text-sm font-normal text-slate-500">W</span>
            </p>
          </div>
        </div>

        <!-- Segunda linha de métricas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <!-- Eventos Hoje -->
          <div class="bg-slate-900 border border-slate-800 shadow-slate-950/40 rounded-3xl p-6 hover:shadow-emerald-500/10 transition">
            <div class="flex items-center gap-3 mb-3">
              <div class="bg-purple-500/10 p-2 rounded-lg">
                <Activity :size="20" class="text-purple-300" />
              </div>
              <div>
                <p class="text-sm text-slate-400">Eventos hoje</p>
                <p class="text-2xl font-bold text-white">
                  {{ stats.eventos_hoje }}
                </p>
              </div>
            </div>
          </div>

          <!-- Tempo Ligado Hoje -->
          <div class="bg-slate-900 border border-slate-800 shadow-slate-950/40 rounded-3xl p-6 hover:shadow-emerald-500/10 transition">
            <div class="flex items-center gap-3 mb-3">
              <div class="bg-indigo-500/10 p-2 rounded-lg">
                <Timer :size="20" class="text-indigo-300" />
              </div>
              <div>
                <p class="text-sm text-slate-400">Tempo ligado hoje</p>
                <p class="text-2xl font-bold text-white">
                  {{ stats.tempo_ligado_hoje }}
                </p>
              </div>
            </div>
          </div>

          <!-- Potência Média -->
          <div class="bg-slate-900 border border-slate-800 shadow-slate-950/40 rounded-3xl p-6 hover:shadow-emerald-500/10 transition">
            <div class="flex items-center gap-3 mb-3">
              <div class="bg-red-500/10 p-2 rounded-lg">
                <Gauge :size="20" class="text-red-300" />
              </div>
              <div>
                <p class="text-sm text-slate-400">Potência média</p>
                <p class="text-2xl font-bold text-white">
                  {{ stats.potencia_media_ligado ?? "—" }}
                  <span class="text-sm font-normal text-slate-500">W</span>
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Desligamentos Programados -->
        <div class="bg-slate-900 border border-slate-800 shadow-slate-950/40 rounded-3xl p-6 hover:shadow-emerald-500/10 transition">
          <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
              <div class="bg-slate-800 p-3 rounded-lg">
                <Clock :size="24" class="text-slate-200" />
              </div>
              <div>
                <p class="text-sm text-slate-400">Desligamentos programados</p>
                <p class="text-2xl font-bold text-white">
                  {{ stats.desligamentos_programados }}
                </p>
              </div>
            </div>
            <Link
              class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm transition flex items-center gap-2"
              :href="route('commands.create', device.id)"
            >
              <Clock :size="16" />
              + Novo agendamento
            </Link>
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
              :key="event.id"
              class="flex justify-between items-center p-6 text-sm hover:bg-slate-900/90 transition"
            >
              <div class="flex items-center gap-3">
                <div class="w-2 h-2 bg-emerald-400 rounded-full"></div>
                <span class="text-slate-100">{{ event.description }}</span>
              </div>
              <span class="text-slate-400">
                {{ new Date(event.created_at).toLocaleTimeString() }}
              </span>
            </div>
            <div v-if="events.length === 0" class="p-6 text-center text-slate-500">
              Nenhum evento recente
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
