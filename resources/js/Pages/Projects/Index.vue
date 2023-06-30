<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { EnvelopeIcon } from '@heroicons/vue/24/outline';
import { EllipsisVerticalIcon } from '@heroicons/vue/24/outline';
import { KeyIcon } from '@heroicons/vue/24/solid';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

import Avatar from '@/Components/Avatar.vue';
import PrimaryLinkButton from '@/Components/PrimaryLinkButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  data: {
    type: Array,
  },
});
</script>

<template>
  <Head :title="$t('Projects')" />

  <AuthenticatedLayout>
    <div class="px-12 pt-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 mb-6">
      <div class="bg-white shadow sm:rounded-lg">
        <!-- menu -->
        <div class="px-4">
          <div class="font-medium text-center text-gray-500 dark:text-gray-400 flex items-center justify-between">
            <ul class="flex flex-wrap -mb-px">
              <li class="mr-2">
                <a
                  href="#"
                  class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-blue-600 hover:border-blue-300 dark:hover:text-gray-300"
                  >{{ $t('Your projects') }}</a
                >
              </li>
              <li class="mr-2">
                <a
                  href="#"
                  class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500"
                  >{{ $t('Starred projects') }}</a
                >
              </li>
              <li class="mr-2">
                <a
                  href="#"
                  class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-blue-600 hover:border-blue-300 dark:hover:text-gray-300"
                  >{{ $t('All projects') }}</a
                >
              </li>
            </ul>

            <div>
              <PrimaryLinkButton :href="data.url.create">{{ $t('Create project') }}</PrimaryLinkButton>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="pb-12">
      <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 flex">
        <div class="w-full">
          <div class="bg-white shadow sm:rounded-lg">
            <!-- list of projects -->
            <div class="flex">
              <ul class="w-full">
                <li
                  v-for="project in props.data.projects"
                  :key="project.id"
                  class="group flex items-center justify-between px-6 py-4 hover:bg-slate-50 first:hover:rounded-t-lg last:hover:rounded-b-lg">
                  <!-- project information -->
                  <div class="flex items-center">
                    <div class="flex flex-col mr-6">
                      <Link
                        :href="project.url.show"
                        class="text-blue-700 hover:bg-blue-700 hover:text-white hover:rounded-sm underline"
                        >{{ project.name }}</Link
                      >
                      <p v-if="project.description">{{ project.description }}</p>
                      <div class="flex">
                        <div class="text-sm inline mr-4">
                          <span class="flex items-center">
                            <EnvelopeIcon class="w-3 h-3 mr-2 text-gray-400" />
                            <span>{{ project.email }}</span>
                          </span>
                        </div>
                        <div class="text-sm inline">
                          <span class="flex items-center">
                            <KeyIcon class="w-3 h-3 mr-2 text-gray-400" />
                            {{ project.permissions }}
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- menu -->
                  <div class="flex items-center">
                    <div class="flex -space-x-4">
                      <Avatar
                        v-for="member in project.members.list"
                        :key="member.id"
                        :data="member.avatar"
                        class="w-8 h-8 border-2 border-white rounded-full dark:border-gray-800" />
                    </div>

                    <Menu as="div" class="text-left relative">
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
                                :href="project.url.edit"
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
