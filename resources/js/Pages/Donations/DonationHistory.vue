<template>
    <DashboardLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Donation History
            </h2>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="mb-6 flex items-center justify-between">
                            <div class="flex-grow">
                                <label for="search" class="block text-gray-700 text-sm font-bold mb-2 sr-only">Search Donations</label>
                                <input
                                    type="text"
                                    id="search"
                                    v-model="searchValues"
                                    placeholder="Search donations..."
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                />
                            </div>
                            </div>

                        <div v-if="donations && donations.length > 0">
                            <EasyDataTable
                                ref="dataTable"
                                :headers="headers"
                                :items="donations"
                                :search-value="searchValues"
                                :pagination-settings="{ initialPage: 1, rowsPerPage: 10, showPerPageOptions: true, noResultsText: 'No donations found' }"
                            >
                                <template #item-id="item">
                                    {{ item.id }}
                                </template>
                                    
                                <template #item-created_at="item">
                                    {{ new Date(item.created_at).toLocaleDateString() }}
                                </template>
                                <template #item-amount="item">
                                    ${{ parseFloat(item.amount).toFixed(2) }}
                                </template>
                                <template #item-campaign="item">
                                    <Link :href="route('campaigns.show', item.campaign.id)" class="text-indigo-600 font-medium hover:underline">
                                        {{ item.campaign.title }}
                                    </Link>
                                </template>
                            </EasyDataTable>
                        </div>
                        <div v-else class="py-4 text-gray-600 italic">
                            No donation history available.
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
import { ref } from 'vue';
import EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';
// import axios from 'axios'; // Inertia handles the data

const props = defineProps({
    donations: Array,
});

const searchValues = ref('');
const dataTable = ref(null);

const headers = ref([
    { text: 'ID', value: 'ID', sortable: true },
    { text: 'Donation Date', value: 'created_at', sortable: true },
    { text: 'Amount', value: 'amount', sortable: true, class: 'text-right' },
    { text: 'Campaign Title', value: 'campaign', sortable: false }
]);

// Data for donations should be passed as a prop from the Laravel controller
// No need for explicit data fetching with axios here if Inertia is handling it.
</script>