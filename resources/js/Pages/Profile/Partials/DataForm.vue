<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue';

const isShowModal = ref(false);
const form = useForm({
    file: null,
});

const importData = () => {
    isShowModal.value = true;
    form.clearErrors();
    form.reset();
}

const submitForm = () => {
    form.post(route('profile.data.import'), {
        onSuccess (res) {
            closeModal();
        }
    });
}

const closeModal = () => {
    isShowModal.value = false;
    form.clearErrors();
    form.reset();
}

</script>
<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Your data</h2>

            <p class="mt-1 text-sm text-gray-600">
                Import/Export your data (teachers, subjects, schedules)
            </p>
        </header>
        <div class="flex flex-wrap justify-between xs:justify-start xs:space-x-2">
            <PrimaryButton @click="importData">Import</PrimaryButton>
            <a :href="route('profile.data.export')"><PrimaryButton>Export</PrimaryButton></a>
        </div>
        <Modal :show="isShowModal" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Import Data
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Import data will delete all your current data! Make sure you have a backup!
                </p>

                <form class="mt-6" @submit.prevent="submitForm" id="import-form">
                    <InputLabel for="file" value="Choose file" class="sr-only" />

                    <input
                        id="file"
                        @input="form.file = $event.target.files[0]"
                        type="file"
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                        accept=".json"
                    />

                    <InputError :message="form.errors.file" class="mt-2" />
                </form>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

                    <PrimaryButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        form="import-form"
                    >
                        Import
                    </PrimaryButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
