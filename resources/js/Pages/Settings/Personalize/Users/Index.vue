<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { EnvelopeIcon } from '@heroicons/vue/24/outline';
import { EllipsisVerticalIcon } from '@heroicons/vue/24/outline';
import { ChevronRightIcon } from '@heroicons/vue/24/solid';
import { KeyIcon } from '@heroicons/vue/24/solid';
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

const localUsers = ref([]);

onMounted(() => {
  localUsers.value = props.data.users;
});

const destroy = (user) => {
  if (confirm(trans('Are you sure? This action cannot be undone.'))) {
    axios
      .delete(user.url.destroy)
      .then(() => {
        flash(trans('The user has been deleted'), 'success');
        let id = localUsers.value.findIndex((x) => x.id === user.id);
        localUsers.value.splice(id, 1);
      })
      .catch(() => {});
  }
};
</script>

<template>
  <Head :title="$t('Manage users')" />

  <AuthenticatedLayout>
    <!-- header -->
    <div class="mb-6">
      <div class="bg-white shadow px-4 py-2">
        <div class="">
          <!-- Breadcrumb -->
          <nav class="flex py-3 text-gray-700">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
              <li class="inline-flex items-center">
                <Link
                  :href="data.url.breadcrumb.home"
                  class="text-sm text-blue-700 hover:bg-blue-700 hover:text-white hover:rounded-sm underline"
                  >{{ $t('Home') }}</Link
                >
              </li>
              <li>
                <div class="flex items-center">
                  <ChevronRightIcon class="w-4 h-4 text-gray-400 mr-2" />
                  <Link
                    :href="data.url.breadcrumb.settings"
                    class="text-sm text-blue-700 hover:bg-blue-700 hover:text-white hover:rounded-sm underline"
                    >{{ $t('Account settings') }}</Link
                  >
                </div>
              </li>
              <li>
                <div class="flex items-center">
                  <ChevronRightIcon class="w-4 h-4 text-gray-400" />
                  <span class="ml-1 text-sm text-gray-500 md:ml-2 dark:text-gray-400">{{ $t('Manage users') }}</span>
                </div>
              </li>
            </ol>
          </nav>
        </div>
      </div>
    </div>

    <div class="pb-12">
      <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 flex">
        <div class="w-full">
          <div class="bg-white shadow sm:rounded-lg">
            <!-- title -->
            <div class="px-4 py-2 flex justify-between items-center border-b border-gray-200">
              <h2 class="text-lg font-medium text-gray-900">
                {{ $t('All the users who have access to this account') }}
              </h2>

              <div>
                <PrimaryLinkButton :href="data.url.invite">{{ $t('Invite user') }}</PrimaryLinkButton>
              </div>
            </div>

            <!-- list of users -->
            <div class="flex">
              <ul class="w-full">
                <li
                  v-for="user in localUsers"
                  :key="user.id"
                  class="group flex items-center justify-between px-6 py-4 hover:bg-slate-50 last:hover:rounded-b-lg">
                  <!-- user information -->
                  <div class="flex items-center">
                    <div v-html="user.avatar.content" class="h-7 w-7 rounded mr-4" />

                    <div class="flex flex-col mr-6">
                      <span class="font-bold">{{ user.name }}</span>
                      <div class="flex">
                        <div class="text-sm inline mr-4">
                          <span class="flex items-center">
                            <EnvelopeIcon class="w-3 h-3 mr-2 text-gray-400" />
                            <span>{{ user.email }}</span>
                          </span>
                        </div>
                        <div class="text-sm inline">
                          <span class="flex items-center">
                            <KeyIcon class="w-3 h-3 mr-2 text-gray-400" />
                            {{ user.permissions }}
                          </span>
                        </div>
                      </div>
                    </div>

                    <span
                      v-if="!user.verified"
                      class="flex items-center bg-yellow-50 border-yellow-300 border px-2 py-1 rounded-lg text-xs">
                      <span class="text-yellow-600">{{ $t('invited') }}</span>
                    </span>
                  </div>

                  <!-- menu -->
                  <div v-if="user.can_delete">
                    <Menu as="div" class="text-left">
                      <MenuButton class="">
                        <EllipsisVerticalIcon class="h-5 w-5 hover:text-gray-500 cursor-pointer" />
                      </MenuButton>

                      <transition
                        enter-active-class="transition duration-100 ease-out"
                        enter-from-class="transform scale-95 opacity-0"
                        enter-to-class="transform scale-100 opacity-100"
                        leave-active-class="transition duration-75 ease-in"
                        leave-from-class="transform scale-100 opacity-100"
                        leave-to-class="transform scale-95 opacity-0">
                        <MenuItems
                          class="absolute right-0 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                          <div class="px-1 py-1">
                            <MenuItem v-slot="{ active }">
                              <Link
                                :href="user.url.edit"
                                :class="[
                                  active ? 'bg-violet-500 text-white' : 'text-gray-900',
                                  'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                ]">
                                {{ $t('Edit') }}
                              </Link>
                            </MenuItem>
                            <MenuItem v-slot="{ active }">
                              <button
                                @click="destroy(user)"
                                :class="[
                                  active ? 'bg-violet-500 text-white' : 'text-gray-900',
                                  'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                ]">
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
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
