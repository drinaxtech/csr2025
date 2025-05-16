<template>
    <div class="min-h-screen bg-gray-50">
        <nav class="bg-white shadow-sm border-b border-gray-200 z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <Link :href="route('dashboard')" class="flex-shrink-0">
                            <svg class="h-8 w-auto text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.812 5c-1.248 0-2.325.555-2.988 1.318l-.656-.656a4.5 4.5 0 016.787-6.787l.656.656c.663.763 1.74 1.318 2.988 1.318 1.434 0 3.02-.477 4.188-1.253l.656.656a4.5 4.5 0 01-6.787 6.787l-.656-.656c-.663-.763-1.74-1.318-2.988-1.318-1.434 0-3.02.477-4.188 1.253v10h.752c1.248 0 2.325-.555 2.988-1.318l.656.656a4.5 4.5 0 01-6.787 6.787l-.656-.656c-.663.763-1.74-1.318-2.988-1.318-1.248 0-2.325-.555-2.988-1.318l-.656.656a4.5 4.5 0 016.787-6.787l.656-.656c.663-.763 1.74-1.318 2.988-1.318 1.434 0 3.02.477 4.188 1.253v-13" />
                            </svg>
                        </Link>
                    </div>
                    <div class="flex items-center ml-6">
                        <div class="relative mr-4">
                            <button @click="isNotificationsOpen = !isNotificationsOpen" class="bg-white rounded-full p-2 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out" aria-expanded="false" aria-haspopup="true">
                                <span class="sr-only">Notifications</span>
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-6-6C5.928 5 4 6.979 4 9.5V17l2 2v-1.581A2 2 0 005 15.75h14a2 2 0 00-1.819-3.191L15 17z" /></svg>
                                <span v-if="hasNotifications" class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">{{ notificationCount }}</span>
                            </button>
                            <div v-if="isNotificationsOpen" class="absolute right-0 mt-2 w-64 bg-white rounded-md shadow-lg origin-top-right">
                                <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Notification 1</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Notification 2</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Notification 3</a>
                                    <div class="border-t border-gray-100">
                                        <a href="#" class="block px-4 py-2 text-sm text-indigo-600 hover:bg-gray-100" role="menuitem">See all notifications</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="relative">
                            <button @click="isUserMenuOpen = !isUserMenuOpen" class="bg-white rounded-full flex items-center text-gray-800 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                <svg class="h-8 w-8 rounded-full text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <span class="ml-2 text-sm font-medium">{{ auth.user.name }}</span>
                                <svg class="ml-1 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </button>
                            <div v-if="isUserMenuOpen" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg origin-top-right">
                                <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button">
                                    <Link :href="route('profile.edit')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                        Your Profile
                                    </Link>
                                    <Link :href="route('logout')" method="post" as="button" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                        Sign out
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <aside class="sm:block hidden w-64 bg-white border-r border-gray-200 fixed top-0 left-0 h-full pt-16">
            <div class="py-6 px-3">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Menu</h3>
                <nav class="space-y-1">
                    <Link :href="route('admin.dashboard')" :class="['group flex items-center px-3 py-2 text-sm font-medium rounded-md focus:outline-none transition duration-150 ease-in-out', route().current('dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900']">
                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0l2-2m-2 2v9a1 1 0 011 1h3m-6 0l2-2m-2 2v9a1 1 0 011 1h3" /></svg>
                        Dashboard
                    </Link>
                    <Link :href="route('admin.mail.settings.index')" :class="['group flex items-center px-3 py-2 text-sm font-medium rounded-md focus:outline-none transition duration-150 ease-in-out', route().current('campaigns.index') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900']">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail">
    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
    <polyline points="22 6 12 13 2 6"></polyline>
</svg>


                        Settings
                    </Link>

                    </nav>
            </div>
        </aside>

        <div class="sm:ml-64 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <slot />
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link, usePage, router } from '@inertiajs/vue3';
import { computed, ref, onMounted, onUnmounted } from 'vue';

const page = usePage();
const auth = computed(() => page.props.auth);

const isNotificationsOpen = ref(false);
const isUserMenuOpen = ref(false);
const hasNotifications = ref(true); // Example state for notifications
const notificationCount = ref(5); // Example notification count

const closeDropdownsOnOutsideClick = (event) => {
    if (isUserMenuOpen.value && !event.target.closest('.relative > button[id="user-menu-button"]')) {
        isUserMenuOpen.value = false;
    }
    if (isNotificationsOpen.value && !event.target.closest('.relative > button:first-child')) {
        isNotificationsOpen.value = false;
    }
};

onMounted(() => {
    window.addEventListener('click', closeDropdownsOnOutsideClick);
});

onUnmounted(() => {
    window.removeEventListener('click', closeDropdownsOnOutsideClick);
});
</script>

<style scoped>
/* Example styles for dropdowns */
.origin-top-right {
    transform-origin: top right;
}
</style>