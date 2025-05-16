<template>
    <div>
        <Head title="Admin Dashboard" />

        <div class="min-h-screen bg-gray-100">
            <nav class="bg-gray-800 text-white py-4">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                    <Link href="/admin/dashboard" class="font-semibold text-xl">
                        Admin Panel
                    </Link>
                    <div>
                        <Link :href="route('admin.dashboard')" class="px-4 py-2 hover:bg-gray-700 rounded">Dashboard</Link>
                        <Link :href="route('admin.parameters.index')" class="px-4 py-2 hover:bg-gray-700 rounded">Parameters</Link>
                        <Link :href="route('campaigns.index')" class="px-4 py-2 hover:bg-gray-700 rounded">Back to App</Link>
                        <form method="POST" :action="route('logout')" class="inline-block">
                            <input type="hidden" name="_token" :value="$page.props.csrf_token">
                            <button type="submit" class="px-4 py-2 hover:bg-gray-700 rounded">Log Out</button>
                        </form>
                    </div>
                </div>
            </nav>

            <header v-if="$slots.header" class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <main>
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>