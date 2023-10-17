<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { EllipsisVerticalIcon } from '@heroicons/vue/24/outline';
import { ChevronRightIcon } from '@heroicons/vue/24/solid';
import { ChevronDoubleRightIcon } from '@heroicons/vue/24/solid';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { onMounted, ref } from 'vue';

import PrimaryLinkButton from '@/Components/PrimaryLinkButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { flash } from '@/methods.js';

const props = defineProps({
  data: {
    type: Array,
  },
});

const localTeamTypes = ref([]);

onMounted(() => {
  localTeamTypes.value = props.data.team_types;
});

const destroy = (office) => {
  if (confirm(trans('Are you sure? This action cannot be undone.'))) {
    axios
      .delete(office.url.destroy)
      .then(() => {
        flash(trans('The team type has been deleted'), 'success');
        let id = localTeamTypes.value.findIndex((x) => x.id === office.id);
        localTeamTypes.value.splice(id, 1);
      })
      .catch(() => {});
  }
};
</script>

<template>
  <Head :title="$t('Manage team types')" />

  <AuthenticatedLayout>
    <!-- header -->
    <div class="mb-6">
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
                  <span class="ml-1 text-sm text-gray-500 dark:text-gray-400 md:ml-2">
                    {{ $t('Manage team types') }}
                  </span>
                </div>
              </li>
            </ol>
          </nav>
        </div>
      </div>
    </div>

    <div class="pb-12">
      <div class="mx-auto flex max-w-5xl sm:px-6 lg:px-8">
        <div class="w-full">
          <div class="bg-white shadow sm:rounded-lg">
            <!-- title -->
            <div class="flex items-center justify-between border-b border-gray-200 px-4 py-2">
              <h2 class="text-lg font-medium text-gray-900">
                {{ $t("All the organization's team types") }}
              </h2>

              <div>
                <PrimaryLinkButton :href="data.url.create">{{ $t('Add a team type') }}</PrimaryLinkButton>
              </div>
            </div>

            <!-- list of team types -->
            <div v-if="localTeamTypes.length > 0" class="flex">
              <ul class="w-full">
                <li v-for="teamType in localTeamTypes" :key="teamType.id" class="group flex items-center justify-between px-6 py-4 hover:bg-slate-50 last:hover:rounded-b-lg">
                  <div class="flex items-center">
                    <!-- chevrons -->
                    <div class="mr-2 flex">
                      <span v-for="index in teamType.position" :key="index">
                        <ChevronDoubleRightIcon class="w-3 text-gray-500" />
                      </span>
                    </div>

                    <div class="flex items-center">
                      <span class="mr-3">{{ teamType.label }}</span>
                    </div>
                  </div>

                  <!-- menu -->
                  <div>
                    <Menu as="div" class="relative text-left">
                      <MenuButton class="">
                        <EllipsisVerticalIcon class="h-5 w-5 cursor-pointer hover:text-gray-500" />
                      </MenuButton>

                      <transition enter-active-class="transition duration-100 ease-out" enter-from-class="transform scale-95 opacity-0" enter-to-class="transform scale-100 opacity-100" leave-active-class="transition duration-75 ease-in" leave-from-class="transform scale-100 opacity-100" leave-to-class="transform scale-95 opacity-0">
                        <MenuItems class="absolute right-0 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                          <div class="px-1 py-1">
                            <MenuItem v-slot="{ active }">
                              <Link :href="teamType.url.edit" :class="[active ? 'bg-violet-500 text-white' : 'text-gray-900', 'group flex w-full items-center rounded-md px-2 py-2 text-sm']">
                                {{ $t('Edit') }}
                              </Link>
                            </MenuItem>
                            <MenuItem v-slot="{ active }">
                              <button @click="destroy(teamType)" :class="[active ? 'bg-violet-500 text-white' : 'text-gray-900', 'group flex w-full items-center rounded-md px-2 py-2 text-sm']">
                                {{ $t('Delete') }}
                              </button>
                            </MenuItem>
                          </div>
                        </MenuItems>
                      </transition>
                    </Menu>
                  </div>
                </li>
              </ul>
            </div>

            <!-- blank state -->
            <div v-else>
              <div class="px-4 py-6 text-center">
                <h3 class="mb-2 text-lg font-medium text-gray-900">{{ $t('There is no team type yet.') }}</h3>
                <p class="mb-20 text-gray-500">
                  {{ $t('Team types enable you to create a hierarchy of teams in your organization, such as divisions or departments.') }}
                </p>
                <img src="/img/team_type.png" class="mx-auto block h-60 w-60" alt="" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
