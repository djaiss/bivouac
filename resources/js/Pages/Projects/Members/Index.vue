<script setup>
import { ChatBubbleBottomCenterTextIcon } from '@heroicons/vue/24/outline';
import { BoltIcon } from '@heroicons/vue/24/outline';
import { Head, Link } from '@inertiajs/vue3';

import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { ChevronDownIcon, ChevronUpIcon, EllipsisVerticalIcon } from '@heroicons/vue/24/outline';
import Avatar from '@/Components/Avatar.vue';
import PrimaryLinkButton from '@/Components/PrimaryLinkButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ProjectHeader from '@/Pages/Projects/Partials/ProjectHeader.vue';

defineProps({
  data: {
    type: Array,
  },
  menu: {
    type: Array,
  },
});
</script>

<template>
  <Head :title="$t('All messages')" />

  <AuthenticatedLayout>
    <div class="mx-auto mb-6 mt-8 max-w-7xl space-y-6 px-12 sm:px-6 lg:px-8">
      <ProjectHeader :data="data" :menu="menu" />

      <div class="mx-auto max-w-2xl bg-white shadow sm:rounded-lg">
        <!-- header -->
        <div class="flex items-center justify-between border-b border-gray-200 px-4 py-2">
          <h2 class="text-lg font-medium text-gray-900">
            {{ $t('Users who can contribute to this project') }}
          </h2>

          <div>
            <PrimaryLinkButton :href="'data.url.create'">{{ $t('Add a message') }}</PrimaryLinkButton>
          </div>
        </div>

        <!-- list of users -->
        <ul v-if="data.members.length > 0" class="w-full">
          <li v-for="member in data.members" :key="member.id"
            class="flex justify-between items-center py-4 pl-4 pr-6 hover:bg-slate-50 last:hover:rounded-b-lg">

            <div class="group mr-4 flex items-center">
              <Avatar :data="member.avatar" :url="member.url" class="mr-2 h-6 w-6 rounded" />
              <Link :href="member.url" class="text-gray-600 group-hover:text-blue-700 group-hover:underline">
              {{ member.name }}
              </Link>
            </div>

            <Menu as="div" class="icon-menu relative z-30 text-left">
              <MenuButton>
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
                      <button
                        @click="showEditTask(task)"
                        :class="[
                          active ? 'bg-violet-500 text-white' : 'text-gray-900',
                          'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                        ]">
                        {{ $t('Edit') }}
                      </button>
                    </MenuItem>
                    <MenuItem v-slot="{ active }">
                      <button
                        @click="destroy(task)"
                        :class="[
                          active ? 'bg-violet-500 text-white' : 'text-gray-900',
                          'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                        ]">
                        {{ $t('Remove') }}
                      </button>
                    </MenuItem>
                  </div>
                </MenuItems>
              </transition>
            </Menu>
          </li>
        </ul>

        <!-- blank state -->
        <div v-else class="px-4 py-6 text-center">
          <h3 class="mb-2 text-lg font-medium text-gray-900">{{ $t("You haven't written a message yet.") }}</h3>
          <p class="mb-5 text-gray-500">{{ $t('Get started by adding your first message.') }}</p>
          <img src="/img/messages.png" class="mx-auto block h-60 w-60" alt="" />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
