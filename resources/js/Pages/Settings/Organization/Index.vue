<script setup>
import { ChevronRightIcon } from '@heroicons/vue/24/solid';
import { Head, Link, router } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { ref } from 'vue';

import DangerButton from '@/Components/DangerButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  data: {
    type: Array,
  },
});

const loadingState = ref(false);

const destroy = () => {
  loadingState.value = true;

  if (confirm(trans('Are you sure? This action cannot be undone.'))) {
    axios.delete(props.data.url.destroy).then((response) => {
      router.visit(response.data.data);
    });
  }
};
</script>

<template>
  <Head :title="$t('Manage offices')" />

  <AuthenticatedLayout>
    <!-- header -->
    <div class="mb-12">
      <div class="bg-white px-4 py-2 shadow">
        <div class="">
          <!-- Breadcrumb -->
          <nav class="flex py-3 text-gray-700">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
              <li class="inline-flex items-center">
                <Link :href="data.url.breadcrumb.home" class="text-sm text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                  {{ $t('Home') }}
                </Link>
              </li>
              <li>
                <div class="flex items-center">
                  <ChevronRightIcon class="mr-2 h-4 w-4 text-gray-400" />
                  <Link :href="data.url.breadcrumb.settings" class="text-sm text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                    {{ $t('Account settings') }}
                  </Link>
                </div>
              </li>
              <li>
                <div class="flex items-center">
                  <ChevronRightIcon class="h-4 w-4 text-gray-400" />
                  <span class="ml-1 text-sm text-gray-500 dark:text-gray-400 md:ml-2">{{ $t('Delete the organization') }}</span>
                </div>
              </li>
            </ol>
          </nav>
        </div>
      </div>
    </div>

    <div class="pb-12">
      <div class="mx-auto max-w-lg overflow-hidden rounded-lg bg-white shadow-md dark:bg-gray-800">
        <form @submit.prevent="destroy">
          <div class="relative border-b px-6 py-4">
            <div class="h-3w-32 relative mx-auto mb-4 w-32 overflow-hidden rounded-full">
              <img class="mx-auto block text-center" src="/img/invite.png" alt="logo" />
            </div>
            <h1 class="text-center text-lg font-bold">
              {{ $t('Do you want to the delete this organization?') }}
            </h1>
          </div>

          <div class="relative border-b px-6 py-4">
            <p>
              {{ $t('Are you sure? All information will be deleted immediately. This cannot be restored.') }}
            </p>
          </div>

          <!-- actions -->
          <div class="flex items-center justify-between bg-gray-50 px-6 py-4">
            <Link :href="data.url.breadcrumb.settings" class="text-sm font-medium text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
              {{ $t('Back') }}
            </Link>

            <DangerButton class="ml-3" @click="destroy">
              {{ $t('Delete') }}
            </DangerButton>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
