<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, Link, router, useForm} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import Modal from "@/Components/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import {ref} from "vue";

defineProps({
	subjects: {
		type: Object,
		required: true,
	},
	teachers: {
		type: Object,
		required: true,
	},
	sort: {
		type: String,
		default: "ASC",
	},
	sort_by: {
		type: String,
		default: "subject_id",
	},
});

const formNew = useForm({
	subject_id: "",
	name: "",
	credits: 1,
	color_foreground: "#000000",
	color_background: "#ffffff",
	teacher_id: "",
});
const isShowModalNew = ref(false);

const showModalNew = () => {
	formNew.reset();
	isShowModalNew.value = true;
};

const closeModalNew = () => {
	isShowModalNew.value = false;
	formNew.reset();
};

const submitNew = () => {
	formNew.post(route("subject.store"), {
		onSuccess: closeModalNew,
	});
};

const formEdit = useForm({
	subject_id: "",
	name: "",
	credits: 1,
	color_foreground: "#000000",
	color_background: "#ffffff",
	teacher_id: "",
});
const isShowModalEdit = ref(false);
const editSubject = ref(null);

const showModalEdit = (subject) => {
	editSubject.value = subject;
	formEdit.subject_id = subject.subject_id;
	formEdit.name = subject.name;
	formEdit.credits = subject.credits;
	formEdit.color_foreground = subject.color_foreground;
	formEdit.color_background = subject.color_background;
	formEdit.teacher_id = subject.teacher_id;
	isShowModalEdit.value = true;
};

const closeModalEdit = () => {
	isShowModalEdit.value = false;
	formEdit.reset();
	editSubject.value = null;
};

const submitEdit = () => {
	formEdit.patch(route("subject.update", editSubject.value), {
		onSuccess: closeModalEdit,
	});
};

const isShowModalDelete = ref(false);
const deleteSubject = ref(null);

const showModalDelete = (subject) => {
	deleteSubject.value = subject;
	isShowModalDelete.value = true;
};

const closeModalDelete = () => {
	isShowModalDelete.value = false;
	deleteSubject.value = null;
};

const submitDelete = () => {
	router.delete(route("subject.destroy", deleteSubject.value), {
		onSuccess: closeModalDelete,
	});
};
</script>
<template>
	<Head title="Subjects" />
	<AuthenticatedLayout>
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight flex justify-between items-center">
				Subjects
				<PrimaryButton @click="showModalNew">New</PrimaryButton>
			</h2>
		</template>
		<div class="py-12">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="flex flex-wrap justify-end items-center space-x-2">
					<Dropdown
						align="right"
						class="mb-2">
						<template #trigger>
							<span class="inline-flex rounded-md">
								<PrimaryButton>
									Limit: {{ subjects.per_page }}

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
									route('subject.index', {
										sort: sort,
										sort_by: sort_by,
										limit: limit,
									})
								"
								preserve-scroll
								v-bind:key="limit"
								:only="['subjects', 'sort', 'sort_by']">
								{{ limit }}
							</DropdownLink>
						</template>
					</Dropdown>
					<ul
						class="flex justify-center items-center -space-x-px h-[2.5rem] text-sm mb-2"
						v-if="subjects.last_page > 1">
						<template
							v-for="(link, index) in subjects.links"
							:key="index">
							<li v-if="link.active || (subjects.current_page == 1 && index == 3) || (subjects.current_page == subjects.last_page && index == subjects.links.length - 4) || (!link.active && link.label != '...' && (index == 0 || index == subjects.links.length - 1 || link.url == subjects.next_page_url || link.url == subjects.prev_page_url))">
								<Link
									:href="link.url"
									class="flex items-center justify-center px-3 h-[2.5rem] min-w-[2.5rem] leading-tight"
									:class="{
										'text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white': !link.active,
										'text-blue-600 border border-blue-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white': link.active,
										'rounded-l-lg': index == 0,
										'rounded-r-lg': index == subjects.links.length - 1,
									}"
									:only="['subjects', 'sort', 'sort_by']"
									v-if="!link.active && link.url"
									>{{ index == 0 ? "<" : index == subjects.links.length - 1 ? ">" : link.label }}</Link
								>
								<span
									class="flex items-center justify-center px-3 h-[2.5rem] min-w-[2.5rem] leading-tight"
									:class="{
										'text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white': !link.active,
										'text-blue-600 border border-blue-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white': link.active,
										'rounded-l-lg': index == 0,
										'rounded-r-lg': index == subjects.links.length - 1,
									}"
									v-if="link.active || !link.url"
									>{{ index == 0 ? "<" : index == subjects.links.length - 1 ? ">" : link.label }}</span
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
											route('subject.index', {
												sort: sort_by.toLowerCase() == 'subject_id' ? (sort.toUpperCase() == 'DESC' ? 'ASC' : 'DESC') : 'ASC',
												limit: limit,
												page: subjects.current_page,
											})
										"
										preserve-scroll
										class="flex items-center space-x-1 justify-center"
										:only="['subjects', 'sort', 'sort_by']">
										<span>ID</span>
										<svg
											v-if="sort.toUpperCase() == 'ASC' && sort_by.toLowerCase() == 'subject_id'"
											xmlns="http://www.w3.org/2000/svg"
											width="1em"
											height="1em"
											viewBox="0 0 24 24">
											<path
												fill="currentColor"
												d="m7 14l5-5l5 5z"></path>
										</svg>
										<svg
											v-if="sort.toUpperCase() == 'DESC' && sort_by.toLowerCase() == 'subject_id'"
											xmlns="http://www.w3.org/2000/svg"
											width="1em"
											height="1em"
											viewBox="0 0 24 24">
											<path
												fill="currentColor"
												d="m7 10l5 5l5-5z"></path>
										</svg>
									</Link>
								</th>
								<th class="px-3 md:px-6 py-3 whitespace-nowrap text-left">
									<Link
										:href="
											route('subject.index', {
												sort: sort_by.toLowerCase() == 'name' ? (sort.toUpperCase() == 'DESC' ? 'ASC' : 'DESC') : 'ASC',
												sort_by: 'name',
												limit: limit,
												page: subjects.current_page,
											})
										"
										preserve-scroll
										class="flex items-center space-x-1"
										:only="['subjects', 'sort', 'sort_by']">
										<span>Name</span>
										<svg
											v-if="sort.toUpperCase() == 'ASC' && sort_by.toLowerCase() == 'name'"
											xmlns="http://www.w3.org/2000/svg"
											width="1em"
											height="1em"
											viewBox="0 0 24 24">
											<path
												fill="currentColor"
												d="m7 14l5-5l5 5z"></path>
										</svg>
										<svg
											v-if="sort.toUpperCase() == 'DESC' && sort_by.toLowerCase() == 'name'"
											xmlns="http://www.w3.org/2000/svg"
											width="1em"
											height="1em"
											viewBox="0 0 24 24">
											<path
												fill="currentColor"
												d="m7 10l5 5l5-5z"></path>
										</svg>
									</Link>
								</th>
								<th class="px-3 md:px-6 py-3 whitespace-nowrap text-left w-auto">Teacher</th>
								<th class="px-3 md:px-6 py-3 whitespace-nowrap w-auto">
									<Link
										:href="
											route('subject.index', {
												sort: sort_by.toLowerCase() == 'credits' ? (sort.toUpperCase() == 'DESC' ? 'ASC' : 'DESC') : 'ASC',
												sort_by: 'credits',
												limit: limit,
												page: subjects.current_page,
											})
										"
										preserve-scroll
										class="flex items-center space-x-1"
										:only="['subjects', 'sort', 'sort_by']">
										<svg
											v-if="sort.toUpperCase() == 'ASC' && sort_by.toLowerCase() == 'credits'"
											xmlns="http://www.w3.org/2000/svg"
											width="1em"
											height="1em"
											viewBox="0 0 24 24">
											<path
												fill="currentColor"
												d="m7 14l5-5l5 5z"></path>
										</svg>
										<svg
											v-if="sort.toUpperCase() == 'DESC' && sort_by.toLowerCase() == 'credits'"
											xmlns="http://www.w3.org/2000/svg"
											width="1em"
											height="1em"
											viewBox="0 0 24 24">
											<path
												fill="currentColor"
												d="m7 10l5 5l5-5z"></path>
										</svg>
										<span>Credits</span>
									</Link>
								</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<tr
								v-for="subject in subjects.data"
								class="even:bg-gray-300 hover:bg-gray-500 hover:text-white">
								<td
									class="px-3 py-3 whitespace-nowrap text-center w-auto"
									:style="{
										color: subject.color_foreground,
										background: subject.color_background,
									}">
									{{ subject.subject_id }}
								</td>
								<td class="px-3 md:px-6 py-3 whitespace-nowrap w-full">
									{{ subject.name }}
								</td>
								<td class="px-3 md:px-6 py-3 whitespace-nowrap w-auto">
									{{ teachers.find((v) => v.teacher_id == subject.teacher_id).name }}
								</td>
								<td class="px-3 md:px-6 py-3 whitespace-nowrap text-right w-auto">
									{{ subject.credits }}
								</td>
								<td class="px-3 md:px-6 py-1 whitespace-nowrap text-center space-x-2">
									<PrimaryButton
										type="button"
										@click="showModalEdit(subject)"
										>Edit</PrimaryButton
									>
									<DangerButton
										type="button"
										@click="showModalDelete(subject)"
										>Delete</DangerButton
									>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div
					class="mt-2"
					v-if="subjects.last_page > 1">
					<ul class="flex justify-end items-center -space-x-px h-[2.5rem] text-sm">
						<template
							v-for="(link, index) in subjects.links"
							:key="index">
							<li v-if="link.active || (subjects.current_page == 1 && index == 3) || (subjects.current_page == subjects.last_page && index == subjects.links.length - 4) || (!link.active && link.label != '...' && (index == 0 || index == subjects.links.length - 1 || link.url == subjects.next_page_url || link.url == subjects.prev_page_url))">
								<Link
									:href="link.url"
									class="flex items-center justify-center px-3 h-[2.5rem] min-w-[2.5rem] leading-tight"
									:class="{
										'text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white': !link.active,
										'text-blue-600 border border-blue-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white': link.active,
										'rounded-l-lg': index == 0,
										'rounded-r-lg': index == subjects.links.length - 1,
									}"
									:only="['subjects', 'sort', 'sort_by']"
									v-if="!link.active && link.url"
									>{{ index == 0 ? "<" : index == subjects.links.length - 1 ? ">" : link.label }}</Link
								>
								<span
									class="flex items-center justify-center px-3 h-[2.5rem] min-w-[2.5rem] leading-tight"
									:class="{
										'text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white': !link.active,
										'text-blue-600 border border-blue-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white': link.active,
										'rounded-l-lg': index == 0,
										'rounded-r-lg': index == subjects.links.length - 1,
									}"
									v-if="link.active || !link.url"
									>{{ index == 0 ? "<" : index == subjects.links.length - 1 ? ">" : link.label }}</span
								>
							</li>
						</template>
					</ul>
				</div>
			</div>
		</div>
		<Modal
			:show="isShowModalNew"
			@close="closeModalNew">
			<div class="flex items-start justify-between p-4 border-b rounded-t">
				<h3 class="text-xl font-semibold text-gray-900">New subject</h3>
				<button
					type="button"
					class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
					@click="closeModalNew">
					<svg
						aria-hidden="true"
						class="w-5 h-5"
						fill="currentColor"
						viewBox="0 0 20 20"
						xmlns="http://www.w3.org/2000/svg">
						<path
							fill-rule="evenodd"
							d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
							clip-rule="evenodd"></path>
					</svg>
					<span class="sr-only">Close modal</span>
				</button>
			</div>
			<div class="p-6">
				<form
					@submit.prevent="submitNew"
					id="form-new"
					class="space-y-6">
					<div>
						<InputLabel
							for="id"
							value="ID" />

						<TextInput
							id="id"
							type="text"
							class="mt-1 block w-full"
							v-model="formNew.subject_id"
							required
							placeholder="ABC..." />
						<InputError
							class="mt-2"
							:message="formNew.errors.subject_id" />
					</div>
					<div>
						<InputLabel
							for="name"
							value="Name" />

						<TextInput
							id="name"
							type="text"
							class="mt-1 block w-full"
							v-model="formNew.name"
							required
							placeholder="ABC..." />
						<InputError
							class="mt-2"
							:message="formNew.errors.name" />
					</div>
					<div>
						<InputLabel
							for="credits"
							value="Credits" />

						<TextInput
							id="credits"
							type="number"
							class="mt-1 block w-full"
							v-model="formNew.credits"
							required
							min="0"
							step="1"
							placeholder="0" />
						<InputError
							class="mt-2"
							:message="formNew.errors.credits" />
					</div>
					<div class="grid grid-cols-2 gap-2">
						<div>
							<InputLabel
								for="color_foreground"
								value="Text's color" />

							<TextInput
								id="color_foreground"
								type="color"
								class="mt-1 block w-full"
								v-model="formNew.color_foreground"
								required
								placeholder="#000000" />
							<InputError
								class="mt-2"
								:message="formNew.errors.color_foreground" />
						</div>
						<div>
							<InputLabel
								for="color_background"
								value="Background's color" />

							<TextInput
								id="color_background"
								type="color"
								class="mt-1 block w-full"
								v-model="formNew.color_background"
								required
								placeholder="#ffffff" />
							<InputError
								class="mt-2"
								:message="formNew.errors.color_background" />
						</div>
					</div>
					<div>
						<InputLabel
							:value="formNew.subject_id"
							:style="{
								color: formNew.color_foreground,
								background: formNew.color_background,
							}"
							class="py-3 text-center border border-gray-500" />
					</div>
					<div>
						<InputLabel
							for="teacher_id"
							value="Teacher" />

						<select
							id="teacher_id"
							v-model="formNew.teacher_id"
							required
							class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
							<option
								disabled
								value="">
								-- Teacher --
							</option>
							<option
								v-for="teacher in subjects"
								:key="teacher.teacher_id"
								:value="teacher.teacher_id">
								{{ teacher.name }}
							</option>
						</select>
						<InputError
							class="mt-2"
							:message="formNew.errors.teacher_id" />
					</div>
				</form>
			</div>
			<div class="flex justify-end items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
				<SecondaryButton
					@click="closeModalNew"
					type="button"
					>Close</SecondaryButton
				>
				<PrimaryButton
					type="submit"
					form="form-new"
					>Add</PrimaryButton
				>
			</div>
		</Modal>

		<Modal
			:show="isShowModalEdit"
			@close="closeModalEdit">
			<div class="flex items-start justify-between p-4 border-b rounded-t">
				<h3 class="text-xl font-semibold text-gray-900">Edit subject</h3>
				<button
					type="button"
					class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
					@click="closeModalEdit">
					<svg
						aria-hidden="true"
						class="w-5 h-5"
						fill="currentColor"
						viewBox="0 0 20 20"
						xmlns="http://www.w3.org/2000/svg">
						<path
							fill-rule="evenodd"
							d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
							clip-rule="evenodd"></path>
					</svg>
					<span class="sr-only">Close modal</span>
				</button>
			</div>
			<div class="p-6">
				<form
					@submit.prevent="submitEdit"
					id="form-edit"
					class="space-y-6">
					<div>
						<InputLabel
							for="id"
							value="ID" />

						<TextInput
							id="id"
							type="text"
							class="mt-1 block w-full"
							v-model="formEdit.subject_id"
							required
							placeholder="ABC..." />
						<InputError
							class="mt-2"
							:message="formEdit.errors.subject_id" />
					</div>
					<div>
						<InputLabel
							for="name"
							value="Name" />

						<TextInput
							id="name"
							type="text"
							class="mt-1 block w-full"
							v-model="formEdit.name"
							required
							placeholder="ABC..." />
						<InputError
							class="mt-2"
							:message="formEdit.errors.name" />
					</div>
					<div>
						<InputLabel
							for="credits"
							value="Credits" />

						<TextInput
							id="credits"
							type="number"
							class="mt-1 block w-full"
							v-model="formEdit.credits"
							required
							min="0"
							step="1"
							placeholder="0" />
						<InputError
							class="mt-2"
							:message="formEdit.errors.credits" />
					</div>
					<div class="grid grid-cols-2 gap-2">
						<div>
							<InputLabel
								for="color_foreground"
								value="Text's color" />

							<TextInput
								id="color_foreground"
								type="color"
								class="mt-1 block w-full"
								v-model="formEdit.color_foreground"
								required
								placeholder="#000000" />
							<InputError
								class="mt-2"
								:message="formEdit.errors.color_foreground" />
						</div>
						<div>
							<InputLabel
								for="color_background"
								value="Background's color" />

							<TextInput
								id="color_background"
								type="color"
								class="mt-1 block w-full"
								v-model="formEdit.color_background"
								required
								placeholder="#ffffff" />
							<InputError
								class="mt-2"
								:message="formEdit.errors.color_background" />
						</div>
					</div>
					<div>
						<InputLabel
							:value="formEdit.subject_id"
							:style="{
								color: formEdit.color_foreground,
								background: formEdit.color_background,
							}"
							class="py-3 text-center border border-gray-500" />
					</div>
					<div>
						<InputLabel
							for="teacher_id"
							value="Teacher" />

						<select
							id="teacher_id"
							v-model="formEdit.teacher_id"
							required
							class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
							<option
								v-for="teacher in subjects"
								:key="teacher.teacher_id"
								:value="teacher.teacher_id">
								{{ teacher.name }}
							</option>
						</select>
						<InputError
							class="mt-2"
							:message="formEdit.errors.teacher_id" />
					</div>
				</form>
			</div>
			<div class="flex justify-end items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
				<SecondaryButton
					@click="closeModalEdit"
					type="button"
					>Close</SecondaryButton
				>
				<PrimaryButton
					type="submit"
					form="form-edit"
					>Update</PrimaryButton
				>
			</div>
		</Modal>

		<Modal
			:show="isShowModalDelete"
			@close="closeModalDelete">
			<div class="flex items-start justify-between p-4 border-b rounded-t">
				<h3 class="text-xl font-semibold text-gray-900">Delete subject</h3>
				<button
					type="button"
					class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
					@click="closeModalDelete">
					<svg
						aria-hidden="true"
						class="w-5 h-5"
						fill="currentColor"
						viewBox="0 0 20 20"
						xmlns="http://www.w3.org/2000/svg">
						<path
							fill-rule="evenodd"
							d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
							clip-rule="evenodd"></path>
					</svg>
					<span class="sr-only">Close modal</span>
				</button>
			</div>
			<div class="p-6">
				Do you really want to delete
				<b>"{{ deleteSubject && deleteSubject.name }}"</b>?
			</div>
			<div class="flex justify-end items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
				<PrimaryButton
					@click="closeModalDelete"
					type="button"
					>No</PrimaryButton
				>
				<DangerButton
					@click="submitDelete"
					type="button"
					>Yes</DangerButton
				>
			</div>
		</Modal>
	</AuthenticatedLayout>
</template>
