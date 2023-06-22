<script setup>
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
  data: {
    type: Array,
  },
});
</script>

<template>
  <Head :title="$t('Account settings')" />

  <AuthenticatedLayout>
    <!-- header -->
    <div class="px-12 pt-6 max-w-4xl mx-auto sm:px-6 lg:px-8 mb-6">
      <div class="bg-white shadow sm:rounded-lg px-4 flex justify-center">
        <div class="text-center flex items-center">
          <img src="/img/settings.png" class="w-24 h-24 mr-6" alt="settings" />
          <p class="text-lg font-bold">{{ $t('Account settings') }}</p>
        </div>
      </div>
    </div>

    <div class="pb-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 flex">
        <div class="space-y-6 w-full">
          <div class="bg-white shadow sm:rounded-lg">
            <ul>
              <li class="flex items-center border-b border-gray-200 px-4 py-2 hover:bg-slate-50 hover:rounded-t-lg">
                <img src="/img/users.png" class="h-16 w-16 mr-2" alt="" />
                <Link
                  :href="data.url.users"
                  class="text-blue-700 hover:bg-blue-700 hover:text-white hover:rounded-sm underline"
                  >{{ $t('Add or remove users') }}</Link
                >
              </li>
              <li class="flex items-center border-b border-gray-200 px-4 py-2 hover:bg-slate-50">
                <img src="/img/skyscraper.png" class="h-16 w-16 mr-2" alt="" />
                <Link class="text-blue-700 hover:bg-blue-700 hover:text-white hover:rounded-sm underline">{{
                  $t("Update organization's name and description")
                }}</Link>
              </li>
              <li class="flex items-center border-b border-gray-200 px-4 py-2 hover:bg-slate-50">
                <img src="/img/genders.png" class="h-16 w-16 mr-2" alt="" />
                <Link class="text-blue-700 hover:bg-blue-700 hover:text-white hover:rounded-sm underline">{{
                  $t('Manage genders and pronouns')
                }}</Link>
              </li>
              <li class="flex items-center px-4 py-2 hover:bg-slate-50 hover:rounded-b-lg">
                <img src="/img/cancel.png" class="h-16 w-16 mr-2" alt="" />
                <Link class="text-blue-700 hover:bg-blue-700 hover:text-white hover:rounded-sm underline">{{
                  $t('Delete organization')
                }}</Link>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
