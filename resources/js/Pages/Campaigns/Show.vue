<template>
    <Head :title="campaign.title" />
    <DashboardLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ campaign.title }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold mb-2">{{ campaign.title }}</h3>
                        <p class="text-gray-700 mb-4">{{ campaign.description || 'No description provided.' }}</p>
                        <p class="text-gray-700 mb-2">Goal Amount: ${{ parseFloat(campaign.goal_amount).toFixed(2) }}</p>
                        <p class="text-green-600 mb-2">Raised: ${{ parseFloat(campaign.donations_sum_amount || 0).toFixed(2) }}</p>
                        <p class="text-gray-700 mb-2">Start Date: {{ new Date(campaign.starts_at).toLocaleDateString() }}</p>
                        <p class="text-gray-700 mb-4">End Date: {{ new Date(campaign.ends_at).toLocaleDateString() }}</p>

                        <div class="mb-4 flex items-center">
                            <Link
                                v-if="auth.user && auth.user.id !== campaign.created_by && parseFloat(campaign.donations_sum_amount || 0) < parseFloat(campaign.goal_amount)"
                                :href="route('donations.create', campaign.id)"
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-300 disabled:opacity-25 transition ease-in-out duration-150"
                            >
                                Donate Now
                            </Link>
                            <span
                                v-if="parseFloat(campaign.donations_sum_amount || 0) >= parseFloat(campaign.goal_amount)"
                                class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-not-allowed"
                            >
                                Goal Reached
                            </span>
                            <Link
                                v-if="auth.user && auth.user.id === campaign.created_by"
                                :href="route('campaigns.edit', campaign.id)"
                                class="inline-flex items-center px-4 py-2 bg-yellow-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-300 active:bg-yellow-500 focus:outline-none focus:border-yellow-500 focus:ring focus:ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150 ml-2"
                            >
                                Edit Campaign
                            </Link>
                            <Link
                                v-if="auth.user && auth.user.id === campaign.created_by"
                                :href="route('campaigns.donations.index', campaign.id)"
                                class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-400 active:bg-blue-600 focus:outline-none focus:border-blue-600 focus:ring focus:ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 ml-2"
                            >
                                View Donations
                            </Link>
                        </div>

                        <div class="mt-6">
                            <Link :href="route('campaigns.index')" class="text-indigo-600 hover:underline">Back to All Campaigns</Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    campaign: Object,
});

const page = usePage();
const auth = computed(() => page.props.auth);
</script>