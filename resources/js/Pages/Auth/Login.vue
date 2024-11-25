<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";

import { Head } from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />
        <a
            :href="route('auth.redirect')"
            class="text-md inline-flex w-full items-center justify-center rounded-lg border border-gray-800 px-5 py-2.5 text-center font-medium text-gray-900 hover:bg-gray-900 hover:text-white focus:outline-none focus:ring-4 focus:ring-gray-300 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-800"
        >
            <ApplicationLogo class="me-2 h-6" />
            <span>Login with ID</span>
        </a>
    </GuestLayout>
</template>
