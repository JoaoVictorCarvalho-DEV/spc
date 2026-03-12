<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'

const stats = [
    { name: "Dispositivos ativos", value: 3 },
    { name: "Consumo atual", value: "420 W" },
    { name: "Eventos hoje", value: 12 },
    { name: "Desligamentos programados", value: 2 },
]



const devices = [
    {
        name: "Tomada Sala",
        status: "Ligado",
        power: "120 W",
    },
    {
        name: "Tomada Servidor",
        status: "Ligado",
        power: "250 W",
    },
    {
        name: "Tomada TV",
        status: "Desligado",
        power: "0 W",
    },
]

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
]
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="py-10">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-8">

                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div
                        v-for="stat in stats"
                        :key="stat.name"
                        class="bg-white shadow rounded-lg p-6"
                    >
                        <p class="text-sm text-gray-500">
                            {{ stat.name }}
                        </p>

                        <p class="text-2xl font-bold text-gray-800">
                            {{ stat.value }}
                        </p>
                    </div>
                </div>


                <!-- Devices -->
                <div class="bg-white shadow rounded-lg">
                    <div class="p-6 border-b">
                        <h3 class="text-lg font-semibold">
                            Dispositivos
                        </h3>
                    </div>

                    <div class="divide-y">
                        <div
                            v-for="device in devices"
                            :key="device.name"
                            class="flex items-center justify-between p-6"
                        >
                            <div>
                                <p class="font-medium text-gray-800">
                                    {{ device.name }}
                                </p>

                                <p class="text-sm text-gray-500">
                                    Consumo: {{ device.power }}
                                </p>
                            </div>

                            <span
                                class="px-3 py-1 text-sm rounded-full"
                                :class="device.status === 'Ligado'
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-gray-200 text-gray-700'"
                            >
                                {{ device.status }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Events -->
                <div class="bg-white shadow rounded-lg">
                    <div class="p-6 border-b">
                        <h3 class="text-lg font-semibold">
                            Eventos recentes
                        </h3>
                    </div>

                    <div class="divide-y">
                        <div
                            v-for="event in events"
                            :key="event.time"
                            class="flex justify-between p-6 text-sm"
                        >
                            <div>
                                <span class="font-medium">
                                    {{ event.device }}
                                </span>
                                —
                                {{ event.event }}
                            </div>

                            <span class="text-gray-500">
                                {{ event.time }}
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
