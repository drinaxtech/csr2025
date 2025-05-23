<template>
  <Head title="Donation List" />
  <DashboardLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Donations for "{{ campaign.title }}"
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <div v-if="donations && donations.length > 0">
              <h3 class="text-lg font-semibold mb-4">List of Donations</h3>

              <div class="mb-4 text-right">
                <input
                  v-model="searchValues"
                  type="text"
                  placeholder="Search donations..."
                  class="border rounded px-3 py-2 w-64"
                />
              </div>

              <EasyDataTable
                :headers="headers"
                :items="filteredDonations"
                :search-value="searchValues"
                :buttons-text="{ export: 'Export to CSV' }"
                @export-csv="exportCsv"
              />
            </div>
            <div v-else>
              <p>No donations have been made to this campaign yet.</p>
            </div>
            <div class="mt-6">
              <Link :href="route('my-campaigns')" class="text-indigo-600 hover:underline">Back to Campaign Details</Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';

const props = defineProps({
  campaign: Object,
  donations: Array,
});

const headers = ref([
  { value: 'id', text: 'ID', sortable: true },
  { value: 'user.name', text: 'Name', sortable: true },
  { value: 'user_id', text: 'User ID', sortable: true },
  { value: 'amount', text: 'Amount (USD)', sortable: true, formatter: v => parseFloat(v).toFixed(2) },
  { value: 'donated_at', text: 'Donation Date', sortable: true, formatter: v => new Date(v).toLocaleString() },
]);

const searchValues = ref('');

const filteredDonations = computed(() => {
  const searchTerm = searchValues.value.toLowerCase();
  if (!searchTerm) {
    return props.donations;
  }

  return props.donations.filter(donation => {
    const userName = donation.user?.name?.toLowerCase() || '';
    const donationDate = donation.donated_at ? new Date(donation.donated_at).toLocaleString().toLowerCase() : '';
    return (
      String(donation.id).toLowerCase().includes(searchTerm) ||
      userName.includes(searchTerm) ||
      String(donation.user_id).toLowerCase().includes(searchTerm) ||
      String(donation.amount).toLowerCase().includes(searchTerm) ||
      donationDate.includes(searchTerm)
    );
  });
});

const exportCsv = (items, headers) => {
  if (!items || items.length === 0) {
    return;
  }

  const csvRows = [];

  const headerRow = headers.map(header => header.text).join(',');
  csvRows.push(headerRow);

  items.forEach(item => {
    const dataRow = headers.map(header => {
      let value = item[header.value];
      if (header.formatter) {
        value = header.formatter(value);
      }
      return value;
    }).join(',');
    csvRows.push(dataRow);
  });

  const csvData = csvRows.join('\n');
  const blob = new Blob([csvData], { type: 'text/csv' });
  const url = window.URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.setAttribute('href', url);
  a.setAttribute('download', `donations_${props.campaign.title.replace(/\s+/g, '_')}.csv`);
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
  window.URL.revokeObjectURL(url);
};
</script>
