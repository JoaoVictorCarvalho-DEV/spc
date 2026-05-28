<template>
  <div
    v-for="stat in stats"
    :key="stat.name"
    class="bg-slate-900 border border-slate-800 shadow-slate-950/40 rounded-3xl p-6 hover:shadow-emerald-500/10 transition"
  >
    <div class="flex items-center justify-between mb-3">
      <div :class="[stat.iconBg, 'p-2 rounded-lg']">
        <component :is="stat.icon" :size="20" :class="stat.iconColor" />
      </div>
      <span class="text-xs text-slate-500">Hoje</span>
    </div>

    <p class="text-sm text-slate-400 mb-1">{{ stat.name }}</p>

    <p class="text-2xl font-bold text-slate-100">
      <template v-if="stat.format">
        {{ formatCurrency(stat.value) }}
      </template>
      <template v-else>
        {{ stat.value }}
        <span v-if="stat.suffix" class="text-sm font-normal text-slate-400">
          {{ stat.suffix }}
        </span>
      </template>
    </p>
  </div>
</template>

<script>
export default {
  name: 'StatsGrid',

  props: {
    stats: {
      type: Array,
      required: true,
      default: () => []
    }
  },

  methods: {
    formatCurrency(value) {
      return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
      }).format(value);
    }
  }
}
</script>
