<template>
    <DashboardLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Fundraising Campaigns
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="mb-4 flex items-center justify-between">
                            <Link :href="route('campaigns.create')" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Create New Campaign
                            </Link>
                            <div class="ml-4 flex items-center">
                                <input
                                    type="text"
                                    v-model="search"
                                    placeholder="Search campaigns..."
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mr-2"
                                />
                                <button
                                    @click="submitSearch"
                                    class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-400 active:bg-gray-600 focus:outline-none focus:border-gray-600 focus:ring focus:ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150"
                                >
                                    Search
                                </button>
                            </div>
                        </div>

                        <div v-if="campaignsData.length > 0">
                            <div v-for="campaign in campaignsData" :key="campaign.id" class="mb-4 p-4 border rounded-md">
                                <Link :href="route('campaigns.show', campaign.id)" class="text-lg font-semibold hover:underline">
                                    {{ campaign.title }}
                                </Link>
                                <p class="text-gray-600 text-sm mt-1">{{ campaign.description ? campaign.description.substring(0, 100) + '...' : 'No description provided.' }}</p>
                                <p class="text-gray-700 mt-2">Goal: ${{ parseFloat(campaign.goal_amount).toFixed(2) }}</p>
                                <p class="text-green-600 mt-1">Raised: ${{ parseFloat(campaign.donations_sum_amount || 0).toFixed(2) }}</p>
                                <div class="flex justify-end mt-2">
                                    <Link :href="route('donations.create', campaign.id)" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        Donate Now
                                    </Link>
                                </div>
                            </div>

                            <div v-if="loading" class="text-center py-4">Loading more campaigns...</div>
                            <div v-if="!nextPageUrl && campaignsData.length > 0" class="text-center py-4">No more campaigns to load.</div>
                        </div>
                        <div v-else>
                            <p>No campaigns created by others yet.</p>
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
import { ref, watch, onMounted, onUnmounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    campaigns: Object,
    search: String,
});

const campaignsData = ref([...props.campaigns.data]);
const nextPageUrl = ref(props.campaigns.next_page_url);
const search = ref(props.search || '');
const loading = ref(false);

const submitSearch = () => {
    router.get(route('campaigns.index'), { search: search.value }, { preserveState: true });
};

watch(search, (newSearch) => {
    if (newSearch === '') {
        router.get(route('campaigns.index'), {}, { preserveState: true, replace: true });
    }
});

const loadMore = () => {
    if (loading.value || !nextPageUrl.value) {
        return;
    }
    loading.value = true;
    axios.get(nextPageUrl.value)
        .then(response => {
            campaignsData.value = [...campaignsData.value, ...response.data.data];
            nextPageUrl.value = response.data.next_page_url;
        })
        .catch(error => {
            console.error("Error loading more campaigns:", error);
        })
        .finally(() => {
            loading.value = false;
        });
};

const handleScroll = () => {
    const scrollPosition = window.scrollY;
    const windowHeight = window.innerHeight;
    const bodyHeight = document.body.offsetHeight;
    const scrollThreshold = 200; // Load more when within 200px of the bottom

    if (scrollPosition + windowHeight >= bodyHeight - scrollThreshold && !loading.value && nextPageUrl.value) {
        loadMore();
    }
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>
