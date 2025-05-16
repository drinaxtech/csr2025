<template>
  <AdminLayout>
    <template #header>
      <h2 class="font-semibold text-2xl text-gray-900 dark:text-white leading-tight">
        Dashboard
      </h2>
    </template>

    <div class="py-10">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
          <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
            <div class="px-6 py-4">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <svg class="h-6 w-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                </div>
                <div class="ml-4 w-0 flex-1">
                  <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                    Total Campaigns
                  </h3>
                  <div class="mt-2 flex items-center text-2xl font-bold text-gray-900 dark:text-white">
                    <span>{{ totalCampaigns }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
            <div class="px-6 py-4">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2-1.343-2-3-2z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 10V6m-2-2h4m-4 8v10m-4-3h4m-4-3l4 3m-4-3l-4-3" />
                  </svg>
                </div>
                <div class="ml-4 w-0 flex-1">
                  <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                    Total Donations
                  </h3>
                  <div class="mt-2 flex items-center text-2xl font-bold text-gray-900 dark:text-white">
                    <span>${{ parseFloat(totalDonations).toFixed(2) }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
            <div class="px-6 py-4">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                </div>
                <div class="ml-4 w-0 flex-1">
                  <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                    Total Employees
                  </h3>
                  <div class="mt-2 flex items-center text-2xl font-bold text-gray-900 dark:text-white">
                    <span>{{ totalUsers }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
          <div class="px-6 py-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
              Recent Donations
            </h3>
            <div v-if="recentDonations && recentDonations.length > 0" class="space-y-4">
              <div v-for="donation in recentDonations" :key="donation.id" class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-0">
                <div class="flex justify-between items-start">
                    <div>
                      <p class="text-sm text-gray-500 dark:text-gray-400">
                        User ID: <span class="font-medium text-gray-900 dark:text-white">{{ donation.user_id }}</span>
                      </p>
                      <p class="text-sm text-gray-500 dark:text-gray-400">
                        Campaign ID: <span class="font-medium text-gray-900 dark:text-white">{{ donation.campaign_id }}</span>
                      </p>
                    </div>
                    <div>
                      <p class="text-base font-semibold text-green-600 dark:text-green-400">
                        ${{ parseFloat(donation.amount).toFixed(2) }}
                      </p>
                      <p class="text-xs text-gray-500 dark:text-gray-400 text-right">
                        {{ new Date(donation.donated_at).toLocaleDateString() }}
                      </p>
                    </div>
                </div>
              </div>
            </div>
            <div v-else class="text-gray-500 dark:text-gray-400 py-4">
              No recent donations.
            </div>
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
