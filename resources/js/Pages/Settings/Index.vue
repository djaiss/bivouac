<script setup>
import { Link, Head, usePage } from '@inertiajs/vue3';

import { computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
  data: {
    type: Array,
  },
});

const page = usePage();
const user = computed(() => page.props.auth.user);
</script>

<template>
  <Head :title="$t('Account settings')" />

  <AuthenticatedLayout>
    <!-- header -->
    <div class="mx-auto mb-6 max-w-4xl px-12 pt-6 sm:px-6 lg:px-8">
      <div class="flex justify-center bg-white px-4 shadow sm:rounded-lg">
        <div class="flex items-center text-center">
          <img src="/img/settings.png" class="mr-6 h-24 w-24" alt="settings" />
          <p class="text-lg font-bold">{{ $t('Account settings') }}</p>
        </div>
      </div>
    </div>

    <div class="pb-12">
      <div class="mx-auto flex max-w-4xl sm:px-6 lg:px-8">
        <div class="w-full space-y-6">
          <div class="bg-white shadow sm:rounded-lg">
            <ul>
              <li class="flex items-center border-b border-gray-200 px-4 py-2 hover:rounded-t-lg hover:bg-slate-50">
                <span class="mr-4 rounded border border-yellow-400 bg-yellow-100 px-1">👥</span>
                <Link :href="data.url.users" class="text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                  {{ $t('Add or remove users') }}
                </Link>
              </li>
              <!-- <li class="flex items-center border-b border-gray-200 px-4 py-2 hover:bg-slate-50">
                <span class="mr-4 rounded border border-yellow-400 bg-yellow-100 px-1">👮‍♂️</span>
                <Link class="text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                  {{ $t('Manage roles') }}
                </Link>
              </li>
              <li class="flex items-center border-b border-gray-200 px-4 py-2 hover:bg-slate-50">
                <span class="mr-4 rounded border border-yellow-400 bg-yellow-100 px-1">🏢</span>
                <Link :href="data.url.offices" class="text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                  {{ $t('Manage offices') }}
                </Link>
              </li> -->
              <li v-if="data.upgradable" class="flex items-center border-b border-gray-200 px-4 py-2 hover:rounded-t-lg hover:bg-slate-50">
                <span class="mr-4 rounded border border-yellow-400 bg-yellow-100 px-1">🤑</span>
                <Link :href="data.url.upgrade" class="text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                  {{ $t('Unlock account') }}
                </Link>
              </li>
              <li v-if="user.permissions === 'account_manager'" class="flex items-center px-4 py-2 hover:rounded-b-lg hover:bg-slate-50">
                <span class="mr-4 rounded border border-yellow-400 bg-yellow-100 px-1">🗑️</span>
                <Link :href="data.url.organization" class="text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                  {{ $t('Delete organization') }}
                </Link>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
