<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
    schedules: {
        type: Object,
        required: true
    },
    date: {
        type: Number,
        required: true
    },
    nameOfDate: {
        type: Array,
        required: true
    },
    maxADay: {
        type: Number,
        required: true
    },
    timeOfEachClassPeriod: {
        type: Object,
        required: true
    },
    day: {
        type: String
    },
    prev_day: {
        type: String
    },
    next_day: {
        type: String
    }
});

const paddingNumber = (num = 0, length = 1) => {
    if (`${num}`.length < length) {
        num = "0".repeat(length - `${num}`.length) + `${num}`;
    }
    return `${num}`;
}

</script>

<template>
    <Head title="Home" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Schedule</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                <div class="flex justify-center items-center space-x-3">
                    <Link :href="route('home', prev_day)"><PrimaryButton>Prev</PrimaryButton></Link>
                    <TextInput :value="day" type="date" @change="event => router.visit(route('home', event.target.value))" />
                    <Link :href="route('home', next_day)"><PrimaryButton>Next</PrimaryButton></Link>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <tr>
                            <th class="px-6 py-3"></th>
                            <th class="px-6 py-3 border border-black whitespace-nowrap group relative bg-white">
                                {{ nameOfDate[date].date }}
                                <div class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 left-1/2 -bottom-2 -translate-x-1/2 translate-y-full before:content-[''] before:absolute before:left-1/2 before:bottom-full before:-translate-x-1/2 before:border-8 before:border-x-transparent before:border-t-transparent before:border-b-gray-900 group-hover:opacity-100 group-hover:visible">
                                        {{ nameOfDate[date].title }}
                                    </div>
                            </th>
                            <template v-for="n in 7" :key="n">
                                <th v-if="n > date + 1" class="px-6 py-3 border border-black whitespace-nowrap group relative bg-white">
                                    {{ nameOfDate[n - 1].date }}
                                    <div class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 left-1/2 -bottom-2 -translate-x-1/2 translate-y-full before:content-[''] before:absolute before:left-1/2 before:bottom-full before:-translate-x-1/2 before:border-8 before:border-x-transparent before:border-t-transparent before:border-b-gray-900 group-hover:opacity-100 group-hover:visible">
                                        {{ nameOfDate[n - 1].title }}
                                    </div>
                                </th>
                            </template>
                            <th v-for="n in date" :key="n" class="px-6 py-3 border border-black whitespace-nowrap group relative bg-white">
                                {{ nameOfDate[n - 1].date }}
                                <div class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 left-1/2 -bottom-2 -translate-x-1/2 translate-y-full before:content-[''] before:absolute before:left-1/2 before:bottom-full before:-translate-x-1/2 before:border-8 before:border-x-transparent before:border-t-transparent before:border-b-gray-900 group-hover:opacity-100 group-hover:visible">
                                    {{ nameOfDate[n - 1].title }}
                                </div>
                            </th>
                        </tr>
                        <tr v-for="n in maxADay" class="bg-white">
                            <th class="px-6 py-3 border border-black whitespace-nowrap group relative">
                                {{ n }}
                                <div class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 -right-2 top-1/2 translate-x-full -translate-y-1/2 before:content-[''] before:absolute before:top-1/2  before:right-full before:-translate-y-1/2 before:border-8 before:border-y-transparent before:border-l-transparent before:border-r-gray-900 group-hover:opacity-100 group-hover:visible">
                                    {{ paddingNumber(timeOfEachClassPeriod[n].start[0], 2) }}:{{ paddingNumber(timeOfEachClassPeriod[n].start[1], 2) }} - {{ paddingNumber(timeOfEachClassPeriod[n].end[0], 2) }}:{{ paddingNumber(timeOfEachClassPeriod[n].end[1], 2) }}
                                </div>
                            </th>
                            <template v-for="m in 7">
                                <td :rowspan="schedules[m - 1][n].rowspan" v-if="!!schedules[m - 1][n]"
                                    class="px-6 py-3 border border-black whitespace-nowrap text-center group relative">
                                    {{ schedules[m - 1][n].label }}
                                    <span v-if="schedules[m - 1][n].onl" class="text-xs">(ONL)</span>
                                    <template v-if="schedules[m - 1][n].title.length > 0">
                                        <div v-if="m == 1" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 -right-2 top-1/2 translate-x-full -translate-y-1/2 before:content-[''] before:absolute before:top-1/2  before:right-full before:-translate-y-1/2 before:border-8 before:border-y-transparent before:border-l-transparent before:border-r-gray-900 group-hover:opacity-100 group-hover:visible">
                                            {{ schedules[m - 1][n].title }}
                                        </div>
                                        <div v-else-if="m == 7" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 -left-2 top-1/2 -translate-x-full -translate-y-1/2 after:content-[''] after:absolute after:top-1/2  after:left-full after:-translate-y-1/2 after:border-8 after:border-y-transparent after:border-r-transparent after:border-l-gray-900 group-hover:opacity-100 group-hover:visible">
                                            {{ schedules[m - 1][n].title }}
                                        </div>
                                        <div v-else class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 left-1/2 -top-2 -translate-x-1/2 -translate-y-full after:content-[''] after:absolute after:left-1/2 after:top-full after:-translate-x-1/2 after:border-8 after:border-x-transparent after:border-b-transparent after:border-t-gray-900 group-hover:opacity-100 group-hover:visible">
                                            {{ schedules[m - 1][n].title }}
                                        </div>
                                    </template>
                                </td>
                            </template>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>