<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import { ref } from 'vue';
import DangerButton from '@/Components/DangerButton.vue';

defineProps({
    subjects: {
        type: Object,
        required: true
    },
    teachers: {
        type: Object,
        required: true
    }
});

const formNew = useForm({
    subject_id: '',
    name: '',
    credits: 1,
    color_foreground: '#000000',
    color_background: '#ffffff',
    teacher_id: ''
});
const isShowModalNew = ref(false);

const showModalNew = () => {
    formNew.reset();
    isShowModalNew.value = true;
}

const closeModalNew = () => {
    isShowModalNew.value = false;
    formNew.reset();
}

const submitNew = () => {
    formNew.post(route('subject.store'), {
        onSuccess: closeModalNew,
    });
};

const formEdit = useForm({
    subject_id: '',
    name: '',
    credits: 1,
    color_foreground: '#000000',
    color_background: '#ffffff',
    teacher_id: ''
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
}

const closeModalEdit = () => {
    isShowModalEdit.value = false;
    formEdit.reset();
    editSubject.value = null;
}

const submitEdit = () => {
    formEdit.patch(route('subject.update', editSubject.value), {
        onSuccess: closeModalEdit,
    });
};

const isShowModalDelete = ref(false);
const deleteSubject = ref(null);

const showModalDelete = (subject) => {
    deleteSubject.value = subject;
    isShowModalDelete.value = true;
}

const closeModalDelete = () => {
    isShowModalDelete.value = false;
    deleteSubject.value = null;
}

const submitDelete = () => {
    router.delete(route('subject.destroy', deleteSubject.value), {
        onSuccess: closeModalDelete,
    });
}

</script>
<template>
    <Head title="Subjects" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between">
                Subjects
                <PrimaryButton @click="showModalNew">New</PrimaryButton>
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="overflow-x-auto bg-white">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-700 text-white">
                                <th class="px-3 py-3 whitespace-nowrap w-auto">ID</th>
                                <th class="px-3 md:px-6 py-3 whitespace-nowrap text-left">Name</th>
                                <th class="px-3 md:px-6 py-3 whitespace-nowrap text-left w-auto">Teacher</th>
                                <th class="px-3 md:px-6 py-3 whitespace-nowrap w-auto">Credits</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="subject in subjects" class="even:bg-gray-300 hover:bg-gray-500 hover:text-white">
                                <td class="px-3 py-3 whitespace-nowrap text-center w-auto" :style="{
                                    color: subject.color_foreground,
                                    background: subject.color_background
                                }">{{ subject.subject_id }}</td>
                                <td class="px-3 md:px-6 py-3 whitespace-nowrap w-full">{{ subject.name }}</td>
                                <td class="px-3 md:px-6 py-3 whitespace-nowrap w-auto">{{ subject.teacher.name }}</td>
                                <td class="px-3 md:px-6 py-3 whitespace-nowrap text-right w-auto">{{ subject.credits }}</td>
                                <td class="px-3 md:px-6 py-1 whitespace-nowrap text-center space-x-2">
                                    <PrimaryButton type="button" @click="showModalEdit(subject)">Edit</PrimaryButton>
                                    <DangerButton type="button" @click="showModalDelete(subject)">Delete</DangerButton>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <Modal :show="isShowModalNew" @close="closeModalNew">
            <div class="flex items-start justify-between p-4 border-b rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">
                    New subject
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" @click="closeModalNew">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-6">
                <form @submit.prevent="submitNew" id="form-new" class="space-y-6">
                    <div>
                        <InputLabel for="id" value="ID" />

                        <TextInput
                            id="id"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="formNew.subject_id"
                            required
                            placeholder="ABC..."
                        />
                        <InputError class="mt-2" :message="formNew.errors.subject_id" />
                    </div>
                    <div>
                        <InputLabel for="name" value="Name" />

                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="formNew.name"
                            required
                            placeholder="ABC..."
                        />
                        <InputError class="mt-2" :message="formNew.errors.name" />
                    </div>
                    <div>
                        <InputLabel for="credits" value="Credits" />

                        <TextInput
                            id="credits"
                            type="number"
                            class="mt-1 block w-full"
                            v-model="formNew.credits"
                            required
                            min="1"
                            step="1"
                            placeholder="0"
                        />
                        <InputError class="mt-2" :message="formNew.errors.credits" />
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <InputLabel for="color_foreground" value="Text's color" />

                            <TextInput
                                id="color_foreground"
                                type="color"
                                class="mt-1 block w-full"
                                v-model="formNew.color_foreground"
                                required
                                placeholder="#000000"
                            />
                            <InputError class="mt-2" :message="formNew.errors.color_foreground" />
                        </div>
                        <div>
                            <InputLabel for="color_background" value="Background's color" />

                            <TextInput
                                id="color_background"
                                type="color"
                                class="mt-1 block w-full"
                                v-model="formNew.color_background"
                                required
                                placeholder="#ffffff"
                            />
                            <InputError class="mt-2" :message="formNew.errors.color_background" />
                        </div>
                    </div>
                    <div>
                        <InputLabel :value="formNew.subject_id" :style="{
                            color: formNew.color_foreground,
                            background: formNew.color_background
                        }" class="py-3 text-center border border-gray-500" />
                    </div>
                    <div>
                        <InputLabel for="teacher_id" value="Teacher" />

                        <select id="teacher_id" v-model="formNew.teacher_id" required class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                            <option disabled value="">-- Teacher --</option>
                            <option v-for="teacher in teachers" :key="teacher.teacher_id" :value="teacher.teacher_id">
                                {{ teacher.name }}
                            </option>
                        </select>
                        <InputError class="mt-2" :message="formNew.errors.teacher_id" />
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
                    Edit subject
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" @click="closeModalEdit">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-6">
                <form @submit.prevent="submitEdit" id="form-edit" class="space-y-6">
                    <div>
                        <InputLabel for="id" value="ID" />

                        <TextInput
                            id="id"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="formEdit.subject_id"
                            required
                            placeholder="ABC..."
                        />
                        <InputError class="mt-2" :message="formEdit.errors.subject_id" />
                    </div>
                    <div>
                        <InputLabel for="name" value="Name" />

                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="formEdit.name"
                            required
                            placeholder="ABC..."
                        />
                        <InputError class="mt-2" :message="formEdit.errors.name" />
                    </div>
                    <div>
                        <InputLabel for="credits" value="Credits" />

                        <TextInput
                            id="credits"
                            type="number"
                            class="mt-1 block w-full"
                            v-model="formEdit.credits"
                            required
                            min="1"
                            step="1"
                            placeholder="0"
                        />
                        <InputError class="mt-2" :message="formEdit.errors.credits" />
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <InputLabel for="color_foreground" value="Text's color" />

                            <TextInput
                                id="color_foreground"
                                type="color"
                                class="mt-1 block w-full"
                                v-model="formEdit.color_foreground"
                                required
                                placeholder="#000000"
                            />
                            <InputError class="mt-2" :message="formEdit.errors.color_foreground" />
                        </div>
                        <div>
                            <InputLabel for="color_background" value="Background's color" />

                            <TextInput
                                id="color_background"
                                type="color"
                                class="mt-1 block w-full"
                                v-model="formEdit.color_background"
                                required
                                placeholder="#ffffff"
                            />
                            <InputError class="mt-2" :message="formEdit.errors.color_background" />
                        </div>
                    </div>
                    <div>
                        <InputLabel :value="formEdit.subject_id" :style="{
                            color: formEdit.color_foreground,
                            background: formEdit.color_background
                        }" class="py-3 text-center border border-gray-500" />
                    </div>
                    <div>
                        <InputLabel for="teacher_id" value="Teacher" />

                        <select id="teacher_id" v-model="formEdit.teacher_id" required class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                            <option v-for="teacher in teachers" :key="teacher.teacher_id" :value="teacher.teacher_id">
                                {{ teacher.name }}
                            </option>
                        </select>
                        <InputError class="mt-2" :message="formEdit.errors.teacher_id" />
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
                    Delete subject
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" @click="closeModalDelete">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-6">
                Do you really want to delete <b>"{{ deleteSubject && deleteSubject.name }}"</b>?
            </div>
            <div class="flex justify-end items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
                <PrimaryButton @click="closeModalDelete" type="button">No</PrimaryButton>
                <DangerButton @click="submitDelete" type="button">Yes</DangerButton>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>