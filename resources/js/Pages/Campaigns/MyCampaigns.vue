<template>
  <Head title="My Campaigns" />
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
            <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between">
              <div class="flex-grow">
                <label for="search" class="block text-gray-700 text-sm font-bold mb-2 sr-only">Search Campaigns</label>
                <input
                  type="text"
                  id="search"
                  v-model="searchValues"
                  placeholder="Search campaigns..."
                  class="border rounded px-3 py-2 w-64"
                />
              </div>
              <div class="ml-4 mt-4 sm:mt-0 flex flex-col sm:flex-row sm:items-center sm:space-x-2">
                <button @click="exportToXlsx" class="inline-flex items-center px-4 py-2 bg-green-300 border border-green-300 rounded-md font-semibold text-xs text-green-700 uppercase tracking-widest hover:bg-green-200 focus:outline-none focus:border-green-400 focus:ring focus:ring-green-200 disabled:opacity-25 transition ease-in-out duration-150">
                  Export to XLSX
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
                    {{ truncateText(item.description, 20) }}
                  </p>
                </template>

                <template #item-goal_amount="item">
                  <p class="text-gray-700 font-semibold">
                    ${{ parseFloat(item.goal_amount).toFixed(2) }}
                  </p>
                </template>

                <template #item-donations_sum_amount="item">
                  <p class="text-gray-700 font-semibold">
                    ${{  isNaN(parseFloat(item.donations_sum_amount)) ? '0.00' : parseFloat(item.donations_sum_amount).toFixed(2) }}
                  </p>
                </template>

                <template #item-id="item">
                  <Link :href="route('campaigns.donations', { campaign: item.id })" class="text-indigo-600 font-medium hover:underline">
                    Show Donations
                  </Link>
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
import * as XLSX from 'xlsx';
import Swal from 'sweetalert2';

const props = defineProps({
  campaigns: Array,
});

const searchValues = ref('');
const dataTable = ref(null);

const headers = ref([
  { text: 'Title',       value: 'title',         sortable: true },
  { text: 'Description',  value: 'description',  sortable: false },
  { text: 'Goal Amount',  value: 'goal_amount',  sortable: true, class: 'text-right' },
  { text: 'Raised',       value: 'donations_sum_amount',   sortable: true },
  { text: 'Donations',    value: 'id',           sortable: false },
  { text: 'Actions',      value: 'actions',      sortable: false, class: 'text-right' }
]);

const destroy = (id) => {
  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.isConfirmed) {
      axios.post(route('campaigns.close', id)).then(() => {
        router.visit(window.location.href);
        Swal.fire(
          'Deleted!',
          'Your campaign has been deleted.',
          'success'
        );
      });
    }
  });
};

const exportToXlsx = () => {
  const data = props.campaigns.map(item => { // Changed from campaigns to props.campaigns
    return {
      Title: item.title,
      Description: item.description,
      'Goal Amount': item.goal_amount,
      Raised: isNaN(parseFloat(item.donations_sum_amount)) ? '0.00' : parseFloat(item.donations_sum_amount).toFixed(2),
      Donations: item.id, // Or calculate the actual donations
    };
  });

  const worksheet = XLSX.utils.json_to_sheet(data);
  const workbook = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(workbook, worksheet, 'Campaigns');
  XLSX.writeFile(workbook, `campaigns_export_${new Date().toISOString().slice(0, 10)}.xlsx`);
};

const truncateText = (text, characterLimit) => {
  if (!text) return '<span class="text-gray-400 italic">No description</span>';
  if (text.length > characterLimit) {
    return text.substring(0, characterLimit) + '...';
  }
  return text;
};
</script>
