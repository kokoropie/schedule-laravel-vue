<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, Link, router, useForm, usePage} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Modal from "@/Components/Modal.vue";
import Checkbox from "@/Components/Checkbox.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import {ref, watch} from "vue";
import DangerButton from "@/Components/DangerButton.vue";

defineProps({
	schedules: {
		type: Object,
		required: true,
	},
	schedule_selected: {
		type: Object,
	},
	schedule_details: {
		type: Object,
		required: true,
	},
	subjects: {
		type: Object,
		required: true,
	},
	numOfClassPeriodsPerDay: {
		type: Number,
		required: true,
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
		},
	},
	dateOfWeekShort: {
		type: Object,
		default: {
			1: "Sun",
			2: "Mon",
			3: "Tue",
			4: "Wed",
			5: "Thu",
			6: "Fri",
			7: "Sat",
		},
	},
	timeOfEachClassPeriod: {
		type: Object,
		required: true,
	},
	sort: {
		type: String,
		default: "DESC",
	},
	sort_by: {
		type: String,
		default: "schedule_id",
	},
	page: {
		type: Number,
		default: 1,
	},
	flash: {
		type: Object,
		default: {},
	},
});

const timeOfEachClassPeriodSFN = ref({});
const timeOfEachClassPeriodEFN = ref({});
const formScheduleNew = useForm({
	name: "",
	numOfClassPeriodsPerDay: "1",
	timeOfEachClassPeriod: null,
});
const isShowModalScheduleNew = ref(false);

const showModalScheduleNew = (schedule = null) => {
    formScheduleNew.clearErrors();
	formScheduleNew.reset();
	timeOfEachClassPeriodSFN.value = {};
	timeOfEachClassPeriodEFN.value = {};
	if (schedule) {
		formScheduleNew.name = schedule.name;
		formScheduleNew.numOfClassPeriodsPerDay = schedule.numOfClassPeriodsPerDay;
		for (let index in schedule.timeOfEachClassPeriod) {
			timeOfEachClassPeriodSFN.value[index] = schedule.timeOfEachClassPeriod[index].start;
			timeOfEachClassPeriodEFN.value[index] = schedule.timeOfEachClassPeriod[index].end;
		}
	}
	isShowModalScheduleNew.value = true;
};

const closeModalScheduleNew = () => {
	isShowModalScheduleNew.value = false;
    formScheduleNew.clearErrors();
	formScheduleNew.reset();
};

const submitScheduleNew = () => {
	let timeOfEachClassPeriodFN = {};
	for (let i = 1; i <= formScheduleNew.numOfClassPeriodsPerDay; i++) {
		timeOfEachClassPeriodFN[i] = {
			start: timeOfEachClassPeriodSFN.value[i],
			end: timeOfEachClassPeriodEFN.value[i],
		};
	}
	formScheduleNew.timeOfEachClassPeriod = timeOfEachClassPeriodFN;
	formScheduleNew.post(route("schedule.store"), {
		onSuccess: closeModalScheduleNew,
	});
};

const timeOfEachClassPeriodSFE = ref({});
const timeOfEachClassPeriodEFE = ref({});
const formScheduleEdit = useForm({
	name: "",
	numOfClassPeriodsPerDay: "1",
	timeOfEachClassPeriod: null,
});
const isShowModalScheduleEdit = ref(false);

const showModalScheduleEdit = () => {
    formScheduleEdit.clearErrors();
	formScheduleEdit.reset();
	timeOfEachClassPeriodSFE.value = {};
	timeOfEachClassPeriodEFE.value = {};
	formScheduleEdit.name = usePage().props.schedule_selected.name;
	formScheduleEdit.numOfClassPeriodsPerDay = usePage().props.schedule_selected.numOfClassPeriodsPerDay + "";
	for (let index in usePage().props.schedule_selected.timeOfEachClassPeriod) {
		timeOfEachClassPeriodSFE.value[index] = usePage().props.schedule_selected.timeOfEachClassPeriod[index].start;
		timeOfEachClassPeriodEFE.value[index] = usePage().props.schedule_selected.timeOfEachClassPeriod[index].end;
	}
	isShowModalScheduleEdit.value = true;
};

const closeModalScheduleEdit = () => {
	isShowModalScheduleEdit.value = false;
    formScheduleEdit.clearErrors();
	formScheduleEdit.reset();
};

const submitScheduleEdit = () => {
	let timeOfEachClassPeriodFE = {};
	for (let i = 1; i <= formScheduleEdit.numOfClassPeriodsPerDay; i++) {
		timeOfEachClassPeriodFE[i] = {
			start: timeOfEachClassPeriodSFE.value[i],
			end: timeOfEachClassPeriodEFE.value[i],
		};
	}
	formScheduleEdit.timeOfEachClassPeriod = timeOfEachClassPeriodFE;
	formScheduleEdit.patch(route("schedule.update", usePage().props.schedule_selected), {
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
	router.delete(route("schedule.destroy", usePage().props.schedule_selected), {
		onSuccess: closeModalScheduleDelete,
	});
};

const formDetailNew = useForm({
	subject_id: "",
	start: 1,
	end: 1,
	from: "",
	to: "",
	type: "OFFLINE",
	dateOfWeek: [2],
	is_makeUp_class: false,
});
const isShowModalDetailNew = ref(false);

watch(
	() => formDetailNew.start,
	async (newStart, oldStart) => {
		if (newStart >= formDetailNew.end) formDetailNew.end = newStart;
	},
);

const showModalDetailNew = (detail = null) => {
    formDetailNew.clearErrors();
	formDetailNew.reset();
	if (detail) {
		formDetailNew.subject_id = detail.subject_id;
		formDetailNew.start = detail.start;
		formDetailNew.end = detail.end;
		formDetailNew.from = detail.from;
		formDetailNew.to = detail.to;
		formDetailNew.type = detail.type;
		formDetailNew.dateOfWeek = detail.dateOfWeek;
		formDetailNew.is_makeUp_class = detail.is_makeUp_class;
	}
	isShowModalDetailNew.value = true;
};

const closeModalDetailNew = () => {
	isShowModalDetailNew.value = false;
    formDetailNew.clearErrors();
	formDetailNew.reset();
};

const submitDetailNew = () => {
	formDetailNew.post(route("schedule.detail.store", [usePage().props.schedule_selected]), {
		onSuccess: closeModalDetailNew,
	});
};

const formDetailEdit = useForm({
	subject_id: "",
	start: 1,
	end: 1,
	from: "",
	to: "",
	type: "OFFLINE",
	dateOfWeek: 2,
	is_makeUp_class: false,
});
const isShowModalDetailEdit = ref(false);
const editDetail = ref(null);

watch(
	() => formDetailEdit.start,
	async (newStart, oldStart) => {
		if (newStart >= formDetailEdit.end) formDetailEdit.end = newStart;
	},
);

const showModalDetailEdit = (detail) => {
    formDetailEdit.clearErrors();
    formDetailEdit.reset();
	editDetail.value = detail;
	formDetailEdit.subject_id = detail.subject_id;
	formDetailEdit.start = detail.start;
	formDetailEdit.end = detail.end;
	formDetailEdit.from = detail.from;
	formDetailEdit.to = detail.to;
	formDetailEdit.type = detail.type;
	formDetailEdit.dateOfWeek = detail.dateOfWeek;
	formDetailEdit.is_makeUp_class = detail.is_makeUp_class;
	isShowModalDetailEdit.value = true;
};

const closeModalDetailEdit = () => {
	isShowModalDetailEdit.value = false;
    formDetailEdit.clearErrors();
	formDetailEdit.reset();
	editDetail.value = null;
};

const submitDetailEdit = () => {
	formDetailEdit.patch(route("schedule.detail.update", [usePage().props.schedule_selected, editDetail.value]), {
		onSuccess: closeModalDetailEdit,
	});
};

const isShowModalDetailDelete = ref(false);
const deleteDetail = ref(null);

const showModalDetailDelete = (detail) => {
	deleteDetail.value = detail;
	isShowModalDetailDelete.value = true;
};

const closeModalDetailDelete = () => {
	isShowModalDetailDelete.value = false;
	deleteDetail.value = null;
};

const submitDetailDelete = () => {
	router.delete(route("schedule.detail.destroy", [usePage().props.schedule_selected, deleteDetail.value]), {
		onSuccess: closeModalDetailDelete,
	});
};
</script>
<template>
	<Head title="Schedules" />
	<AuthenticatedLayout>
		<template #header>
			<h2
				class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight flex flex-wrap flex-col xs:flex-row items-center space-y-1 xs:space-y-0 space-x-0 xs:space-x-2"
				v-if="schedules.length > 0">
				<span class="mr-auto">
					Schedules
					<span v-if="schedule_selected"> / {{ schedule_selected.name }} </span>
				</span>
				<Dropdown align="right">
					<template #trigger>
						<span class="inline-flex rounded-md">
							<PrimaryButton>
								Main Schedules
								<svg
									class="ml-2 -mr-0.5 h-4 w-4"
									xmlns="http://www.w3.org/2000/svg"
									viewBox="0 0 20 20"
									fill="currentColor">
									<path
										fill-rule="evenodd"
										d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
										clip-rule="evenodd" />
								</svg>
							</PrimaryButton>
						</span>
					</template>

					<template #content>
						<button
							class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 dark:text-gray-50 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 transition duration-150 ease-in-out"
							@click="showModalScheduleNew()">
							New Main Schedule
						</button>
						<DropdownLink
							v-for="schedule in schedules"
							:href="route('schedule.show', schedule)"
                            replace
							v-bind:key="schedule.schedule_id"
							:only="['schedule_details', 'schedule_selected']"
							>{{ schedule.name }}</DropdownLink
						>
					</template>
				</Dropdown>
				<Dropdown align="right">
					<template #trigger>
						<span class="inline-flex rounded-md">
							<PrimaryButton>
								Action
								<svg
									class="ml-2 -mr-0.5 h-4 w-4"
									xmlns="http://www.w3.org/2000/svg"
									viewBox="0 0 20 20"
									fill="currentColor">
									<path
										fill-rule="evenodd"
										d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
										clip-rule="evenodd" />
								</svg>
							</PrimaryButton>
						</span>
					</template>

					<template #content>
						<button
							class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 dark:text-gray-50 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 transition duration-150 ease-in-out"
							@click="showModalScheduleNew(schedule_selected)">
							Copy
						</button>
						<button
							class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 dark:text-gray-50 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 transition duration-150 ease-in-out"
							@click="showModalScheduleEdit()">
							Edit
						</button>
						<button
							class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 dark:text-gray-50 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 transition duration-150 ease-in-out"
							@click="showModalScheduleDelete()">
							Delete
						</button>
					</template>
				</Dropdown>
				<PrimaryButton @click="showModalDetailNew()">New</PrimaryButton>
			</h2>
			<h2
				class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight flex items-center"
				v-else>
				<span class="mr-auto">Schedules</span>
				<PrimaryButton @click="showModalScheduleNew()">New Schedule</PrimaryButton>
			</h2>
		</template>
		<div
			class="py-12"
			v-if="schedules.length > 0">
			<div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="flex flex-wrap justify-end items-center space-x-2">
					<Dropdown
						align="right"
						class="mb-2">
						<template #trigger>
							<span class="inline-flex rounded-md">
								<PrimaryButton>
									Limit: {{ schedule_details.per_page }}
									<svg
										class="ml-2 -mr-0.5 h-4 w-4"
										xmlns="http://www.w3.org/2000/svg"
										viewBox="0 0 20 20"
										fill="currentColor">
										<path
											fill-rule="evenodd"
											d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
											clip-rule="evenodd" />
									</svg>
								</PrimaryButton>
							</span>
						</template>

						<template #content>
							<DropdownLink
								v-for="limit in [5, 10, 20, 50]"
								:href="
									route('schedule.show', {
										sort: sort,
										sort_by: sort_by,
										schedule: schedule_selected,
										limit: limit,
									})
								"
                                replace
								preserve-scroll
								:key="limit"
								:only="['schedule_details', 'sort', 'sort_by']"
								>{{ limit }}</DropdownLink
							>
						</template>
					</Dropdown>
					<ul
						class="flex justify-center items-center -space-x-px h-[2.5rem] text-sm mb-2"
						v-if="schedule_details.last_page > 1">
						<template
							v-for="(link, index) in schedule_details.links"
							:key="index">
							<li v-if="link.active || (schedule_details.current_page == 1 && index == 3) || (schedule_details.current_page == schedule_details.last_page && index == schedule_details.links.length - 4) || (!link.active && link.label != '...' && (index == 0 || index == schedule_details.links.length - 1 || link.url == schedule_details.next_page_url || link.url == schedule_details.prev_page_url))">
								<Link
									:href="link.url"
                                    replace
                                    preserve-scroll
									class="flex items-center justify-center px-3 h-[2.5rem] min-w-[2.5rem] leading-tight"
									:class="{
										'text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white': !link.active,
										'text-blue-600 border border-blue-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white': link.active,
										'rounded-l-lg': index == 0,
										'rounded-r-lg': index == schedule_details.links.length - 1,
									}"
									:only="['schedule_details', 'sort', 'sort_by']"
									v-if="!link.active && link.url"
									>{{ index == 0 ? "<" : index == schedule_details.links.length - 1 ? ">" : link.label }}</Link
								>
								<span
									class="flex items-center justify-center px-3 h-[2.5rem] min-w-[2.5rem] leading-tight"
									:class="{
										'text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white': !link.active,
										'text-blue-600 border border-blue-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white': link.active,
										'rounded-l-lg': index == 0,
										'rounded-r-lg': index == schedule_details.links.length - 1,
									}"
									v-if="link.active || !link.url"
									>{{ index == 0 ? "<" : index == schedule_details.links.length - 1 ? ">" : link.label }}</span
								>
							</li>
						</template>
					</ul>
				</div>
				<div class="overflow-x-auto bg-white">
					<table class="w-full">
						<thead>
							<tr class="bg-gray-700 text-white">
								<th class="px-3 py-3 whitespace-nowrap w-auto">
									<Link
										:href="
											route('schedule.show', {
												sort: sort_by.toLowerCase() == 'schedule_detail_id' ? (sort.toUpperCase() == 'DESC' ? 'ASC' : 'DESC') : 'DESC',
												schedule: schedule_selected,
												limit: schedule_details.per_page,
												page: schedule_details.current_page,
											})
										"
                                        replace
                                        preserve-scroll
										class="flex items-center space-x-1 justify-center"
										:only="['schedule_details', 'sort', 'sort_by']">
										<span>#</span>
										<svg
											v-if="sort.toUpperCase() == 'ASC' && sort_by.toLowerCase() == 'schedule_detail_id'"
											xmlns="http://www.w3.org/2000/svg"
											width="1em"
											height="1em"
											viewBox="0 0 24 24">
											<path
												fill="currentColor"
												d="m7 14l5-5l5 5z" />
										</svg>
										<svg
											v-if="sort.toUpperCase() == 'DESC' && sort_by.toLowerCase() == 'schedule_detail_id'"
											xmlns="http://www.w3.org/2000/svg"
											width="1em"
											height="1em"
											viewBox="0 0 24 24">
											<path
												fill="currentColor"
												d="m7 10l5 5l5-5z" />
										</svg>
									</Link>
								</th>
								<th class="px-3 md:px-6 py-3 whitespace-nowrap text-left">
									<Link
										:href="
											route('schedule.show', {
												sort: sort_by.toLowerCase() == 'subject_id' ? (sort.toUpperCase() == 'DESC' ? 'ASC' : 'DESC') : 'ASC',
												sort_by: 'subject_id',
												schedule: schedule_selected,
												limit: schedule_details.per_page,
												page: schedule_details.current_page,
											})
										"
                                        replace
                                        preserve-scroll
										class="flex items-center space-x-1"
										:only="['schedule_details', 'sort', 'sort_by']">
										<span>Subject</span>
										<svg
											v-if="sort.toUpperCase() == 'ASC' && sort_by.toLowerCase() == 'subject_id'"
											xmlns="http://www.w3.org/2000/svg"
											width="1em"
											height="1em"
											viewBox="0 0 24 24">
											<path
												fill="currentColor"
												d="m7 14l5-5l5 5z" />
										</svg>
										<svg
											v-if="sort.toUpperCase() == 'DESC' && sort_by.toLowerCase() == 'subject_id'"
											xmlns="http://www.w3.org/2000/svg"
											width="1em"
											height="1em"
											viewBox="0 0 24 24">
											<path
												fill="currentColor"
												d="m7 10l5 5l5-5z" />
										</svg>
									</Link>
								</th>
								<th class="px-3 md:px-6 py-3 whitespace-nowrap text-right w-auto">
									<Link
										:href="
											route('schedule.show', {
												sort: sort_by.toLowerCase() == 'start' ? (sort.toUpperCase() == 'DESC' ? 'ASC' : 'DESC') : 'ASC',
												sort_by: 'start',
												schedule: schedule_selected,
												limit: schedule_details.per_page,
												page: schedule_details.current_page,
											})
										"
                                        replace
                                        preserve-scroll
										class="flex items-center space-x-1 justify-end"
										:only="['schedule_details', 'sort', 'sort_by']">
										<svg
											v-if="sort.toUpperCase() == 'ASC' && sort_by.toLowerCase() == 'start'"
											xmlns="http://www.w3.org/2000/svg"
											width="1em"
											height="1em"
											viewBox="0 0 24 24">
											<path
												fill="currentColor"
												d="m7 14l5-5l5 5z" />
										</svg>
										<svg
											v-if="sort.toUpperCase() == 'DESC' && sort_by.toLowerCase() == 'start'"
											xmlns="http://www.w3.org/2000/svg"
											width="1em"
											height="1em"
											viewBox="0 0 24 24">
											<path
												fill="currentColor"
												d="m7 10l5 5l5-5z" />
										</svg>
										<span>Start</span>
									</Link>
								</th>
								<th class="px-3 md:px-6 py-3 whitespace-nowrap text-right w-auto">
									<Link
										class="flex items-center space-x-1 justify-end"
                                        replace
                                        preserve-scroll
										:href="
											route('schedule.show', {
												sort: sort_by.toLowerCase() == 'end' ? (sort.toUpperCase() == 'DESC' ? 'ASC' : 'DESC') : 'ASC',
												sort_by: 'end',
												schedule: schedule_selected,
												limit: schedule_details.per_page,
												page: schedule_details.current_page,
											})
										"
										:only="['schedule_details', 'sort', 'sort_by']">
										<svg
											height="1em"
											viewBox="0 0 24 24"
											width="1em"
											xmlns="http://www.w3.org/2000/svg"
											v-if="sort.toUpperCase() == 'ASC' && sort_by.toLowerCase() == 'end'">
											<path
												d="m7 14l5-5l5 5z"
												fill="currentColor" />
										</svg>
										<svg
											height="1em"
											viewBox="0 0 24 24"
											width="1em"
											xmlns="http://www.w3.org/2000/svg"
											v-if="sort.toUpperCase() == 'DESC' && sort_by.toLowerCase() == 'end'">
											<path
												d="m7 10l5 5l5-5z"
												fill="currentColor" />
										</svg>
										<span>End</span>
									</Link>
								</th>
								<th class="px-3 md:px-6 py-3 whitespace-nowrap text-right w-auto">
									<Link
										class="flex items-center space-x-1 justify-end"
                                        replace
                                        preserve-scroll
										:href="
											route('schedule.show', {
												sort: sort_by.toLowerCase() == 'from' ? (sort.toUpperCase() == 'DESC' ? 'ASC' : 'DESC') : 'ASC',
												sort_by: 'from',
												schedule: schedule_selected,
												limit: schedule_details.per_page,
												page: schedule_details.current_page,
											})
										"
										:only="['schedule_details', 'sort', 'sort_by']">
										<svg
											height="1em"
											viewBox="0 0 24 24"
											width="1em"
											xmlns="http://www.w3.org/2000/svg"
											v-if="sort.toUpperCase() == 'ASC' && sort_by.toLowerCase() == 'from'">
											<path
												d="m7 14l5-5l5 5z"
												fill="currentColor" />
										</svg>
										<svg
											height="1em"
											viewBox="0 0 24 24"
											width="1em"
											xmlns="http://www.w3.org/2000/svg"
											v-if="sort.toUpperCase() == 'DESC' && sort_by.toLowerCase() == 'from'">
											<path
												d="m7 10l5 5l5-5z"
												fill="currentColor" />
										</svg>
										<span>From</span>
									</Link>
								</th>
								<th class="px-3 md:px-6 py-3 whitespace-nowrap text-right w-auto">
									<Link
										class="flex items-center space-x-1 justify-end"
                                        replace
                                        preserve-scroll
										:href="
											route('schedule.show', {
												sort: sort_by.toLowerCase() == 'to' ? (sort.toUpperCase() == 'DESC' ? 'ASC' : 'DESC') : 'ASC',
												sort_by: 'to',
												schedule: schedule_selected,
												limit: schedule_details.per_page,
												page: schedule_details.current_page,
											})
										"
										:only="['schedule_details', 'sort', 'sort_by']">
										<svg
											height="1em"
											viewBox="0 0 24 24"
											width="1em"
											xmlns="http://www.w3.org/2000/svg"
											v-if="sort.toUpperCase() == 'ASC' && sort_by.toLowerCase() == 'to'">
											<path
												d="m7 14l5-5l5 5z"
												fill="currentColor" />
										</svg>
										<svg
											height="1em"
											viewBox="0 0 24 24"
											width="1em"
											xmlns="http://www.w3.org/2000/svg"
											v-if="sort.toUpperCase() == 'DESC' && sort_by.toLowerCase() == 'to'">
											<path
												d="m7 10l5 5l5-5z"
												fill="currentColor" />
										</svg>
										<span>To</span>
									</Link>
								</th>
								<th class="px-3 md:px-6 py-3 whitespace-nowrap w-auto">
									<Link
										class="flex items-center space-x-1 justify-center"
                                        replace
                                        preserve-scroll
										:href="
											route('schedule.show', {
												sort: sort_by.toLowerCase() == 'type' ? (sort.toUpperCase() == 'DESC' ? 'ASC' : 'DESC') : 'ASC',
												sort_by: 'type',
												schedule: schedule_selected,
												limit: schedule_details.per_page,
												page: schedule_details.current_page,
											})
										"
										:only="['schedule_details', 'sort', 'sort_by']">
										<span>Type</span>
										<svg
											height="1em"
											viewBox="0 0 24 24"
											width="1em"
											xmlns="http://www.w3.org/2000/svg"
											v-if="sort.toUpperCase() == 'ASC' && sort_by.toLowerCase() == 'type'">
											<path
												d="m7 14l5-5l5 5z"
												fill="currentColor" />
										</svg>
										<svg
											height="1em"
											viewBox="0 0 24 24"
											width="1em"
											xmlns="http://www.w3.org/2000/svg"
											v-if="sort.toUpperCase() == 'DESC' && sort_by.toLowerCase() == 'type'">
											<path
												d="m7 10l5 5l5-5z"
												fill="currentColor" />
										</svg>
									</Link>
								</th>
								<th class="px-3 md:px-6 py-3 whitespace-nowrap text-left w-auto">
                                    <span>Date</span>
								</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<tr
								class="even:bg-gray-300 hover:bg-gray-500 hover:text-white"
								v-for="schedule in schedule_details.data"
								:key="schedule.schedule_id">
								<td class="px-3 py-3 whitespace-nowrap text-center w-auto">
									{{ schedule.schedule_detail_id }}
								</td>
								<td class="px-3 md:px-6 py-3 whitespace-nowrap w-full flex items-center">
									<svg
										class="mr-2"
										height="1.5em"
										viewBox="0 0 24 24"
										width="1.5em"
										xmlns="http://www.w3.org/2000/svg"
										v-if="schedule.is_makeUp_class">
										<path
											d="M10 8v6l4.7 2.9l.8-1.2l-4-2.4V8z"
											fill="currentColor" />
										<path
											d="M17.92 12A6.957 6.957 0 0 1 11 20c-3.9 0-7-3.1-7-7s3.1-7 7-7c.7 0 1.37.1 2 .29V4.23c-.64-.15-1.31-.23-2-.23c-5 0-9 4-9 9s4 9 9 9a8.963 8.963 0 0 0 8.94-10h-2.02z"
											fill="currentColor" />
										<path
											d="M20 5V2h-2v3h-3v2h3v3h2V7h3V5z"
											fill="currentColor" />
									</svg>
									{{ schedule.subject_id }} -
									{{ subjects.find((i) => i.subject_id == schedule.subject_id)?.name }}
								</td>
								<td class="px-3 md:px-6 py-3 whitespace-nowrap text-right w-auto">
									{{ timeOfEachClassPeriod[schedule.start].start }}
								</td>
								<td class="px-3 md:px-6 py-3 whitespace-nowrap text-right w-auto">
									{{ timeOfEachClassPeriod[schedule.end].end }}
								</td>
								<td class="px-3 md:px-6 py-3 whitespace-nowrap text-right w-auto">
									{{ schedule.from }}
								</td>
								<td class="px-3 md:px-6 py-3 whitespace-nowrap text-right w-auto">
									{{ schedule.to }}
								</td>
								<td class="px-3 md:px-6 py-3 whitespace-nowrap text-center w-auto">
									{{ schedule.type }}
								</td>
								<td class="px-3 md:px-6 py-3 whitespace-nowrap text-left w-auto">
									{{ schedule.dateOfWeek.map(v => dateOfWeekShort[v]).join(", ") }}
								</td>
								<td class="px-3 md:px-6 py-1 whitespace-nowrap text-center space-x-2">
									<SecondaryButton
										type="button"
										@click="showModalDetailNew(schedule)"
										>Copy</SecondaryButton
									>
									<PrimaryButton
										type="button"
										@click="showModalDetailEdit(schedule)"
										>Edit</PrimaryButton
									>
									<DangerButton
										type="button"
										@click="showModalDetailDelete(schedule)"
										>Delete</DangerButton
									>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div
					class="mt-2"
					v-if="schedule_details.last_page > 1">
					<ul class="flex justify-end items-center -space-x-px h-[2.5rem] text-sm">
						<template
							v-for="(link, index) in schedule_details.links"
							:key="index">
							<li v-if="link.active || (schedule_details.current_page == 1 && index == 3) || (schedule_details.current_page == schedule_details.last_page && index == schedule_details.links.length - 4) || (!link.active && link.label != '...' && (index == 0 || index == schedule_details.links.length - 1 || link.url == schedule_details.next_page_url || link.url == schedule_details.prev_page_url))">
								<Link
									class="flex items-center justify-center px-3 h-[2.5rem] min-w-[2.5rem] leading-tight"
									v-if="!link.active && link.url"
									:class="{
										'text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white': !link.active,
										'text-blue-600 border border-blue-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white': link.active,
										'rounded-l-lg': index == 0,
										'rounded-r-lg': index == schedule_details.links.length - 1,
									}"
									:href="link.url"
                                    replace
                                    preserve-scroll
									:only="['schedule_details', 'sort', 'sort_by']"
									>{{ index == 0 ? "<" : index == schedule_details.links.length - 1 ? ">" : link.label }}</Link
								>
								<span
									class="flex items-center justify-center px-3 h-[2.5rem] min-w-[2.5rem] leading-tight"
									v-if="link.active || !link.url"
									:class="{
										'text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white': !link.active,
										'text-blue-600 border border-blue-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white': link.active,
										'rounded-l-lg': index == 0,
										'rounded-r-lg': index == schedule_details.links.length - 1,
									}"
									>{{ index == 0 ? "<" : index == schedule_details.links.length - 1 ? ">" : link.label }}</span
								>
							</li>
						</template>
					</ul>
				</div>
			</div>
		</div>

		<!-- Creating Schedule Modal -->
		<Modal
			:show="isShowModalScheduleNew"
			@close="closeModalScheduleNew">
			<div class="flex items-start justify-between p-4 border-b rounded-t">
				<h3 class="text-xl font-semibold text-gray-900">New main schedule</h3>
				<button
					class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
					type="button"
					@click="closeModalScheduleNew">
					<svg
						aria-hidden="true"
						class="w-5 h-5"
						fill="currentColor"
						viewBox="0 0 20 20"
						xmlns="http://www.w3.org/2000/svg">
						<path
							clip-rule="evenodd"
							d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
							fill-rule="evenodd" />
					</svg>
					<span class="sr-only">Close modal</span>
				</button>
			</div>
			<div class="p-6">
				<form
					class="space-y-6"
					id="form-schedule-new"
					@submit.prevent="submitScheduleNew">
					<div>
						<InputLabel
							for="name"
							value="Name" />
						<TextInput
							class="mt-1 block w-full"
							id="name"
							placeholder="Name"
							required
							type="text"
							v-model="formScheduleNew.name" />
						<InputError
							class="mt-2"
							:message="formScheduleNew.errors.name" />
					</div>
					<div>
						<InputLabel
							for="numOfClassPeriodsPerDay"
							value="Num of class periods per day" />
						<TextInput
							class="mt-1 block w-full"
							id="numOfClassPeriodsPerDay"
							min="1"
							placeholder="1"
							required
							step="1"
							type="number"
							v-model="formScheduleNew.numOfClassPeriodsPerDay" />
						<InputError
							class="mt-2"
							:message="formScheduleNew.errors.numOfClassPeriodsPerDay" />
					</div>
					<div
						v-for="i in formScheduleNew.numOfClassPeriodsPerDay * 1"
						:key="i">
						<InputLabel>
							<b>Period {{ i }}</b>
						</InputLabel>
						<div class="grid grid-cols-2 gap-2">
							<div>
								<InputLabel
									value="Start"
									:for="`start-${i}`" />
								<TextInput
									class="mt-1 block w-full"
									required
									type="time"
									v-model="timeOfEachClassPeriodSFN[i]"
									:id="`start-${i}`" />
								<InputError
									class="mt-2"
									:message="formScheduleNew.errors[`timeOfEachClassPeriod.${i}.start`]" />
							</div>
							<div>
								<InputLabel
									value="End"
									:for="`end-${i}`" />
								<TextInput
									class="mt-1 block w-full"
									required
									type="time"
									v-model="timeOfEachClassPeriodEFN[i]"
									:id="`end-${i}`" />
								<InputError
									class="mt-2"
									:message="formScheduleNew.errors[`timeOfEachClassPeriod.${i}.end`]" />
							</div>
						</div>
						<InputError
							class="mt-2"
							:message="formScheduleNew.errors[`timeOfEachClassPeriod.${i}`]" />
					</div>
				</form>
			</div>
			<div class="flex justify-end items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
				<SecondaryButton
					type="button"
					@click="closeModalScheduleNew"
					>Close</SecondaryButton
				>
				<PrimaryButton
					form="form-schedule-new"
					type="submit"
					>Add</PrimaryButton
				>
			</div>
		</Modal>

		<!-- Editing Schedule Modal -->
		<Modal
			:show="isShowModalScheduleEdit"
			@close="closeModalScheduleEdit">
			<div class="flex items-start justify-between p-4 border-b rounded-t">
				<h3 class="text-xl font-semibold text-gray-900">Edit main schedule</h3>
				<button
					class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
					type="button"
					@click="closeModalScheduleEdit">
					<svg
						aria-hidden="true"
						class="w-5 h-5"
						fill="currentColor"
						viewBox="0 0 20 20"
						xmlns="http://www.w3.org/2000/svg">
						<path
							clip-rule="evenodd"
							d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
							fill-rule="evenodd" />
					</svg>
					<span class="sr-only">Close modal</span>
				</button>
			</div>
			<div class="p-6">
				<form
					class="space-y-6"
					id="form-schedule-edit"
					@submit.prevent="submitScheduleEdit">
					<div>
						<InputLabel
							for="name"
							value="Name" />
						<TextInput
							class="mt-1 block w-full"
							id="name"
							placeholder="Name"
							required
							type="text"
							v-model="formScheduleEdit.name" />
						<InputError
							class="mt-2"
							:message="formScheduleEdit.errors.name" />
					</div>
					<div>
						<InputLabel
							for="numOfClassPeriodsPerDay"
							value="Num of class periods per day" />
						<TextInput
							class="mt-1 block w-full"
							id="numOfClassPeriodsPerDay"
							min="1"
							placeholder="1"
							required
							step="1"
							type="number"
							v-model="formScheduleEdit.numOfClassPeriodsPerDay" />
						<InputError
							class="mt-2"
							:message="formScheduleEdit.errors.numOfClassPeriodsPerDay" />
					</div>
					<div
						v-for="i in formScheduleEdit.numOfClassPeriodsPerDay * 1"
						:key="i">
						<InputLabel>
							<b>Period {{ i }}</b>
						</InputLabel>
						<div class="grid grid-cols-2 gap-2">
							<div>
								<InputLabel
									value="Start"
									:for="`start-${i}`" />
								<TextInput
									class="mt-1 block w-full"
									type="time"
									v-model="timeOfEachClassPeriodSFE[i]"
									:id="`start-${i}`" />
								<InputError
									class="mt-2"
									:message="formScheduleEdit.errors[`timeOfEachClassPeriod.${i}.start`]" />
							</div>
							<div>
								<InputLabel
									value="End"
									:for="`end-${i}`" />
								<TextInput
									class="mt-1 block w-full"
									required
									type="time"
									v-model="timeOfEachClassPeriodEFE[i]"
									:id="`end-${i}`" />
								<InputError
									class="mt-2"
									:message="formScheduleEdit.errors[`timeOfEachClassPeriod.${i}.end`]" />
							</div>
						</div>
						<InputError
							class="mt-2"
							:message="formScheduleEdit.errors[`timeOfEachClassPeriod.${i}`]" />
					</div>
				</form>
			</div>
			<div class="flex justify-end items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
				<SecondaryButton
					type="button"
					@click="closeModalScheduleEdit"
					>Close</SecondaryButton
				>
				<PrimaryButton
					form="form-schedule-edit"
					type="submit"
					>Update</PrimaryButton
				>
			</div>
		</Modal>

		<!-- Deleting Schedule Modal-->
		<Modal
			:show="isShowModalScheduleDelete"
			@close="closeModalScheduleDelete">
			<div class="flex items-start justify-between p-4 border-b rounded-t">
				<h3 class="text-xl font-semibold text-gray-900">Delete main schedule</h3>
				<button
					class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
					type="button"
					@click="closeModalDetailDelete">
					<svg
						aria-hidden="true"
						class="w-5 h-5"
						fill="currentColor"
						viewBox="0 0 20 20"
						xmlns="http://www.w3.org/2000/svg">
						<path
							clip-rule="evenodd"
							d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
							fill-rule="evenodd" />
					</svg>
					<span class="sr-only">Close modal</span>
				</button>
			</div>
			<div class="p-6">
				Do you really want to delete
				<b>"{{ schedule_selected.name }}"</b>?
			</div>
			<div class="flex justify-end items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
				<PrimaryButton
					type="button"
					@click="closeModalScheduleDelete"
					>No</PrimaryButton
				>
				<DangerButton
					type="button"
					@click="submitScheduleDelete"
					>Yes</DangerButton
				>
			</div>
		</Modal>

		<!-- Creating Detail Modal -->
		<Modal
			:show="isShowModalDetailNew"
			@close="closeModalDetailNew">
			<div class="flex items-start justify-between p-4 border-b rounded-t">
				<h3 class="text-xl font-semibold text-gray-900">New schedule</h3>
				<button
					type="button"
					class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
					@click="closeModalDetailNew">
					<svg
						aria-hidden="true"
						class="w-5 h-5"
						fill="currentColor"
						viewBox="0 0 20 20"
						xmlns="http://www.w3.org/2000/svg">
						<path
							clip-rule="evenodd"
							d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
							fill-rule="evenodd" />
					</svg>
					<span class="sr-only">Close modal</span>
				</button>
			</div>
			<div class="p-6">
				<form
					class="space-y-6"
					id="form-new"
					@submit.prevent="submitDetailNew">
					<div>
						<InputLabel
							for="subject_id"
							value="Subject" />
						<select
							class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
							id="subject_id"
							required
							v-model="formDetailNew.subject_id"
							placeholder="-- Subject --">
							<option
								disabled
								value="">
								-- Subject --
							</option>
							<option
								v-for="subject in subjects"
								:key="subject.subject_id"
								:value="subject.subject_id">
								{{ subject.subject_id }} - {{ subject.name }}
							</option>
						</select>
						<InputError
							class="mt-2"
							:message="formDetailNew.errors.subject_id" />
					</div>
					<div class="grid grid-cols-2 gap-2">
						<div>
							<InputLabel
								for="start"
								value="Start" />
							<select
								class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
								id="start"
								required
								v-model="formDetailNew.start">
								<option
									v-for="index in numOfClassPeriodsPerDay * 1"
									:key="index"
									:value="index">
									{{ index }}. {{ timeOfEachClassPeriod[index].start }} -
									{{ timeOfEachClassPeriod[index].end }}
								</option>
							</select>
							<InputError
								class="mt-2"
								:message="formDetailNew.errors.start" />
						</div>
						<div>
							<InputLabel
								for="end"
								value="End" />
							<select
								class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
								id="end"
								required
								v-model="formDetailNew.end">
								<option
									v-for="index in (numOfClassPeriodsPerDay - formDetailNew.start + 1) * 1"
									:key="index + formDetailNew.start - 1"
									:value="index + formDetailNew.start - 1">
									{{ index + formDetailNew.start - 1 }}.
									{{ timeOfEachClassPeriod[index + formDetailNew.start - 1].start }}
									-
									{{ timeOfEachClassPeriod[index + formDetailNew.start - 1].end }}
								</option>
							</select>
							<InputError
								class="mt-2"
								:message="formDetailNew.errors.end" />
						</div>
					</div>
					<div class="grid grid-cols-2 gap-2">
						<div>
							<InputLabel
								for="from"
								value="From" />
							<TextInput
								class="mt-1 block w-full"
								id="from"
								required
								type="date"
								v-model="formDetailNew.from" />
							<InputError
								class="mt-2"
								:message="formDetailNew.errors.from" />
						</div>
						<div>
							<InputLabel
								for="to"
								value="To" />
							<TextInput
								class="mt-1 block w-full"
								id="to"
								required
								type="date"
								v-model="formDetailNew.to" />
							<InputError
								class="mt-2"
								:message="formDetailNew.errors.to" />
						</div>
					</div>
					<div>
						<InputLabel
							for="type"
							value="Type" />
						<div class="mt-1 w-full grid grid-cols-2 gap-2">
							<div class="flex items-center space-x-2">
								<input
									class="rounded-full"
									id="type_offline"
									type="radio"
									value="OFFLINE"
									v-model="formDetailNew.type" />
								<InputLabel
									for="type_offline"
									value="OFFLINE" />
							</div>

							<div class="flex items-center space-x-2">
								<input
									class="rounded-full"
									id="type_online"
									type="radio"
									value="ONLINE"
									v-model="formDetailNew.type" />
								<InputLabel
									for="type_online"
									value="ONLINE" />
							</div>
						</div>
						<InputError
							class="mt-2"
							:message="formDetailNew.errors.type" />
					</div>
					<div>
						<InputLabel
							for="dateOfWeek"
							value="Date (hold Ctrl for multiple select)" />
						<select
							class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
							id="dateOfWeek"
							required
							v-model="formDetailNew.dateOfWeek"
                            multiple
                            size="3">
							<option
								v-for="(dateName, dateValue) in dateOfWeek"
								:key="dateValue"
								:value="dateValue">
								{{ dateName }}
							</option>
						</select>
						<InputError
							class="mt-2"
							:message="formDetailNew.errors.dateOfWeek" />
                        <InputError
                            class="mt-2"
                            v-for="key in Object.keys(formDetailNew.errors).filter(v => v.indexOf('dateOfWeek.') > -1)"
                            :key="key"
                            :message="formDetailNew.errors[key]" />
					</div>
					<div>
						<div class="flex items-center space-x-2">
							<Checkbox
								id="is_makeUp_class"
								v-model="formDetailNew.is_makeUp_class"
                                :checked="formDetailNew.is_makeUp_class" />
							<InputLabel
								for="is_makeUp_class"
								value="Is make-up class" />
						</div>
						<InputError
							class="mt-2"
							:checked="formDetailNew.is_makeUp_class"
							:message="formDetailNew.errors.is_makeUp_class" />
					</div>
				</form>
			</div>
			<div class="flex justify-end items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
				<SecondaryButton
					type="button"
					@click="closeModalDetailNew"
					>Close</SecondaryButton
				>
				<PrimaryButton
					form="form-new"
					type="submit"
					>Add</PrimaryButton
				>
			</div>
		</Modal>

		<!-- Editing Detail Modal -->
		<Modal
			:show="isShowModalDetailEdit"
			@close="closeModalDetailEdit">
			<div class="flex items-start justify-between p-4 border-b rounded-t">
				<h3 class="text-xl font-semibold text-gray-900">Edit schedule</h3>
				<button
					class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
					type="button"
					@click="closeModalDetailEdit">
					<svg
						aria-hidden="true"
						class="w-5 h-5"
						fill="currentColor"
						viewBox="0 0 20 20"
						xmlns="http://www.w3.org/2000/svg">
						<path
							clip-rule="evenodd"
							d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
							fill-rule="evenodd" />
					</svg>
					<span class="sr-only">Close modal</span>
				</button>
			</div>
			<div class="p-6">
				<form
					class="space-y-6"
					id="form-edit"
					@submit.prevent="submitDetailEdit">
					<div>
						<InputLabel
							for="subject_id"
							value="Subject" />
						<select
							class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
							id="subject_id"
							required
							v-model="formDetailEdit.subject_id">
							<option
								v-for="subject in subjects"
								:key="subject.subject_id"
								:value="subject.subject_id">
								{{ subject.subject_id }} - {{ subject.name }}
							</option>
						</select>
						<InputError
							class="mt-2"
							:message="formDetailEdit.errors.subject_id" />
					</div>
					<div class="grid grid-cols-2 gap-2">
						<div>
							<InputLabel
								for="start"
								value="Start" />

							<select
								class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
								id="start"
								required
								v-model="formDetailEdit.start">
								<option
									v-for="index in numOfClassPeriodsPerDay * 1"
									:key="index"
									:value="index">
									{{ index }}. {{ timeOfEachClassPeriod[index].start }} -
									{{ timeOfEachClassPeriod[index].end }}
								</option>
							</select>
							<InputError
								class="mt-2"
								:message="formDetailEdit.errors.start" />
						</div>
						<div>
							<InputLabel
								for="end"
								value="End" />
							<select
								class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
								id="end"
								required
								v-model="formDetailEdit.end">
								<option
									v-for="index in (numOfClassPeriodsPerDay - formDetailEdit.start + 1) * 1"
									:key="index + formDetailEdit.start - 1"
									:value="index + formDetailEdit.start - 1">
									{{ index + formDetailEdit.start - 1 }}.
									{{ timeOfEachClassPeriod[index + formDetailEdit.start - 1].start }}
									-
									{{ timeOfEachClassPeriod[index + formDetailEdit.start - 1].end }}
								</option>
							</select>
							<InputError
								class="mt-2"
								:message="formDetailEdit.errors.end" />
						</div>
					</div>
					<div class="grid grid-cols-2 gap-2">
						<div>
							<InputLabel
								for="from"
								value="From" />
							<TextInput
								class="mt-1 block w-full"
								id="from"
								required
								type="date"
								v-model="formDetailEdit.from" />
							<InputError
								class="mt-2"
								:message="formDetailEdit.errors.from" />
						</div>
						<div>
							<InputLabel
								for="to"
								value="To" />
							<TextInput
								class="mt-1 block w-full"
								id="to"
								required
								type="date"
								v-model="formDetailEdit.to" />
							<InputError
								class="mt-2"
								:message="formDetailEdit.errors.to" />
						</div>
					</div>
					<div>
						<InputLabel
							for="type"
							value="Type" />

						<div class="mt-1 w-full grid grid-cols-2 gap-2">
							<div class="flex items-center space-x-2">
								<input
									class="rounded-full"
									id="type_offline"
									type="radio"
									value="OFFLINE"
									v-model="formDetailEdit.type" />
								<InputLabel
									for="type_offline"
									value="OFFLINE" />
							</div>

							<div class="flex items-center space-x-2">
								<input
									class="rounded-full"
									id="type_online"
									type="radio"
									value="ONLINE"
									v-model="formDetailEdit.type" />
								<InputLabel
									for="type_online"
									value="ONLINE" />
							</div>
						</div>
						<InputError
							class="mt-2"
							:message="formDetailEdit.errors.type" />
					</div>
					<div>
						<InputLabel
							for="dateOfWeek"
							value="Date (hold Ctrl for multiple select)" />
						<select
							class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
							id="dateOfWeek"
							required
							v-model="formDetailEdit.dateOfWeek"
                            multiple
                            size="3">
							<option
								v-for="(dateName, dateValue) in dateOfWeek"
								:key="dateValue"
								:value="dateValue">
								{{ dateName }}
							</option>
						</select>
						<InputError
							class="mt-2"
							:message="formDetailEdit.errors.dateOfWeek" />
                        <InputError
                            class="mt-2"
                            v-for="key in Object.keys(formDetailEdit.errors).filter(v => v.indexOf('dateOfWeek.') > -1)"
                            :key="key"
                            :message="formDetailEdit.errors[key]" />
					</div>
					<div>
						<div class="flex items-center space-x-2">
							<Checkbox
								id="is_makeUp_class"
								v-model="formDetailEdit.is_makeUp_class"
								:checked="formDetailEdit.is_makeUp_class" />
							<InputLabel
								for="is_makeUp_class"
								value="Is make-up class" />
						</div>
						<InputError
							class="mt-2"
							:message="formDetailEdit.errors.is_makeUp_class" />
					</div>
				</form>
			</div>
			<div class="flex justify-end items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
				<SecondaryButton
					@click="closeModalDetailEdit"
					type="button"
					>Close</SecondaryButton
				>
				<PrimaryButton
					form="form-edit"
					type="submit"
					>Update</PrimaryButton
				>
			</div>
		</Modal>

		<!-- Deleting Detail Modal -->
		<Modal
			:show="isShowModalDetailDelete"
			@close="closeModalDetailDelete">
			<div class="flex items-start justify-between p-4 border-b rounded-t">
				<h3 class="text-xl font-semibold text-gray-900">Delete schedule</h3>
				<button
					class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
					type="button"
					@click="closeModalDetailDelete">
					<svg
						aria-hidden="true"
						class="w-5 h-5"
						fill="currentColor"
						viewBox="0 0 20 20"
						xmlns="http://www.w3.org/2000/svg">
						<path
							clip-rule="evenodd"
							d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
							fill-rule="evenodd" />
					</svg>
					<span class="sr-only">Close modal</span>
				</button>
			</div>
			<div class="p-6">
				Do you really want to delete
				<b>"{{ deleteDetail && subjects.find((v) => v.subject_id == deleteDetail.subject_id)?.name }}" ({{ deleteDetail && deleteDetail.from }} - {{ deleteDetail && deleteDetail.to }})</b>?
			</div>
			<div class="flex justify-end items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
				<PrimaryButton
					type="button"
					@click="closeModalDetailDelete"
					>No</PrimaryButton
				>
				<DangerButton
					type="button"
					@click="submitDetailDelete"
					>Yes</DangerButton
				>
			</div>
		</Modal>
	</AuthenticatedLayout>
</template>
