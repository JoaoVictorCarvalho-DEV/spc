<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <Head title="SPC" />

    <div class="min-h-screen bg-slate-950 text-slate-100">
        <div class="mx-auto max-w-7xl px-6 py-16 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-[1.2fr_0.8fr] lg:items-center">
                <div class="space-y-10">
                    <div class="max-w-2xl">
                        <p class="inline-flex rounded-full bg-emerald-500/20 px-3 py-1 text-sm font-semibold text-emerald-200">Sistema de Controle de Energia</p>
                        <h1 class="mt-5 text-4xl font-bold tracking-tight text-white sm:text-5xl">Monitore e gerencie seus dispositivos IoT de energia</h1>
                        <p class="mt-6 text-lg leading-8 text-slate-300">Bem-vindo ao SPC, uma plataforma para registrar sensores, visualizar consumo, agendar comandos e acompanhar eventos em tempo real em seu ambiente de energia inteligente.</p>
                    </div>

                    <div class="flex flex-wrap gap-4">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="inline-flex items-center justify-center rounded-full bg-emerald-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-500/20 transition hover:bg-emerald-400"
                        >
                            Ir para Dashboard
                        </Link>

                        <Link
                            v-else
                            :href="route('login')"
                            class="inline-flex items-center justify-center rounded-full bg-slate-100 px-6 py-3 text-sm font-semibold text-slate-950 shadow-lg shadow-slate-950/10 transition hover:bg-slate-200"
                        >
                            Entrar
                        </Link>

                        <Link
                            v-if="canRegister && !$page.props.auth.user"
                            :href="route('register')"
                            class="inline-flex items-center justify-center rounded-full border border-slate-700 bg-transparent px-6 py-3 text-sm font-semibold text-slate-100 transition hover:border-slate-500 hover:text-white"
                        >
                            Criar conta
                        </Link>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="rounded-3xl border border-slate-800 bg-slate-900/80 p-6">
                            <h2 class="text-base font-semibold text-white">Controle de dispositivos</h2>
                            <p class="mt-3 text-sm leading-6 text-slate-400">Cadastre sensores e tomadas, alterne status e visualize consumo de cada dispositivo.</p>
                        </div>

                        <div class="rounded-3xl border border-slate-800 bg-slate-900/80 p-6">
                            <h2 class="text-base font-semibold text-white">Agendamento inteligente</h2>
                            <p class="mt-3 text-sm leading-6 text-slate-400">Programe comandos e desligamentos automáticos para economizar energia e reduzir custos.</p>
                        </div>

                        <div class="rounded-3xl border border-slate-800 bg-slate-900/80 p-6">
                            <h2 class="text-base font-semibold text-white">Eventos e telemetria</h2>
                            <p class="mt-3 text-sm leading-6 text-slate-400">Acompanhe histórico de eventos, alarmes e métricas de consumo em tempo real.</p>
                        </div>

                        <div class="rounded-3xl border border-slate-800 bg-slate-900/80 p-6">
                            <h2 class="text-base font-semibold text-white">Acesso seguro</h2>
                            <p class="mt-3 text-sm leading-6 text-slate-400">Gerencie seu perfil com segurança e mantenha o acesso protegido enquanto controla seus equipamentos.</p>
                        </div>
                    </div>
                </div>

                <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-emerald-500/15 via-slate-900 to-slate-950 p-8 shadow-2xl shadow-slate-950/40">
                    <div class="absolute inset-x-0 top-0 h-48 bg-gradient-to-b from-emerald-400/20 to-transparent"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between gap-6 rounded-3xl border border-slate-800 bg-slate-950/90 p-6">
                            <div>
                                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-emerald-200">Visão rápida</p>
                                <p class="mt-2 text-3xl font-bold text-white">Dashboard SPC</p>
                            </div>
                            <div class="rounded-3xl bg-slate-800/70 px-4 py-3 text-sm font-semibold text-slate-100">Online</div>
                        </div>

                        <div class="mt-8 grid gap-4">
                            <div class="rounded-3xl border border-slate-800 bg-slate-900/90 p-6">
                                <p class="text-sm uppercase tracking-[0.24em] text-slate-500">Dispositivos ativos</p>
                                <p class="mt-4 text-4xl font-semibold text-white">8</p>
                                <p class="mt-2 text-sm text-slate-400">Conecte seus sensores e acompanhe o status em tempo real.</p>
                            </div>

                            <div class="rounded-3xl border border-slate-800 bg-slate-900/90 p-6">
                                <p class="text-sm uppercase tracking-[0.24em] text-slate-500">Consumo estimado</p>
                                <p class="mt-4 text-4xl font-semibold text-white">420 W</p>
                                <p class="mt-2 text-sm text-slate-400">Tenha uma visão clara de energia e custos do seu ambiente.</p>
                            </div>
                        </div>

                        <div class="mt-8 overflow-hidden rounded-3xl bg-slate-950/90 p-6">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-semibold text-white">Eventos recentes</p>
                                <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Agora</span>
                            </div>
                            <div class="mt-6 space-y-4">
                                <div class="rounded-3xl bg-slate-900/80 p-4">
                                    <p class="text-sm font-medium text-white">Tomada Sala</p>
                                    <p class="mt-1 text-sm text-slate-400">Dispositivo ligado automaticamente</p>
                                </div>
                                <div class="rounded-3xl bg-slate-900/80 p-4">
                                    <p class="text-sm font-medium text-white">Tomada TV</p>
                                    <p class="mt-1 text-sm text-slate-400">Consumo alto detectado</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="mt-16 border-t border-slate-800 pt-8 text-center text-sm text-slate-500">
                SPC • Laravel v{{ laravelVersion }} • PHP v{{ phpVersion }}
            </footer>
        </div>
    </div>
</template>
