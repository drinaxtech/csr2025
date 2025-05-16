<template>
  <Head title="Success" />
  <DashboardLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Thank You for Your Donation!
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <h1 class="text-3xl font-semibold text-gray-800 mb-4">Your Support Makes a Difference!</h1>

            <p class="mb-4 text-lg text-gray-700">
              Thank you so much for your kind and generous donation to our campaign!
              Your contribution will directly help us to <span v-if="campaign">{{ campaign.impact_description || 'achieve our goals' }}.</span>
              <span v-else>achieve our goals.</span>
            </p>

            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
              <strong class="font-bold">Your Donation Details:</strong>
              <ul class="list-disc list-inside mt-2">
                <li><strong>Campaign:</strong> {{ campaign ? campaign.title : 'N/A' }}</li>
                <li><strong>Amount Donated:</strong> ${{ donation ? parseFloat(donation.amount).toFixed(2) : 'N/A' }}</li>
                <li><strong>Transaction ID:</strong> {{ transaction ? transaction.transaction_id : 'N/A' }}</li>
                <li><strong>Donation Date:</strong> {{ donation && donation.donated_at ? new Date(donation.donated_at).toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric' }) : 'N/A' }}</li>
              </ul>
              <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                </span>
            </div>

            <p class="mb-4 text-gray-700">
              We are deeply grateful for your support and believe that together, we can achieve our goals.
            </p>

            <h3 class="text-xl font-semibold text-gray-800 mb-2">What Happens Next?</h3>
            <ul class="list-disc list-inside mb-4 text-gray-700">
              <li>You will receive a confirmation email shortly with the details of your donation for your records.</li>
              <li v-if="campaign && campaign.follow_up_message">{{ campaign.follow_up_message }}</li>
              <li>Your contribution is being processed securely.</li>
            </ul>

            <h3 class="text-xl font-semibold text-gray-800 mb-2">Get Involved Further:</h3>
            <p class="mb-4 text-gray-700">
              There are more ways you can help us make a difference:
            </p>
            <ul class="list-disc list-inside mb-4 text-gray-700">
              <li>
                <strong>Share this campaign:</strong> Help us reach more people by sharing this campaign with your friends, family, and social networks.
                <div class="mt-2">
                  <a v-if="campaign && campaign.share_url_facebook" :href="campaign.share_url_facebook" target="_blank" class="inline-block mr-2 text-blue-500 hover:underline">Share on Facebook</a>
                  <a v-if="campaign && campaign.share_url_twitter" :href="campaign.share_url_twitter" target="_blank" class="inline-block mr-2 text-blue-500 hover:underline">Share on Twitter</a>
                  <span v-else>Share options will be available soon.</span>
                </div>
              </li>
              <li>
                <strong>Follow our updates:</strong> Stay informed about our progress and other initiatives by following us on social media or subscribing to our newsletter.
                <div class="mt-2">
                  <a v-if="organization && organization.facebook_url" :href="organization.facebook_url" target="_blank" class="inline-block mr-2 text-blue-500 hover:underline">Follow us on Facebook</a>
                  <a v-if="organization && organization.twitter_url" :href="organization.twitter_url" target="_blank" class="inline-block mr-2 text-blue-500 hover:underline">Follow us on Twitter</a>
                  <a v-if="organization && organization.newsletter_url" :href="organization.newsletter_url" target="_blank" class="inline-block mr-2 text-blue-500 hover:underline">Subscribe to our Newsletter</a>
                  <span v-else>Follow options will be available soon.</span>
                </div>
              </li>
              <li v-if="campaign && campaign.additional_ways_to_help">
                {{ campaign.additional_ways_to_help }}
              </li>
            </ul>

            <div class="mt-8">
              <Link :href="route('campaigns.index')" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                Back to All Campaigns
              </Link>
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
import { defineProps } from 'vue';

const page = usePage();

const { campaign, donation, transaction, organization } = defineProps({
  campaign: Object,
  donation: Object,
  transaction: Object,
});
</script>