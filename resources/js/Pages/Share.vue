<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link, Head, router, usePage } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted, } from "vue";
import XLSX from "xlsx";

defineProps({
    author: {
        type: Object,
        required: true,
    },
    schedule_share: {
        type: Object,
        required: true,
    },
    schedule_selected: {
        type: Object,
        required: true,
    },
    schedule_details: {
        type: Object,
        required: true,
    },
    nameOfDate: {
        type: Object,
        required: true,
    },
    maxADay: {
        type: Number,
        required: true,
    },
    timeOfEachClassPeriod: {
        type: Object,
        required: true,
    },
    day: {
        type: String,
    },
    prev_day: {
        type: String,
    },
    next_day: {
        type: String,
    },
    today: {
        type: String,
    },
});
const isLoading = ref(false)
const changeDay = (day) => {
    if (!isLoading.value) {
        isLoading.value = true;
        router.visit(
            route(
                'share',
                paramRoute(
                    usePage().props.schedule_share,
                    day,
                    usePage().props.today
                )
            ),
            {
                preserveScroll: true,
                preserveState: true,
                only: [
                    'day',
                    'today',
                    'nameOfDate',
                    'next_day',
                    'prev_day',
                    'schedule_details',
                ],
                onFinish() {
                    isLoading.value = false;
                },
                replace: true
            }
        )
    }
}

const keyUp = (event) => {
    switch (event.code) {
        case "ArrowLeft":
            event.preventDefault();
            if (event.target.tagName != "INPUT")
                changeDay(usePage().props.prev_day);
            break;

        case "ArrowRight":
            event.preventDefault();
            if (event.target.tagName != "INPUT")
                changeDay(usePage().props.next_day);
            break;
    }
}

onMounted(() => {
    window.addEventListener('keyup', keyUp);
})

onUnmounted(() => {
    window.removeEventListener('keyup', keyUp)
})

const paddingNumber = (num = 0, length = 1) => {
    if (`${num}`.length < length) {
        num = "0".repeat(length - `${num}`.length) + `${num}`;
    }
    return `${num}`;
};

const scheduleTable = ref(null);

const htmlTableToExcel = (type) => {
    var data = scheduleTable.value;
    var excelFile = XLSX.utils.table_to_book(data, { sheet: "sheet1" });
    XLSX.write(excelFile, { bookType: type, bookSST: true, type: "base64" });
    XLSX.writeFile(
        excelFile,
        new Date().getFullYear() +
            "" +
            paddingNumber(new Date().getMonth() + 1, 2) +
            "" +
            paddingNumber(new Date().getDate(), 2) +
            "" +
            paddingNumber(new Date().getHours(), 2) +
            "" +
            paddingNumber(new Date().getMinutes(), 2) +
            "" +
            paddingNumber(new Date().getSeconds(), 2) +
            "." +
            type
    );
};

const paramRoute = (schedule_share, day = "", today = "") => {
    const param = {};
    param.schedule_share = schedule_share;
    if (day != today) {
        param.day = day;
    }
    return param;
};
</script>

<template>
    <Head :title="`${schedule_selected.name} - ${author.name}`" />
    <div>
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <nav class="bg-white border-b border-gray-100 dark:bg-black dark:border-gray-900">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('home')">
                                    <ApplicationLogo
                                        class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200"
                                    />
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white dark:bg-black dark:text-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight flex items-center flex-wrap">
                        <span class="mr-auto">{{ schedule_selected.name }}</span>
                    </h2>
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <div class="pt-6 pb-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-3 sm:space-y-6">
                <div
                    class="flex flex-wrap flex-col xs:flex-row relative justify-center items-center text-center"
                >
                    <PrimaryButton @click="changeDay(today)" class="sm:absolute left-0 mt-2" v-if="today !== day">Today</PrimaryButton>
                    <div class="flex flex-wrap flex-col xs:flex-row justify-center items-center space-y-2 xs:space-y-0 xs:space-x-3 text-center order-1 mt-2 mx-3">
                        <PrimaryButton @click="changeDay(prev_day)">
                            <span class="hidden sm:block">Prev</span>
                            <span class="block sm:hidden">{{ '<' }}</span>
                        </PrimaryButton>
                        <TextInput
                            :model-value="day"
                            type="date"
                            @change="event => changeDay(event.target.value)"
                        />
                        <PrimaryButton @click="changeDay(next_day)">
                            <span class="hidden sm:block">Next</span>
                            <span class="block sm:hidden">{{ '>' }}</span>
                        </PrimaryButton>
                    </div>
                    <PrimaryButton
                        class="sm:absolute right-0 mt-2 xs:ml-3"
                        @click="htmlTableToExcel('xlsx')"
                        >Export
                    </PrimaryButton>
                </div>
                <div class="overflow-x-auto overflow-y-hidden">
                    <table ref="scheduleTable" class="w-full mb-2">
                        <tr>
                            <th class="px-6 py-1"></th>
                            <template v-for="n in 7" :key="n">
                                <th
                                    class="px-6 py-1 border border-black dark:border-white whitespace-nowrap"
                                    :class="{
                                        'bg-white dark:bg-black dark:text-white':
                                            nameOfDate[n - 1].date != today,
                                        'bg-green-100 dark:bg-green-700 dark:text-white':
                                            nameOfDate[n - 1].date == today,
                                    }"
                                >
                                    <div class="flex flex-col">
                                        {{ nameOfDate[n - 1].title }}
                                        <small>{{
                                            nameOfDate[n - 1].date
                                        }}</small>
                                    </div>
                                </th>
                            </template>
                        </tr>
                        <tr
                            v-for="n in maxADay*1"
                            class="bg-white dark:bg-black dark:text-white"
                            :key="n"
                        >
                            <th
                                class="px-6 py-3 border border-black dark:border-white whitespace-nowrap group relative"
                            >
                                {{ n }}
                                <div
                                    class="absolute z-10 invisible hidden px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 -right-2 top-1/2 translate-x-full -translate-y-1/2 before:content-[''] before:absolute before:top-1/2 before:right-full before:-translate-y-1/2 before:border-8 before:border-y-transparent before:border-l-transparent before:border-r-gray-900 group-hover:opacity-100 group-hover:visible group-hover:inline-block"
                                >
                                    {{ timeOfEachClassPeriod[n].start }} -
                                    {{ timeOfEachClassPeriod[n].end }}
                                </div>
                            </th>
                            <template v-for="m in 7">
                                <td
                                    :rowspan="
                                        schedule_details[m - 1][n].rowspan
                                    "
                                    v-if="!!schedule_details[m - 1][n]"
                                    class="px-6 py-3 border border-black dark:border-white whitespace-nowrap text-center group relative"
                                    :style="schedule_details[m - 1][n].style"
                                    :key="m"
                                >
                                    <div
                                        class="flex flex-nowrap items-center justify-center space-x-1"
                                    >
                                        <span>{{
                                            schedule_details[m - 1][n].label
                                        }}</span>
                                        <span
                                            v-if="
                                                schedule_details[m - 1][n].onl
                                            "
                                            class="text-xs"
                                            >(ONL)</span
                                        >
                                        <span
                                            v-if="
                                                schedule_details[m - 1][n]
                                                    .is_makeUp_class
                                            "
                                            class="text-xs"
                                            >(make-up)</span
                                        >
                                    </div>
                                    <template
                                        v-if="
                                            schedule_details[m - 1][n].title
                                                .length > 0
                                        "
                                    >
                                        <div
                                            class="absolute z-10 invisible hidden px-3 py-2 text-xs font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 group-hover:opacity-100 group-hover:visible group-hover:inline-block"
                                            :class="{
                                                'left-1/2 -bottom-2 -translate-x-1/2 translate-y-full before:content-[\'\'] before:absolute before:left-1/2 before:bottom-full before:-translate-x-1/2 before:border-8 before:border-x-transparent before:border-t-transparent before:border-b-gray-900':
                                                    m != 1 &&
                                                    m != 7 &&
                                                    n == 1 &&
                                                    schedule_details[m - 1][n]
                                                        .rowspan +
                                                        n*1 -
                                                        1 !=
                                                        maxADay, // bottom
                                                '-left-2 top-1/2 -translate-x-full -translate-y-1/2 after:content-[\'\'] after:absolute after:top-1/2  after:left-full after:-translate-y-1/2 after:border-8 after:border-y-transparent after:border-r-transparent after:border-l-gray-900':
                                                    m == 7, // left
                                                '-right-2 top-1/2 translate-x-full -translate-y-1/2 before:content-[\'\'] before:absolute before:top-1/2  before:right-full before:-translate-y-1/2 before:border-8 before:border-y-transparent before:border-l-transparent before:border-r-gray-900':
                                                    m == 1, // right
                                                'left-1/2 -top-2 -translate-x-1/2 -translate-y-full after:content-[\'\'] after:absolute after:left-1/2 after:top-full after:-translate-x-1/2 after:border-8 after:border-x-transparent after:border-b-transparent after:border-t-gray-900':
                                                    m != 1 && m != 7 && n != 1, // top
                                                'left-1/2 bottom-2 -translate-x-1/2 after:content-[\'\'] after:absolute after:left-1/2 after:top-full after:-translate-x-1/2 after:border-8 after:border-x-transparent after:border-b-transparent after:border-t-gray-900':
                                                    m != 1 &&
                                                    m != 7 &&
                                                    n == 1 &&
                                                    schedule_details[m - 1][n]
                                                        .rowspan +
                                                        n*1 -
                                                        1 ==
                                                        maxADay, // top-bottom
                                            }"
                                        >
                                            {{
                                                schedule_details[m - 1][n].title
                                            }}
                                            ({{
                                                timeOfEachClassPeriod[n].start
                                            }}
                                            -
                                            {{
                                                timeOfEachClassPeriod[
                                                    schedule_details[m - 1][n]
                                                        .rowspan +
                                                        n*1 -
                                                        1
                                                ].end
                                            }})
                                            <br />
                                            -
                                            {{
                                                schedule_details[m - 1][n]
                                                    .teacher
                                            }}
                                            -
                                        </div>
                                    </template>
                                </td>
                            </template>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
            </main>
        </div>
    </div>
</template>
