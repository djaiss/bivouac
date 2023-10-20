<script setup>
import { Head, Link } from '@inertiajs/vue3';

import { usePage } from '@inertiajs/vue3';
import Avatar from '@/Components/Avatar.vue';
import PrimaryLinkButton from '@/Components/PrimaryLinkButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const page = usePage();

const props = defineProps({
  data: {
    type: Array,
  },
});
</script>

<template>
  <Head :title="$t('1:1s')" />

  <AuthenticatedLayout>
    <div class="mx-auto mb-4 mt-10 flex max-w-2xl justify-end sm:px-6 lg:px-8">
      <!-- menu -->
      <div>
        <PrimaryLinkButton :href="data.url.create">{{ $t('Create 1:1') }}</PrimaryLinkButton>
      </div>
    </div>

    <div class="pb-12">
      <div class="mx-auto flex max-w-2xl sm:px-6 lg:px-8">
        <div class="w-full">
          <div class="bg-white shadow sm:rounded-lg">
            <!-- list of one on ones -->
            <ul v-if="props.data.one_on_ones.length > 0" class="rounded-lg border bg-white dark:bg-gray-900">
              <li v-for="oneOnOne in props.data.one_on_ones" :key="oneOnOne.id" class="border-b border-gray-200 px-3 py-2 last:border-0 hover:bg-slate-50 first:hover:rounded-t-lg last:hover:rounded-b-lg dark:border-gray-700 dark:bg-slate-900 hover:dark:bg-slate-800">
                <div v-if="page.props.auth.user.id === oneOnOne.user.id" class="flex items-center">
                  <!-- avatar -->
                  <Avatar :data="oneOnOne.other_user.avatar" :url="oneOnOne.other_user.url" class="mr-2 w-5" />

                  <Link :href="oneOnOne.other_user.url" class="inline text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                    {{ oneOnOne.other_user.name }}
                  </Link>
                </div>

                <div v-if="page.props.auth.user.id !== oneOnOne.user.id" class="flex items-center">
                  <!-- avatar -->
                  <Avatar :data="oneOnOne.user.avatar" :url="oneOnOne.user.url" class="mr-2 w-5" />

                  <Link :href="oneOnOne.user.url" class="inline text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                    {{ oneOnOne.user.name }}
                  </Link>
                </div>
              </li>
            </ul>

            <!-- blank state -->
            <div v-else>
              <div class="px-4 py-6 text-center">
                <h3 class="mb-2 text-lg font-medium text-gray-900">{{ $t('1:1s are a great way to connect with each other and to follow up on what needs to be done.') }}</h3>
                <p class="mb-10 text-gray-500">{{ $t('Get started by adding your first 1:1 with someone.') }}</p>
                <img src="/img/projects.png" class="mx-auto block h-60 w-60" alt="projects" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
