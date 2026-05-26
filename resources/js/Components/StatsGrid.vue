<template>
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
        <span v-if="stat.suffix" class="text-sm font-normal text-gray-500">
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
