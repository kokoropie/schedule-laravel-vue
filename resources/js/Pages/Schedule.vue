<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { ref, watch } from 'vue';
import DangerButton from '@/Components/DangerButton.vue';

const { schedule_selected } = defineProps({
    schedules: {
        type: Object,
        required: true
    },
    schedule_selected: {
        type: Object,
        required: true
    },
    schedule_details: {
        type: Object,
        required: true
    },
    subjects: {
        type: Object,
        required: true
    },
    numOfClassPeriodsPerDay: {
        type: Number,
        required: true
    },
    dateOfWeek: {
        type: Object,
        default: {
            1: "Sunday",
            2: "Monday",
            3: "Tuesday",
            4: "Wednesday",
            5: "Thursday",
            6: "Friday",
            7: "Saturday",
        }
    },
    timeOfEachClassPeriod: {
        type: Object,
        required: true
    },
    sort: {
        type: String,
        default: "DESC"
    },
    sort_by: {
        type: String,
        default: "schedule_id"
    },
    limit: {
        type: Number,
        default: 20
    },
    page: {
        type: Number,
        default: 1
    }
});

const paddingNumber = (num = 0, length = 1) => {
    if (`${num}`.length < length) {
        num = "0".repeat(length - `${num}`.length) + `${num}`;
    }
    return `${num}`;
};

const timeOfEachClassPeriodSFN = ref({});
const timeOfEachClassPeriodEFN = ref({});
const formScheduleNew = useForm({
    name: '',
    numOfClassPeriodsPerDay: 1,
    timeOfEachClassPeriod: null
});
const isShowModalScheduleNew = ref(false);

const showModalScheduleNew = (schedule = null) => {
    formScheduleNew.reset();
    timeOfEachClassPeriodSFN.value = {};
    timeOfEachClassPeriodEFN.value = {};
    if (schedule) {
        formScheduleNew.name = schedule.name;
        formScheduleNew.numOfClassPeriodsPerDay = schedule.numOfClassPeriodsPerDay;
        for (let index in schedule.timeOfEachClassPeriod) {
            timeOfEachClassPeriodSFN.value[index] = schedule.timeOfEachClassPeriod[index].start
            timeOfEachClassPeriodEFN.value[index] = schedule.timeOfEachClassPeriod[index].end
        }
    }
    isShowModalScheduleNew.value = true;
}

const closeModalScheduleNew = () => {
    isShowModalScheduleNew.value = false;
    formScheduleNew.reset();
}

const submitScheduleNew = () => {
    let timeOfEachClassPeriodFN = {};
    for (let i = 1; i <= formScheduleNew.numOfClassPeriodsPerDay; i++) {
        timeOfEachClassPeriodFN[i] = {
            start: timeOfEachClassPeriodSFN.value[i],
            end: timeOfEachClassPeriodEFN.value[i]
        }
    }
    formScheduleNew.timeOfEachClassPeriod = timeOfEachClassPeriodFN;
    formScheduleNew.post(route('schedule.store'), {
        onSuccess: closeModalScheduleNew,
    });
};

const timeOfEachClassPeriodSFE = ref({});
const timeOfEachClassPeriodEFE = ref({});
const formScheduleEdit = useForm({
    name: '',
    numOfClassPeriodsPerDay: 1,
    timeOfEachClassPeriod: null
});
const isShowModalScheduleEdit = ref(false);

const showModalScheduleEdit = () => {
    timeOfEachClassPeriodSFE.value = {};
    timeOfEachClassPeriodEFE.value = {};
    formScheduleEdit.name = schedule_selected.name;
    formScheduleEdit.numOfClassPeriodsPerDay = schedule_selected.numOfClassPeriodsPerDay;
    for (let index in schedule_selected.timeOfEachClassPeriod) {
        timeOfEachClassPeriodSFE.value[index] = schedule_selected.timeOfEachClassPeriod[index].start
        timeOfEachClassPeriodEFE.value[index] = schedule_selected.timeOfEachClassPeriod[index].end
    }
    isShowModalScheduleEdit.value = true;
}

const closeModalScheduleEdit = () => {
    isShowModalScheduleEdit.value = false;
    formScheduleEdit.reset();
}

const submitScheduleEdit = () => {
    let timeOfEachClassPeriodFE = {};
    for (let i = 1; i <= formScheduleEdit.numOfClassPeriodsPerDay; i++) {
        timeOfEachClassPeriodFE[i] = {
            start: timeOfEachClassPeriodSFE.value[i],
            end: timeOfEachClassPeriodEFE.value[i]
        }
    }
    formScheduleEdit.timeOfEachClassPeriod = timeOfEachClassPeriodFE;
    formScheduleEdit.patch(route('schedule.update', schedule_selected), {
        onSuccess: closeModalScheduleEdit,
    });
};

const isShowModalScheduleDelete = ref(false);

const showModalScheduleDelete = () => {
    isShowModalScheduleDelete.value = true;
};

const closeModalScheduleDelete = () => {
    isShowModalScheduleDelete.value = false;
};

const submitScheduleDelete = () => {
    router.delete(route('schedule.destroy', schedule_selected), {
        onSuccess: closeModalScheduleDelete,
    });
};

const formNew = useForm({
    subject_id: '',
    start: 1,
    end: 1,
    from: '',
    to: '',
    type: 'OFFLINE',
    dateOfWeek: 2
});
const isShowModalNew = ref(false);

watch(
    () => formNew.start,
    async (newStart, oldStart) => {
        if (newStart >= formNew.end) formNew.end = newStart
    }
)

const showModalNew = (schedule = null) => {
    formNew.reset();
    if (schedule) {
        formNew.subject_id = schedule.subject_id;
        formNew.start = schedule.start;
        formNew.end = schedule.end;
        formNew.from = schedule.from;
        formNew.to = schedule.to;
        formNew.type = schedule.type;
        formNew.dateOfWeek = schedule.dateOfWeek;
    }
    isShowModalNew.value = true;
};

const closeModalNew = () => {
    isShowModalNew.value = false;
    formNew.reset();
};

const submitNew = () => {
    formNew.post(route('schedule.detail.store', [schedule_selected]), {
        onSuccess: closeModalNew,
    });
};

const formEdit = useForm({
    subject_id: '',
    start: 1,
    end: 1,
    from: '',
    to: '',
    type: 'OFFLINE',
    dateOfWeek: 2
});
const isShowModalEdit = ref(false);
const editSchedule = ref(null);

watch(
    () => formEdit.start,
    async (newStart, oldStart) => {
        if (newStart >= formEdit.end) formEdit.end = newStart
    }
)

const showModalEdit = (schedule) => {
    editSchedule.value = schedule;
    formEdit.subject_id = schedule.subject_id;
    formEdit.start = schedule.start;
    formEdit.end = schedule.end;
    formEdit.from = schedule.from;
    formEdit.to = schedule.to;
    formEdit.type = schedule.type;
    formEdit.dateOfWeek = schedule.dateOfWeek;
    isShowModalEdit.value = true;
};

const closeModalEdit = () => {
    isShowModalEdit.value = false;
    formEdit.reset();
    editSchedule.value = null;
};

const submitEdit = () => {
    formEdit.patch(route('schedule.detail.update', [schedule_selected, editSchedule.value]), {
        onSuccess: closeModalEdit,
    });
};

const isShowModalDelete = ref(false);
const deleteSchedule = ref(null);

const showModalDelete = (schedule) => {
    deleteSchedule.value = schedule;
    isShowModalDelete.value = true;
};

const closeModalDelete = () => {
    isShowModalDelete.value = false;
    deleteSchedule.value = null;
};

const submitDelete = () => {
    router.delete(route('schedule.detail.destroy', [schedule_selected, deleteSchedule.value]), {
        onSuccess: closeModalDelete,
    });
};

</script>
<template>
    <Head title="Schedules" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight flex items-center"
                v-if="schedules.length > 0">
                <span class="mr-auto">Schedules <span v-if="schedule_selected"> / {{ schedule_selected.name }}</span></span>
                <Dropdown align="right" width="48">
                    <template #trigger>
                        <span class="inline-flex rounded-md">
                            <PrimaryButton>
                                Schedules

                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </PrimaryButton>
                        </span>
                    </template>

                    <template #content>
                        <button
                            class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 dark:text-gray-50 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 transition duration-150 ease-in-out"
                            @click="showModalScheduleNew()">New Schedule</button>
                        <DropdownLink v-for="schedule in schedules" :href="route('schedule.show', schedule)"
                            preserve-scroll v-bind:key="schedule.schedule_id"> {{ schedule.name }}
                        </DropdownLink>
                    </template>
                </Dropdown>
                <Dropdown align="right" width="48" class="ml-2">
                    <template #trigger>
                        <span class="inline-flex rounded-md">
                            <PrimaryButton>
                                Action

                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </PrimaryButton>
                        </span>
                    </template>

                    <template #content>
                        <button
                            class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 dark:text-gray-50 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 transition duration-150 ease-in-out"
                            @click="showModalScheduleNew(schedule_selected)">Copy</button>
                        <button
                            class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 dark:text-gray-50 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 transition duration-150 ease-in-out"
                            @click="showModalScheduleEdit()">Edit</button>
                        <button
                            class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 dark:text-gray-50 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 transition duration-150 ease-in-out"
                            @click="showModalScheduleDelete()">Delete</button>
                    </template>
                </Dropdown>
                <PrimaryButton @click="showModalNew()" class="ml-2">New</PrimaryButton>
            </h2>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight flex items-center" v-else>
                <span class="mr-auto">Schedules</span>
                <PrimaryButton @click="showModalScheduleNew()" class="ml-2">New Schedule</PrimaryButton>
            </h2>
        </template>
        <div class="py-12" v-if="schedules.length > 0">
            <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="overflow-x-auto bg-white">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-700 text-white">
                                <th class="px-3 py-3 whitespace-nowrap w-auto">
                                    <Link :href="route('schedule.show', {
                                        sort: sort_by.toLowerCase() == 'schedule_detail_id' ? (sort.toUpperCase() == 'DESC' ? 'ASC' : 'DESC') : 'DESC',
                                        schedule: schedule_selected
                                    })" preserve-scroll class="flex items-center space-x-1 justify-center"
                                        :only="['schedule_details', 'sort', 'sort_by', 'limit', 'page', 'total_page']">
                                    <span>#</span>
                                    <svg v-if="sort.toUpperCase() == 'ASC' && sort_by.toLowerCase() == 'schedule_detail_id'"
                                        xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="m7 14l5-5l5 5z"></path>
                                    </svg>
                                    <svg v-if="sort.toUpperCase() == 'DESC' && sort_by.toLowerCase() == 'schedule_detail_id'"
                                        xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="m7 10l5 5l5-5z"></path>
                                    </svg>
                                    </Link>
                                </th>
                                <th class="px-3 md:px-6 py-3 whitespace-nowrap text-left">
                                    <Link :href="route('schedule.show', {
                                        sort: sort_by.toLowerCase() == 'subject_id' ? (sort.toUpperCase() == 'DESC' ? 'ASC' : 'DESC') : 'ASC',
                                        sort_by: 'subject_id',
                                        schedule: schedule_selected
                                    })" preserve-scroll class="flex items-center space-x-1"
                                        :only="['schedule_details', 'sort', 'sort_by', 'limit', 'page', 'total_page']">
                                    <span>Subject</span>
                                    <svg v-if="sort.toUpperCase() == 'ASC' && sort_by.toLowerCase() == 'subject_id'"
                                        xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="m7 14l5-5l5 5z"></path>
                                    </svg>
                                    <svg v-if="sort.toUpperCase() == 'DESC' && sort_by.toLowerCase() == 'subject_id'"
                                        xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="m7 10l5 5l5-5z"></path>
                                    </svg>
                                    </Link>
                                </th>
                                <th class="px-3 md:px-6 py-3 whitespace-nowrap text-right w-auto">
                                    <Link :href="route('schedule.show', {
                                        sort: sort_by.toLowerCase() == 'start' ? (sort.toUpperCase() == 'DESC' ? 'ASC' : 'DESC') : 'ASC',
                                        sort_by: 'start',
                                        schedule: schedule_selected
                                    })" preserve-scroll class="flex items-center space-x-1 justify-end"
                                        :only="['schedule_details', 'sort', 'sort_by', 'limit', 'page', 'total_page']">
                                    <svg v-if="sort.toUpperCase() == 'ASC' && sort_by.toLowerCase() == 'start'"
                                        xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="m7 14l5-5l5 5z"></path>
                                    </svg>
                                    <svg v-if="sort.toUpperCase() == 'DESC' && sort_by.toLowerCase() == 'start'"
                                        xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="m7 10l5 5l5-5z"></path>
                                    </svg>
                                    <span>Start</span>
                                    </Link>
                                </th>
                                <th class="px-3 md:px-6 py-3 whitespace-nowrap text-right w-auto">
                                    <Link :href="route('schedule.show', {
                                        sort: sort_by.toLowerCase() == 'end' ? (sort.toUpperCase() == 'DESC' ? 'ASC' : 'DESC') : 'ASC',
                                        sort_by: 'end',
                                        schedule: schedule_selected
                                    })" preserve-scroll class="flex items-center space-x-1 justify-end"
                                        :only="['schedule_details', 'sort', 'sort_by', 'limit', 'page', 'total_page']">
                                    <svg v-if="sort.toUpperCase() == 'ASC' && sort_by.toLowerCase() == 'end'"
                                        xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="m7 14l5-5l5 5z"></path>
                                    </svg>
                                    <svg v-if="sort.toUpperCase() == 'DESC' && sort_by.toLowerCase() == 'end'"
                                        xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="m7 10l5 5l5-5z"></path>
                                    </svg>
                                    <span>End</span>
                                    </Link>
                                </th>
                                <th class="px-3 md:px-6 py-3 whitespace-nowrap text-right w-auto">
                                    <Link :href="route('schedule.show', {
                                        sort: sort_by.toLowerCase() == 'from' ? (sort.toUpperCase() == 'DESC' ? 'ASC' : 'DESC') : 'ASC',
                                        sort_by: 'from',
                                        schedule: schedule_selected
                                    })" preserve-scroll class="flex items-center space-x-1 justify-end"
                                        :only="['schedule_details', 'sort', 'sort_by', 'limit', 'page', 'total_page']">
                                    <svg v-if="sort.toUpperCase() == 'ASC' && sort_by.toLowerCase() == 'from'"
                                        xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="m7 14l5-5l5 5z"></path>
                                    </svg>
                                    <svg v-if="sort.toUpperCase() == 'DESC' && sort_by.toLowerCase() == 'from'"
                                        xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="m7 10l5 5l5-5z"></path>
                                    </svg>
                                    <span>From</span>
                                    </Link>
                                </th>
                                <th class="px-3 md:px-6 py-3 whitespace-nowrap text-right w-auto">
                                    <Link :href="route('schedule.show', {
                                        sort: sort_by.toLowerCase() == 'to' ? (sort.toUpperCase() == 'DESC' ? 'ASC' : 'DESC') : 'ASC',
                                        sort_by: 'to',
                                        schedule: schedule_selected
                                    })" preserve-scroll class="flex items-center space-x-1 justify-end"
                                        :only="['schedule_details', 'sort', 'sort_by', 'limit', 'page', 'total_page']">
                                    <svg v-if="sort.toUpperCase() == 'ASC' && sort_by.toLowerCase() == 'to'"
                                        xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="m7 14l5-5l5 5z"></path>
                                    </svg>
                                    <svg v-if="sort.toUpperCase() == 'DESC' && sort_by.toLowerCase() == 'to'"
                                        xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="m7 10l5 5l5-5z"></path>
                                    </svg>
                                    <span>To</span>
                                    </Link>
                                </th>
                                <th class="px-3 md:px-6 py-3 whitespace-nowrap w-auto">
                                    <Link :href="route('schedule.show', {
                                        sort: sort_by.toLowerCase() == 'type' ? (sort.toUpperCase() == 'DESC' ? 'ASC' : 'DESC') : 'ASC',
                                        sort_by: 'type',
                                        schedule: schedule_selected
                                    })" preserve-scroll class="flex items-center space-x-1 justify-center"
                                        :only="['schedule_details', 'sort', 'sort_by', 'limit', 'page', 'total_page']">
                                    <span>Type</span>
                                    <svg v-if="sort.toUpperCase() == 'ASC' && sort_by.toLowerCase() == 'type'"
                                        xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="m7 14l5-5l5 5z"></path>
                                    </svg>
                                    <svg v-if="sort.toUpperCase() == 'DESC' && sort_by.toLowerCase() == 'type'"
                                        xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="m7 10l5 5l5-5z"></path>
                                    </svg>
                                    </Link>
                                </th>
                                <th class="px-3 md:px-6 py-3 whitespace-nowrap text-left w-auto">
                                    <Link :href="route('schedule.show', {
                                        sort: sort_by.toLowerCase() == 'dateofweek' ? (sort.toUpperCase() == 'DESC' ? 'ASC' : 'DESC') : 'ASC',
                                        sort_by: 'dateOfWeek',
                                        schedule: schedule_selected
                                    })" preserve-scroll class="flex items-center space-x-1"
                                        :only="['schedule_details', 'sort', 'sort_by', 'limit', 'page', 'total_page']">
                                    <span>Date</span>
                                    <svg v-if="sort.toUpperCase() == 'ASC' && sort_by.toLowerCase() == 'dateofweek'"
                                        xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="m7 14l5-5l5 5z"></path>
                                    </svg>
                                    <svg v-if="sort.toUpperCase() == 'DESC' && sort_by.toLowerCase() == 'dateofweek'"
                                        xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="m7 10l5 5l5-5z"></path>
                                    </svg>
                                    </Link>
                                </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="schedule in schedule_details"
                                class="even:bg-gray-300 hover:bg-gray-500 hover:text-white" :key="schedule.schedule_id">
                                <td class="px-3 py-3 whitespace-nowrap text-center w-auto">{{ schedule.schedule_detail_id }}
                                </td>
                                <td class="px-3 md:px-6 py-3 whitespace-nowrap w-full">{{ schedule.subject_id }} - {{
                                    schedule.subject.name }}</td>
                                <td class="px-3 md:px-6 py-3 whitespace-nowrap text-right w-auto">{{
                                    timeOfEachClassPeriod[schedule.start].start }}</td>
                                <td class="px-3 md:px-6 py-3 whitespace-nowrap text-right w-auto">{{
                                    timeOfEachClassPeriod[schedule.end].end }}</td>
                                <td class="px-3 md:px-6 py-3 whitespace-nowrap text-right w-auto">{{ schedule.from }}</td>
                                <td class="px-3 md:px-6 py-3 whitespace-nowrap text-right w-auto">{{ schedule.to }}</td>
                                <td class="px-3 md:px-6 py-3 whitespace-nowrap text-center w-auto">{{ schedule.type }}</td>
                                <td class="px-3 md:px-6 py-3 whitespace-nowrap text-left w-auto">{{
                                    dateOfWeek[schedule.dateOfWeek] }}</td>
                                <td class="px-3 md:px-6 py-1 whitespace-nowrap text-center space-x-2">
                                    <SecondaryButton type="button" @click="showModalNew(schedule)">Copy</SecondaryButton>
                                    <PrimaryButton type="button" @click="showModalEdit(schedule)">Edit</PrimaryButton>
                                    <DangerButton type="button" @click="showModalDelete(schedule)">Delete</DangerButton>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <Modal :show="isShowModalScheduleNew" @close="closeModalScheduleNew">
            <div class="flex items-start justify-between p-4 border-b rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">
                    New schedule
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    @click="closeModalScheduleNew">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-6">
                <form @submit.prevent="submitScheduleNew" id="form-schedule-new" class="space-y-6">
                    <div>
                        <InputLabel for="name" value="Name" />

                        <TextInput id="name" type="text" class="mt-1 block w-full" v-model="formScheduleNew.name" required
                            placeholder="Name" />
                        <InputError class="mt-2" :message="formScheduleNew.errors.name" />
                    </div>
                    <div>
                        <InputLabel for="numOfClassPeriodsPerDay" value="Num of class periods per day" />

                        <TextInput id="numOfClassPeriodsPerDay" type="number" class="mt-1 block w-full"
                            v-model="formScheduleNew.numOfClassPeriodsPerDay" required min="1" step="1" placeholder="1" />
                        <InputError class="mt-2" :message="formScheduleNew.errors.numOfClassPeriodsPerDay" />
                    </div>
                    <div v-for="i in formScheduleNew.numOfClassPeriodsPerDay * 1" :key="i">
                        <InputLabel><b>Period {{ i }}</b></InputLabel>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <InputLabel :for="`start-${i}`" value="Start" />

                                <TextInput :id="`start-${i}`" type="time" class="mt-1 block w-full"
                                    v-model="timeOfEachClassPeriodSFN[i]" required />
                                <InputError class="mt-2"
                                    :message="formScheduleNew.errors[`timeOfEachClassPeriod.${i}.start`]" />
                            </div>
                            <div>
                                <InputLabel :for="`end-${i}`" value="End" />

                                <TextInput :id="`end-${i}`" type="time" class="mt-1 block w-full"
                                    v-model="timeOfEachClassPeriodEFN[i]" required />
                                <InputError class="mt-2"
                                    :message="formScheduleNew.errors[`timeOfEachClassPeriod.${i}.end`]" />
                            </div>
                        </div>
                        <InputError class="mt-2" :message="formScheduleNew.errors[`timeOfEachClassPeriod.${i}`]" />
                    </div>
                </form>
            </div>
            <div class="flex justify-end items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
                <SecondaryButton @click="closeModalScheduleNew" type="button">Close</SecondaryButton>
                <PrimaryButton type="submit" form="form-schedule-new">Add</PrimaryButton>
            </div>
        </Modal>

        <Modal :show="isShowModalScheduleEdit" @close="closeModalScheduleEdit">
            <div class="flex items-start justify-between p-4 border-b rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">
                    Edit schedule
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    @click="closeModalScheduleEdit">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-6">
                <form @submit.prevent="submitScheduleEdit" id="form-schedule-edit" class="space-y-6">
                    <div>
                        <InputLabel for="name" value="Name" />

                        <TextInput id="name" type="text" class="mt-1 block w-full" v-model="formScheduleEdit.name" required
                            placeholder="Name" />
                        <InputError class="mt-2" :message="formScheduleEdit.errors.name" />
                    </div>
                    <div>
                        <InputLabel for="numOfClassPeriodsPerDay" value="Num of class periods per day" />

                        <TextInput id="numOfClassPeriodsPerDay" type="number" class="mt-1 block w-full"
                            v-model="formScheduleEdit.numOfClassPeriodsPerDay" required min="1" step="1" placeholder="1" />
                        <InputError class="mt-2" :message="formScheduleEdit.errors.numOfClassPeriodsPerDay" />
                    </div>
                    <div v-for="i in formScheduleEdit.numOfClassPeriodsPerDay * 1" :key="i">
                        <InputLabel><b>Period {{ i }}</b></InputLabel>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <InputLabel :for="`start-${i}`" value="Start" />

                                <TextInput :id="`start-${i}`" type="time" class="mt-1 block w-full"
                                    v-model="timeOfEachClassPeriodSFE[i]" />
                                <InputError class="mt-2"
                                    :message="formScheduleEdit.errors[`timeOfEachClassPeriod.${i}.start`]" />
                            </div>
                            <div>
                                <InputLabel :for="`end-${i}`" value="End" />

                                <TextInput :id="`end-${i}`" type="time" class="mt-1 block w-full"
                                    v-model="timeOfEachClassPeriodEFE[i]" required />
                                <InputError class="mt-2"
                                    :message="formScheduleEdit.errors[`timeOfEachClassPeriod.${i}.end`]" />
                            </div>
                        </div>
                        <InputError class="mt-2" :message="formScheduleEdit.errors[`timeOfEachClassPeriod.${i}`]" />
                    </div>
                </form>
            </div>
            <div class="flex justify-end items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
                <SecondaryButton @click="closeModalScheduleEdit" type="button">Close</SecondaryButton>
                <PrimaryButton type="submit" form="form-schedule-edit">Update</PrimaryButton>
            </div>
        </Modal>

        <Modal :show="isShowModalScheduleDelete" @close="closeModalScheduleDelete">
            <div class="flex items-start justify-between p-4 border-b rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">
                    Delete schedule
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    @click="closeModalDelete">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-6">
                Do you really want to delete <b>"{{ schedule_selected.name }}"</b>?
            </div>
            <div class="flex justify-end items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
                <PrimaryButton @click="closeModalScheduleDelete" type="button">No</PrimaryButton>
                <DangerButton @click="submitScheduleDelete" type="button">Yes</DangerButton>
            </div>
        </Modal>

        <Modal :show="isShowModalNew" @close="closeModalNew">
            <div class="flex items-start justify-between p-4 border-b rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">
                    New schedule
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    @click="closeModalNew">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-6">
                <form @submit.prevent="submitNew" id="form-new" class="space-y-6">
                    <div>
                        <InputLabel for="subject_id" value="Teacher" />

                        <select id="subject_id" v-model="formNew.subject_id" required
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                            <option disabled value="">-- Subject --</option>
                            <option v-for="subject in subjects" :key="subject.subject_id" :value="subject.subject_id">
                                {{ subject.subject_id }} - {{ subject.name }}
                            </option>
                        </select>
                        <InputError class="mt-2" :message="formNew.errors.subject_id" />
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <InputLabel for="start" value="Start" />

                            <select id="start" v-model="formNew.start" required
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                                <option v-for="index in numOfClassPeriodsPerDay*1" :key="index" :value="index">
                                    {{ index }}. {{ timeOfEachClassPeriod[index].start }} - {{ timeOfEachClassPeriod[index].end }}
                                </option>
                            </select>
                            <InputError class="mt-2" :message="formNew.errors.start" />
                        </div>
                        <div>
                            <InputLabel for="end" value="End" />

                            <select id="end" v-model="formNew.end" required
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                                <option v-for="index in (numOfClassPeriodsPerDay - formNew.start + 1)*1" :key="index + formNew.start - 1" :value="index + formNew.start - 1">
                                    {{ index + formNew.start - 1 }}. {{ timeOfEachClassPeriod[index + formNew.start - 1].start }} - {{ timeOfEachClassPeriod[index + formNew.start - 1].end }}
                                </option>
                            </select>
                            <InputError class="mt-2" :message="formNew.errors.end" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <InputLabel for="from" value="From" />
                            <TextInput id="from" type="date" class="mt-1 block w-full" v-model="formNew.from" required />
                            <InputError class="mt-2" :message="formNew.errors.from" />
                        </div>
                        <div>
                            <InputLabel for="to" value="To" />

                            <TextInput id="to" type="date" class="mt-1 block w-full" v-model="formNew.to" required />
                            <InputError class="mt-2" :message="formNew.errors.to" />
                        </div>
                    </div>
                    <div>
                        <InputLabel for="type" value="Type" />

                        <div class="mt-1 w-full grid grid-cols-2 gap-2">
                            <div class="flex items-center space-x-2">
                                <input id="type_offline" type="radio" class="rounded-full" v-model="formNew.type"
                                    value="OFFLINE" />
                                <InputLabel for="type_offline" value="OFFLINE" />
                            </div>

                            <div class="flex items-center space-x-2">
                                <input id="type_online" type="radio" class="rounded-full" v-model="formNew.type"
                                    value="ONLINE" />
                                <InputLabel for="type_online" value="ONLINE" />
                            </div>
                        </div>

                        <InputError class="mt-2" :message="formNew.errors.type" />
                    </div>
                    <div>
                        <InputLabel for="dateOfWeek" value="Date" />

                        <select id="dateOfWeek" v-model="formNew.dateOfWeek" required
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                            <option v-for="(dateName, dateValue) in dateOfWeek" :key="dateValue" :value="dateValue">{{
                                dateName }}</option>
                        </select>
                        <InputError class="mt-2" :message="formNew.errors.dateOfWeek" />
                    </div>
                </form>
            </div>
            <div class="flex justify-end items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
                <SecondaryButton @click="closeModalNew" type="button">Close</SecondaryButton>
                <PrimaryButton type="submit" form="form-new">Add</PrimaryButton>
            </div>
        </Modal>

        <Modal :show="isShowModalEdit" @close="closeModalEdit">
            <div class="flex items-start justify-between p-4 border-b rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">
                    Edit schedule
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    @click="closeModalEdit">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-6">
                <form @submit.prevent="submitEdit" id="form-edit" class="space-y-6">
                    <div>
                        <InputLabel for="subject_id" value="Teacher" />

                        <select id="subject_id" v-model="formEdit.subject_id" required
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                            <option disabled value="">-- Subject --</option>
                            <option v-for="subject in subjects" :key="subject.subject_id" :value="subject.subject_id">
                                {{ subject.subject_id }} - {{ subject.name }}
                            </option>
                        </select>
                        <InputError class="mt-2" :message="formEdit.errors.subject_id" />
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <InputLabel for="start" value="Start" />

                            <select id="start" v-model="formEdit.start" required
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                                <option v-for="index in numOfClassPeriodsPerDay*1" :key="index" :value="index">
                                    {{ index }}. {{ timeOfEachClassPeriod[index].start }} - {{ timeOfEachClassPeriod[index].end }}
                                </option>
                            </select>
                            <InputError class="mt-2" :message="formEdit.errors.start" />
                        </div>
                        <div>
                            <InputLabel for="end" value="End" />

                            <select id="end" v-model="formEdit.end" required
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                                <option v-for="index in (numOfClassPeriodsPerDay - formEdit.start + 1)*1" :key="index + formEdit.start - 1" :value="index + formEdit.start - 1">
                                    {{ index + formEdit.start - 1 }}. {{ timeOfEachClassPeriod[index + formEdit.start - 1].start }} - {{ timeOfEachClassPeriod[index + formEdit.start - 1].end }}
                                </option>
                            </select>
                            <InputError class="mt-2" :message="formEdit.errors.end" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <InputLabel for="from" value="From" />

                            <TextInput id="from" type="date" class="mt-1 block w-full" v-model="formEdit.from" required />
                            <InputError class="mt-2" :message="formEdit.errors.from" />
                        </div>
                        <div>
                            <InputLabel for="to" value="To" />

                            <TextInput id="to" type="date" class="mt-1 block w-full" v-model="formEdit.to" required />
                            <InputError class="mt-2" :message="formEdit.errors.to" />
                        </div>
                    </div>
                    <div>
                        <InputLabel for="type" value="Type" />

                        <div class="mt-1 w-full grid grid-cols-2 gap-2">
                            <div class="flex items-center space-x-2">
                                <input id="type_offline" type="radio" class="rounded-full" v-model="formEdit.type"
                                    value="OFFLINE" />
                                <InputLabel for="type_offline" value="OFFLINE" />
                            </div>

                            <div class="flex items-center space-x-2">
                                <input id="type_online" type="radio" class="rounded-full" v-model="formEdit.type"
                                    value="ONLINE" />
                                <InputLabel for="type_online" value="ONLINE" />
                            </div>
                        </div>

                        <InputError class="mt-2" :message="formEdit.errors.type" />
                    </div>
                    <div>
                        <InputLabel for="dateOfWeek" value="Date" />

                        <select id="dateOfWeek" v-model="formEdit.dateOfWeek" required
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                            <option v-for="(dateName, dateValue) in dateOfWeek" :key="dateValue" :value="dateValue">{{
                                dateName }}</option>
                        </select>
                        <InputError class="mt-2" :message="formEdit.errors.dateOfWeek" />
                    </div>
                </form>
            </div>
            <div class="flex justify-end items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
                <SecondaryButton @click="closeModalEdit" type="button">Close</SecondaryButton>
                <PrimaryButton type="submit" form="form-edit">Update</PrimaryButton>
            </div>
        </Modal>

        <Modal :show="isShowModalDelete" @close="closeModalDelete">
            <div class="flex items-start justify-between p-4 border-b rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">
                    Delete schedule
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    @click="closeModalDelete">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-6">
                Do you really want to delete <b>"{{ deleteSchedule && deleteSchedule.subject.name }}" ({{ deleteSchedule &&
                    deleteSchedule.from }} - {{ deleteSchedule && deleteSchedule.to }})</b>?
            </div>
            <div class="flex justify-end items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
                <PrimaryButton @click="closeModalDelete" type="button">No</PrimaryButton>
                <DangerButton @click="submitDelete" type="button">Yes</DangerButton>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
