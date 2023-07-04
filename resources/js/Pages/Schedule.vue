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
    schedules: {
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
    }
});

const paddingNumber = (num = 0, length = 1) => {
    if (`${num}`.length < length) {
        num = "0".repeat(length - `${num}`.length) + `${num}`;
    }
    return `${num}`;
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
}

const closeModalNew = () => {
    isShowModalNew.value = false;
    formNew.reset();
}

const submitNew = () => {
    formNew.post(route('schedule.store'), {
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
}

const closeModalEdit = () => {
    isShowModalEdit.value = false;
    formEdit.reset();
    editSchedule.value = null;
}

const submitEdit = () => {
    formEdit.patch(route('schedule.update', editSchedule.value), {
        onSuccess: closeModalEdit,
    });
};

const isShowModalDelete = ref(false);
const deleteSchedule = ref(null);

const showModalDelete = (schedule) => {
    deleteSchedule.value = schedule;
    isShowModalDelete.value = true;
}

const closeModalDelete = () => {
    isShowModalDelete.value = false;
    deleteSchedule.value = null;
}

const submitDelete = () => {
    router.delete(route('schedule.destroy', deleteSchedule.value), {
        onSuccess: closeModalDelete,
    });
}

</script>
<template>
    <Head title="Schedules" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between">
                Schedules
                <PrimaryButton @click="showModalNew()">New</PrimaryButton>
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="overflow-x-auto bg-white">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-700 text-white">
                                <th class="px-3 py-3 whitespace-nowrap w-auto">#</th>
                                <th class="px-3 md:px-6 py-3 whitespace-nowrap text-left">Subject</th>
                                <th class="px-3 md:px-6 py-3 whitespace-nowrap text-right w-auto">Start</th>
                                <th class="px-3 md:px-6 py-3 whitespace-nowrap text-right w-auto">End</th>
                                <th class="px-3 md:px-6 py-3 whitespace-nowrap text-right w-auto">From</th>
                                <th class="px-3 md:px-6 py-3 whitespace-nowrap text-right w-auto">To</th>
                                <th class="px-3 md:px-6 py-3 whitespace-nowrap w-auto">Type</th>
                                <th class="px-3 md:px-6 py-3 whitespace-nowrap text-left w-auto">Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="schedule in schedules" class="even:bg-gray-300 hover:bg-gray-500 hover:text-white">
                                <td class="px-3 py-3 whitespace-nowrap text-center w-auto">{{ schedule.schedule_id }}</td>
                                <td class="px-3 md:px-6 py-3 whitespace-nowrap w-full">{{ schedule.subject_id }} - {{ schedule.subject.name }}</td>
                                <td class="px-3 md:px-6 py-3 whitespace-nowrap text-right w-auto">{{ paddingNumber(timeOfEachClassPeriod[schedule.start].start[0], 2) }}:{{ paddingNumber(timeOfEachClassPeriod[schedule.start].start[1], 2) }}</td>
                                <td class="px-3 md:px-6 py-3 whitespace-nowrap text-right w-auto">{{ paddingNumber(timeOfEachClassPeriod[schedule.end].end[0], 2) }}:{{ paddingNumber(timeOfEachClassPeriod[schedule.end].end[1], 2) }}</td>
                                <td class="px-3 md:px-6 py-3 whitespace-nowrap text-right w-auto">{{ schedule.from }}</td>
                                <td class="px-3 md:px-6 py-3 whitespace-nowrap text-right w-auto">{{ schedule.to }}</td>
                                <td class="px-3 md:px-6 py-3 whitespace-nowrap text-center w-auto">{{ schedule.type }}</td>
                                <td class="px-3 md:px-6 py-3 whitespace-nowrap text-left w-auto">{{ dateOfWeek[schedule.dateOfWeek] }}</td>
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
        <Modal :show="isShowModalNew" @close="closeModalNew">
            <div class="flex items-start justify-between p-4 border-b rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">
                    New schedule
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" @click="closeModalNew">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-6">
                <form @submit.prevent="submitNew" id="form-new" class="space-y-6">
                    <div>
                        <InputLabel for="subject_id" value="Teacher" />

                        <select id="subject_id" v-model="formNew.subject_id" required class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
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

                            <TextInput
                                id="start"
                                type="number"
                                class="mt-1 block w-full"
                                v-model="formNew.start"
                                required
                                min="1"
                                step="1"
                                :max="numOfClassPeriodsPerDay"
                                placeholder="1"
                            />
                            <InputError class="mt-2" :message="formNew.errors.start" />
                        </div>
                        <div>
                            <InputLabel for="end" value="End" />

                            <TextInput
                                id="end"
                                type="number"
                                class="mt-1 block w-full"
                                v-model="formNew.end"
                                required
                                min="1"
                                step="1"
                                :max="numOfClassPeriodsPerDay"
                                placeholder="1"
                            />
                            <InputError class="mt-2" :message="formNew.errors.end" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <InputLabel for="from" value="From" />

                            <TextInput
                                id="from"
                                type="date"
                                class="mt-1 block w-full"
                                v-model="formNew.from"
                                required
                            />
                            <InputError class="mt-2" :message="formNew.errors.from" />
                        </div>
                        <div>
                            <InputLabel for="to" value="To" />

                            <TextInput
                                id="to"
                                type="date"
                                class="mt-1 block w-full"
                                v-model="formNew.to"
                                required
                            />
                            <InputError class="mt-2" :message="formNew.errors.to" />
                        </div>
                    </div>
                    <div>
                        <InputLabel for="type" value="Type" />

                        <div class="mt-1 w-full grid grid-cols-2 gap-2">
                            <div class="flex items-center space-x-2">
                                <input
                                    id="type_offline"
                                    type="radio"
                                    class="rounded-full"
                                    v-model="formNew.type"
                                    value="OFFLINE"
                                />
                                <InputLabel for="type_offline" value="OFFLINE" />
                            </div>

                            <div class="flex items-center space-x-2">
                                <input
                                    id="type_online"
                                    type="radio"
                                    class="rounded-full"
                                    v-model="formNew.type"
                                    value="ONLINE"
                                />
                                <InputLabel for="type_online" value="ONLINE" />
                            </div>
                        </div>
                        
                        <InputError class="mt-2" :message="formNew.errors.type" />
                    </div>
                    <div>
                        <InputLabel for="dateOfWeek" value="Date" />

                        <select id="dateOfWeek" v-model="formNew.dateOfWeek" required class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                            <option v-for="(dateName, dateValue) in dateOfWeek" :key="dateValue" :value="dateValue">{{ dateName }}</option>
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
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" @click="closeModalEdit">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-6">
                <form @submit.prevent="submitEdit" id="form-edit" class="space-y-6">
                    <div>
                        <InputLabel for="subject_id" value="Teacher" />

                        <select id="subject_id" v-model="formEdit.subject_id" required class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
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

                            <TextInput
                                id="start"
                                type="number"
                                class="mt-1 block w-full"
                                v-model="formEdit.start"
                                required
                                min="1"
                                step="1"
                                :max="numOfClassPeriodsPerDay"
                                placeholder="1"
                            />
                            <InputError class="mt-2" :message="formEdit.errors.start" />
                        </div>
                        <div>
                            <InputLabel for="end" value="End" />

                            <TextInput
                                id="end"
                                type="number"
                                class="mt-1 block w-full"
                                v-model="formEdit.end"
                                required
                                min="1"
                                step="1"
                                :max="numOfClassPeriodsPerDay"
                                placeholder="1"
                            />
                            <InputError class="mt-2" :message="formEdit.errors.end" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <InputLabel for="from" value="From" />

                            <TextInput
                                id="from"
                                type="date"
                                class="mt-1 block w-full"
                                v-model="formEdit.from"
                                required
                            />
                            <InputError class="mt-2" :message="formEdit.errors.from" />
                        </div>
                        <div>
                            <InputLabel for="to" value="To" />

                            <TextInput
                                id="to"
                                type="date"
                                class="mt-1 block w-full"
                                v-model="formEdit.to"
                                required
                            />
                            <InputError class="mt-2" :message="formEdit.errors.to" />
                        </div>
                    </div>
                    <div>
                        <InputLabel for="type" value="Type" />

                        <div class="mt-1 w-full grid grid-cols-2 gap-2">
                            <div class="flex items-center space-x-2">
                                <input
                                    id="type_offline"
                                    type="radio"
                                    class="rounded-full"
                                    v-model="formEdit.type"
                                    value="OFFLINE"
                                />
                                <InputLabel for="type_offline" value="OFFLINE" />
                            </div>

                            <div class="flex items-center space-x-2">
                                <input
                                    id="type_online"
                                    type="radio"
                                    class="rounded-full"
                                    v-model="formEdit.type"
                                    value="ONLINE"
                                />
                                <InputLabel for="type_online" value="ONLINE" />
                            </div>
                        </div>
                        
                        <InputError class="mt-2" :message="formEdit.errors.type" />
                    </div>
                    <div>
                        <InputLabel for="dateOfWeek" value="Date" />

                        <select id="dateOfWeek" v-model="formEdit.dateOfWeek" required class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                            <option v-for="(dateName, dateValue) in dateOfWeek" :key="dateValue" :value="dateValue">{{ dateName }}</option>
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
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" @click="closeModalDelete">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-6">
                Do you really want to delete <b>"{{ deleteSchedule && deleteSchedule.subject.name }}" ({{ deleteSchedule && deleteSchedule.from }} - {{ deleteSchedule && deleteSchedule.to }})</b>?
            </div>
            <div class="flex justify-end items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
                <PrimaryButton @click="closeModalDelete" type="button">No</PrimaryButton>
                <DangerButton @click="submitDelete" type="button">Yes</DangerButton>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>