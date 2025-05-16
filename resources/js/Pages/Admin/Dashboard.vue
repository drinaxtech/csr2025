<template>
    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Admin Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-2">Total Campaigns</h3>
                        <p class="text-2xl">{{ totalCampaigns }}</p>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-2">Total Donations</h3>
                        <p class="text-2xl">${{ parseFloat(totalDonations).toFixed(2) }}</p>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-2">Total Employees</h3>
                        <p class="text-2xl">{{ totalUsers }}</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Recent Donations</h3>
                    <div v-if="recentDonations && recentDonations.length > 0">
                        <ul>
                            <li v-for="donation in recentDonations" :key="donation.id" class="py-2 border-b">
                                User ID: {{ donation.user_id }} - Campaign ID: {{ donation.campaign_id }} - Amount: ${{ parseFloat(donation.amount).toFixed(2) }} - Date: {{ new Date(donation.donation_date).toLocaleDateString() }}
                            </li>
                        </ul>
                    </div>
                    <div v-else>
                        <p>No recent donations.</p>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    totalCampaigns: Number,
    totalDonations: Number,
    totalUsers: Number,
    recentDonations: Array,
});
</script>