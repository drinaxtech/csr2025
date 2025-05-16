<template>
    <DashboardLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create New Campaign
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit">
                            <div>
                                <label for="title" class="block font-medium text-sm text-gray-700">Title</label>
                                <input id="title" type="text" v-model="form.title" required class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />
                                <div v-if="form.errors.title" class="text-sm text-red-600">{{ form.errors.title }}</div>
                            </div>
                            <div class="mt-4">
                                <label for="description" class="block font-medium text-sm text-gray-700">Description</label>
                                <textarea id="description" v-model="form.description" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                <div v-if="form.errors.description" class="text-sm text-red-600">{{ form.errors.description }}</div>
                            </div>
                            <div class="mt-4">
                                <label for="goal_amount" class="block font-medium text-sm text-gray-700">Goal Amount</label>
                                <input id="goal_amount" type="number" v-model="form.goal_amount" required class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />
                                <div v-if="form.errors.goal_amount" class="text-sm text-red-600">{{ form.errors.goal_amount }}</div>
                            </div>
                            <div class="mt-4">
                                <label for="starts_at" class="block font-medium text-sm text-gray-700">Start Date</label>
                                <input id="starts_at" type="date" v-model="form.starts_at" required class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />
                                <div v-if="form.errors.starts_at" class="text-sm text-red-600">{{ form.errors.starts_at }}</div>
                            </div>
                            <div class="mt-4">
                                <label for="ends_at" class="block font-medium text-sm text-gray-700">End Date</label>
                                <input id="ends_at" type="date" v-model="form.ends_at" required class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />
                                <div v-if="form.errors.ends_at" class="text-sm text-red-600">{{ form.errors.ends_at }}</div>
                            </div>
                            <div class="flex items-center justify-end mt-4">
                                <Link :href="route('campaigns.index')" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Cancel
                                </Link>
                                <button type="submit" class="ml-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Create
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    description: null,
    goal_amount: 0,
    starts_at: '',
    ends_at: '',
});

const submit = () => {
    form.post(route('campaigns.store'));
};
</script>