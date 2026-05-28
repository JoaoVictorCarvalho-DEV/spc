<script setup>
import { computed } from "vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
  status: {
    type: String,
  },
});

const form = useForm({});

const submit = () => {
  form.post(route("verification.send"));
};

const verificationLinkSent = computed(() => props.status === "verification-link-sent");
</script>

<template>
  <GuestLayout>
    <Head title="Email Verification" />

    <div class="mb-4 text-sm text-slate-300">
      Obrigado por se inscrever! Antes de começar, você poderia verificar seu endereço de
      e-mail clicando no link que acabamos de enviar para você? Se você não recebeu o
      e-mail, teremos prazer em enviar outro.
    </div>

    <div class="mb-4 text-sm font-medium text-green-600" v-if="verificationLinkSent">
      Um novo link de verificação foi enviado para o endereço de e-mail que você forneceu
      durante o registro.
    </div>

    <form @submit.prevent="submit">
      <div class="mt-4 flex items-center justify-between">
        <PrimaryButton
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Reenviar Email de Verificação
        </PrimaryButton>

        <Link
          :href="route('logout')"
          method="post"
          as="button"
          class="rounded-md text-sm text-slate-300 underline hover:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
          >Log Out</Link
        >
      </div>
    </form>
  </GuestLayout>
</template>
