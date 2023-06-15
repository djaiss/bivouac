<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

defineProps({
  data: {
    type: Array,
  },
});
</script>

<template>
  <Head title="Profile" />

  <AuthenticatedLayout>
    <!-- header -->
    <div class="px-12 pt-6 max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6 mb-6">
      <div class="bg-white shadow sm:rounded-lg px-4 py-2 flex flex-col align-middle justify-center">
        <img src="/img/settings.png" class="w-40 h-40 mb-4" alt="settings">
        <p class="text-lg">{{ $t('Manage company settings') }}</p>
      </div>
    </div>

    <div class="pb-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 flex">
        <div class="space-y-6 w-full">
          <div class="bg-white shadow sm:rounded-lg">
            <ul>
              <li>
                <span>{{ $t('Add or remove users') }}</span>
              </li>
              <li class="flex items-center border-b border-gray-200 px-4 py-2 hover:bg-slate-50">
                <img src="/img/skyscraper.png" class="h-16 w-16 mr-2" alt="">
                <Link class="text-blue-700 hover:bg-blue-700 hover:text-white hover:rounded-sm underline">{{ $t('Update organization\'s name and description') }}</Link>
              </li>
              <li class="flex items-center border-b border-gray-200 px-4 py-2 hover:bg-slate-50">
                <img src="/img/genders.png" class="h-16 w-16 mr-2" alt="">
                <Link class="text-blue-700 underline">{{ $t('Manage genders and pronouns') }}</Link>
              </li>
              <li class="flex items-center px-4 py-2 hover:bg-slate-50 hover:rounded-b-lg">
                <img src="/img/cancel.png" class="h-16 w-16 mr-2" alt="">
                <Link class="text-blue-700 underline">{{ $t('Delete organization') }}</Link>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
