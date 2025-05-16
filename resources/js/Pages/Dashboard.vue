<template>
    <Head title="Dashboard" />
    <DashboardLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        Welcome {{ $page.props.auth.user.name }}!

                        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div class="bg-gray-100 rounded-md p-4">
                                <h3 class="font-semibold text-lg text-gray-700">Active Campaigns</h3>
                                <p class="text-2xl font-bold text-indigo-600">{{ activeCampaignsCount }}</p>
                            </div>

                            <div class="bg-gray-100 rounded-md p-4">
                                <h3 class="font-semibold text-lg text-gray-700">Campaigns Reached Goal</h3>
                                <p class="text-2xl font-bold text-green-600">{{ reachedGoalCampaignsCount }}</p>
                            </div>

                            </div>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { defineProps } from 'vue';

const props = defineProps({
    activeCampaignsCount: Number,
    reachedGoalCampaignsCount: Number,
});
</script>