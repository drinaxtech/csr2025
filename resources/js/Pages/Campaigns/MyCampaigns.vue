<template>
    <DashboardLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    My Campaigns
                </h2>
                <Link :href="route('campaigns.create')" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Create New Campaign
                </Link>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="mb-6 flex items-center justify-between">
                            <div class="flex-grow">
                                <label for="search" class="block text-gray-700 text-sm font-bold mb-2 sr-only">Search Campaigns</label>
                                <input
                                    type="text"
                                    id="search"
                                    v-model="searchValues"
                                    placeholder="Search campaigns..."
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                />
                            </div>
                            <div class="ml-4">
                                <button @click="$refs.dataTable.exportCSV()" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 focus:outline-none focus:border-gray-400 focus:ring focus:ring-gray-200 disabled:opacity-25 transition ease-in-out duration-150">
                                    Export to CSV
                                </button>
                            </div>
                        </div>

                        <div v-if="campaigns.length > 0">
                            <EasyDataTable
                                ref="dataTable"
                                :headers="headers"
                                :items="campaigns"
                                :search-value="searchValues"
                                :pagination-settings="{ initialPage: 1, rowsPerPage: 10, showPerPageOptions: true, noResultsText: 'No campaigns found' }"
                            >
                                <template #item-title="item">
                                    {{ item.title }}
                                </template>

                                <template #item-description="item">
                                    <p class="text-gray-600 text-sm">
                                        {{ item.description ? item.description.substring(0, 100) + '...' : '<span class="text-gray-400 italic">No description</span>' }}
                                    </p>
                                </template>

                                <template #item-goal_amount="item">
                                    <p class="text-gray-700 font-semibold">
                                        ${{ parseFloat(item.goal_amount).toFixed(2) }}
                                    </p>
                                </template>

                                <template #item-actions="item">
                                    <div class="flex justify-end space-x-2">
                                        <Link :href="route('campaigns.edit', item.id)" class="inline-flex items-center px-3 py-2 bg-yellow-400 text-white text-xs rounded-md hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-yellow-300">
                                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15.172 10 13.343 11.828 11.515z" />
                                            </svg>
                                            Edit
                                        </Link>
                                        <button @click="destroy(item.id)" class="inline-flex items-center px-3 py-2 bg-red-600 text-white text-xs rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Delete
                                        </button>
                                    </div>
                                </template>
                            </EasyDataTable>
                        </div>
                        <div v-else class="py-4 text-gray-600 italic">
                            You haven't created any campaigns yet.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';
import axios from 'axios';

const props = defineProps({
    campaigns: Array,
});

const searchValues = ref('');
const dataTable = ref(null); // Ref for the EasyDataTable component

const headers = ref([
    { text: 'Title',         value: 'title',         sortable: true },
    { text: 'Description',   value: 'description',   sortable: false },
    { text: 'Goal Amount',   value: 'goal_amount',   sortable: true, class: 'text-right' },
    { text: 'Actions',       value: 'actions',       sortable: false, class: 'text-right' },
]);

const destroy = (id) => {
  if (confirm('Are you sure you want to delete this campaign?')) {
    axios.post(route('campaigns.close', id)).then(() => {
       router.visit(window.location.href);
    });
  }
};
</script>