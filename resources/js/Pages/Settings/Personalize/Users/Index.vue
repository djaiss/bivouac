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

import Avatar from '@/Components/Avatar.vue';
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
      <div class="bg-white px-4 py-2 shadow">
        <!-- Breadcrumb -->
        <nav class="flex py-3 text-gray-700">
          <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
              <Link
                :href="data.url.breadcrumb.home"
                class="text-sm text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white"
                >{{ $t('Home') }}</Link
              >
            </li>
            <li>
              <div class="flex items-center">
                <ChevronRightIcon class="mr-2 h-4 w-4 text-gray-400" />
                <Link
                  :href="data.url.breadcrumb.settings"
                  class="text-sm text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white"
                  >{{ $t('Account settings') }}</Link
                >
              </div>
            </li>
            <li>
              <div class="flex items-center">
                <ChevronRightIcon class="h-4 w-4 text-gray-400" />
                <span class="ml-1 text-sm text-gray-500 dark:text-gray-400 md:ml-2">{{ $t('Manage users') }}</span>
              </div>
            </li>
          </ol>
        </nav>
      </div>
    </div>

    <div class="pb-12">
      <div class="mx-auto flex max-w-5xl sm:px-6 lg:px-8">
        <div class="w-full">
          <div class="bg-white shadow sm:rounded-lg">
            <!-- title -->
            <div class="flex items-center justify-between border-b border-gray-200 px-4 py-2">
              <h2 class="text-lg font-medium text-gray-900">
                {{ $t('All the users who have access to this account') }}
              </h2>

              <div>
                <PrimaryLinkButton :href="data.url.invite">{{ $t('Invite user') }}</PrimaryLinkButton>
              </div>
            </div>

            <!-- list of users -->
            <ul class="w-full">
              <li
                v-for="user in localUsers"
                :key="user.id"
                class="group flex items-center justify-between px-6 py-4 hover:bg-slate-50 last:hover:rounded-b-lg">
                <!-- user information -->
                <div class="flex items-center">
                  <Avatar :data="user.avatar" class="mr-4 h-8 w-8 rounded" />

                  <div class="mr-6 flex flex-col">
                    <span class="font-bold">{{ user.name }}</span>
                    <div class="flex">
                      <div class="mr-4 inline text-sm">
                        <span class="flex items-center">
                          <EnvelopeIcon class="mr-2 h-3 w-3 text-gray-400" />
                          <span>{{ user.email }}</span>
                        </span>
                      </div>
                      <div class="inline text-sm">
                        <span class="flex items-center">
                          <KeyIcon class="mr-2 h-3 w-3 text-gray-400" />
                          {{ user.permissions }}
                        </span>
                      </div>
                    </div>
                  </div>

                  <span
                    v-if="!user.verified"
                    class="flex items-center rounded-lg border border-yellow-300 bg-yellow-50 px-2 py-1 text-xs">
                    <span class="text-yellow-600">{{ $t('invited') }}</span>
                  </span>
                </div>

                <!-- menu -->
                <div v-if="user.can_delete" class="">
                  <Menu as="div" class="relative text-left">
                    <MenuButton class="">
                      <EllipsisVerticalIcon class="h-5 w-5 cursor-pointer hover:text-gray-500" />
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
  </AuthenticatedLayout>
</template>
