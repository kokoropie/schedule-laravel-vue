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
    teachers: {
        type: Object,
        required: true
    },
    sort: {
        type: String,
        default: "ASC"
    },
    sort_by: {
        type: String,
        default: "teacher_id"
    },
});

const formNew = useForm({
    name: '',
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
    formNew.post(route('teacher.store'), {
        onSuccess: closeModalNew,
    });
};

const formEdit = useForm({
    name: '',
});
const isShowModalEdit = ref(false);
const editTeacher = ref(null);

const showModalEdit = (teacher) => {
    editTeacher.value = teacher;
    formEdit.name = teacher.name;
    isShowModalEdit.value = true;
}

const closeModalEdit = () => {
    isShowModalEdit.value = false;
    formEdit.reset();
    editTeacher.value = null;
}

const submitEdit = () => {
    formEdit.patch(route('teacher.update', editTeacher.value), {
        onSuccess: closeModalEdit,
    });
};

const isShowModalDelete = ref(false);
const deleteTeacher = ref(null);

const showModalDelete = (teacher) => {
    deleteTeacher.value = teacher;
    isShowModalDelete.value = true;
}

const closeModalDelete = () => {
    isShowModalDelete.value = false;
    deleteTeacher.value = null;
}

const submitDelete = () => {
    router.delete(route('teacher.destroy', deleteTeacher.value), {
        onSuccess: closeModalDelete,
    });
}

</script>
<template>
    <Head title="Teachers" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight flex justify-between">
                Teachers
                <PrimaryButton @click="showModalNew">New</PrimaryButton>
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="overflow-x-auto bg-white">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-700 text-white">
                                <th class="px-3 py-3 whitespace-nowrap w-auto">
                                    <Link :href="route('teacher.index', {
                                        sort: sort_by.toLowerCase()=='teacher_id' ? (sort.toUpperCase()=='DESC' ? 'ASC' : 'DESC') : 'ASC'
                                    })" preserve-scroll class="flex items-center space-x-1 justify-center">
                                        <span>#</span>
                                        <svg v-if="sort.toUpperCase()=='ASC' && sort_by.toLowerCase()=='teacher_id'" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="m7 14l5-5l5 5z"></path></svg>
                                        <svg v-if="sort.toUpperCase()=='DESC' && sort_by.toLowerCase()=='teacher_id'" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="m7 10l5 5l5-5z"></path></svg>
                                    </Link>
                                </th>
                                <th class="px-3 md:px-6 py-3 whitespace-nowrap text-left">
                                    <Link :href="route('teacher.index', {
                                        sort: sort_by.toLowerCase()=='name' ? (sort.toUpperCase()=='DESC' ? 'ASC' : 'DESC') : 'ASC',
                                        sort_by: 'name'
                                    })" preserve-scroll class="flex items-center space-x-1">
                                        <span>Name</span>
                                        <svg v-if="sort.toUpperCase()=='ASC' && sort_by.toLowerCase()=='name'" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="m7 14l5-5l5 5z"></path></svg>
                                        <svg v-if="sort.toUpperCase()=='DESC' && sort_by.toLowerCase()=='name'" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="m7 10l5 5l5-5z"></path></svg>
                                    </Link>
                                </th>
                                <th class="px-3 md:px-6 py-3 whitespace-nowrap w-auto">
                                    <Link :href="route('teacher.index', {
                                        sort: sort_by.toLowerCase()=='subjects_count' ? (sort.toUpperCase()=='DESC' ? 'ASC' : 'DESC') : 'ASC',
                                        sort_by: 'subjects_count'
                                    })" preserve-scroll class="flex items-center space-x-1">
                                        <svg v-if="sort.toUpperCase()=='ASC' && sort_by.toLowerCase()=='subjects_count'" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="m7 14l5-5l5 5z"></path></svg>
                                        <svg v-if="sort.toUpperCase()=='DESC' && sort_by.toLowerCase()=='subjects_count'" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="m7 10l5 5l5-5z"></path></svg>
                                        <span>Subjects</span>
                                    </Link>
                                </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="teacher in teachers" class="even:bg-gray-300 hover:bg-gray-500 hover:text-white">
                                <td class="px-3 py-3 whitespace-nowrap text-center w-auto">{{ teacher.teacher_id }}</td>
                                <td class="px-3 md:px-6 py-3 whitespace-nowrap w-full">{{ teacher.name }}</td>
                                <td class="px-3 md:px-6 py-3 whitespace-nowrap text-right w-auto">{{ teacher.subjects_count }}</td>
                                <td class="px-3 md:px-6 py-1 whitespace-nowrap text-center space-x-2">
                                    <PrimaryButton type="button" @click="showModalEdit(teacher)">Edit</PrimaryButton>
                                    <DangerButton type="button" @click="showModalDelete(teacher)">Delete</DangerButton>
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
                    New teacher
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" @click="closeModalNew">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-6">
                <form @submit.prevent="submitNew" id="form-new">
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
                    Edit teacher
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" @click="closeModalEdit">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-6">
                <form @submit.prevent="submitEdit" id="form-edit">
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
                    Delete teacher
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" @click="closeModalDelete">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-6">
                Do you really want to delete <b>"{{ deleteTeacher && deleteTeacher.name }}"</b>?
            </div>
            <div class="flex justify-end items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
                <PrimaryButton @click="closeModalDelete" type="button">No</PrimaryButton>
                <DangerButton @click="submitDelete" type="button">Yes</DangerButton>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>