<template>
    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Mail Configuration
            </h2>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit">
                            <div>
                                <label for="mail_driver" class="block font-medium text-sm text-gray-700">Mail Driver</label>
                                <input type="text" id="mail_driver" v-model="form.mail_driver" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <div v-if="form.errors.driver" class="text-sm text-red-600">{{ form.errors.mail_driver }}</div>
                            </div>
                            <div class="mt-4">
                                <label for="mail_host" class="block font-medium text-sm text-gray-700">Mail Host</label>
                                <input type="text" id="mail_host" v-model="form.mail_host" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <div v-if="form.errors.host" class="text-sm text-red-600">{{ form.errors.mail_host }}</div>
                            </div>
                            <div class="mt-4">
                                <label for="mail_port" class="block font-medium text-sm text-gray-700">Mail Port</label>
                                <input type="number" id="mail_port" v-model="form.mail_port" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <div v-if="form.errors.port" class="text-sm text-red-600">{{ form.errors.mail_port }}</div>
                            </div>
                            <div class="mt-4">
                                <label for="mail_username" class="block font-medium text-sm text-gray-700">Mail Username</label>
                                <input type="text" id="mail_username" v-model="form.mail_username" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <div v-if="form.errors.username" class="text-sm text-red-600">{{ form.errors.mail_username }}</div>
                            </div>
                            <div class="mt-4">
                                <label for="mail_password" class="block font-medium text-sm text-gray-700">Mail Password</label>
                                <input type="password" id="mail_password" v-model="form.mail_password" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <div v-if="form.errors.password" class="text-sm text-red-600">{{ form.errors.mail_password }}</div>
                            </div>
                            <div class="mt-4">
                                <label for="mail_encryption" class="block font-medium text-sm text-gray-700">Mail Encryption</label>
                                <input type="text" id="mail_encryption" v-model="form.mail_encryption" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <div v-if="form.errors.encryption" class="text-sm text-red-600">{{ form.errors.mail_encryption }}</div>
                            </div>
                            <div class="mt-4">
                                <label for="mail_from_address" class="block font-medium text-sm text-gray-700">From Address</label>
                                <input type="email" id="mail_from_address" v-model="form.mail_from_address" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <div v-if="form.errors.mail_from_address" class="text-sm text-red-600">{{ form.errors.mail_from_address }}</div>
                            </div>

                            <div class="mt-6">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Save Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    mailConfig: Object,
});

const form = useForm({
    mail_driver: props.mailConfig?.mail_driver || 'smtp',
    mail_host: props.mailConfig?.mail_host || '',
    mail_port: props.mailConfig?.mail_port || '25',
    mail_username: props.mailConfig?.mail_username || '',
    mail_password: props.mailConfig?.mail_password || '',
    mail_encryption: props.mailConfig?.mail_encryption || 'tls',
    mail_from_address: props.mailConfig?.mail_from_address || '',
});

const submit = () => {
    axios.post('/admin/mail/settings', form.data()) // Use the actual URL
        .then(response => {
            if (response.data.success) {
                alert(response.data.success); // Or handle success message as needed
            } else if (response.data.errors) {
                form.setErrors(response.data.errors); // Handle validation errors
            }
        })
        .catch(error => {
            console.error("Error saving mail settings:", error);
            alert('An error occurred while saving settings.'); // Handle error
        });
};
</script>