<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { EllipsisVerticalIcon, QuestionMarkCircleIcon, XMarkIcon } from '@heroicons/vue/24/outline';
import { Head, Link } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { ref } from 'vue';

import { usePage } from '@inertiajs/vue3';
import Avatar from '@/Components/Avatar.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { flash } from '@/methods.js';
import ProjectHeader from '@/Pages/Projects/Partials/ProjectHeader.vue';

const props = defineProps({
  data: {
    type: Array,
  },
  menu: {
    type: Array,
  },
});

const page = usePage();
const localMembers = ref(props.data.members);
const localPotentialMembers = ref([]);
const potentialMembersShown = ref(false);
const loadingUsers = ref(false);

const showPotentialMembers = () => {
  potentialMembersShown.value = true;
  loadingUsers.value = true;
  load();
};

const load = () => {
  axios.get(props.data.url.search).then((response) => {
    localPotentialMembers.value = response.data.data.users;
    loadingUsers.value = false;
  });
};

const store = (user) => {
  axios
    .post(user.url.store)
    .then((response) => {
      localMembers.value.push(response.data.data);
      flash(trans('The user has been added as a member'));
      potentialMembersShown.value = false;
    });
};

const remove = (member) => {
  if (confirm(trans('Are you sure? This action cannot be undone.'))) {
    axios.delete(member.url.remove).then(() => {
      flash(trans('The member has been removed'));
      let id = localMembers.value.findIndex((x) => x.id === member.id);
      localMembers.value.splice(id, 1);
    });
  }
};
</script>

<template>
  <Head :title="$t('All messages')" />

  <AuthenticatedLayout>
    <div class="mx-auto mb-6 mt-8 max-w-7xl space-y-6 px-12 sm:px-6 lg:px-8">
      <ProjectHeader :data="data" :menu="menu" />

      <!-- help -->
      <div class="mx-auto max-w-2xl flex bg-white shadow sm:rounded-lg px-4 py-2 text-sm">
        <QuestionMarkCircleIcon class="h-5 w-5 shrink-0 pe-2 text-gray-600" />
        <p>{{ $t('If the project is public, anyone can take part without having to be a formal member. On the other hand, private projects are only accessible and open to members.') }}</p>
      </div>

      <!-- list of potential members -->
      <div v-if="potentialMembersShown" class="mx-auto max-w-2xl bg-white shadow sm:rounded-lg">
        <!-- header -->
        <div class="flex justify-between items-center border-b px-4 py-2">
          <p class="font-bold">{{ $t('Add a user to this project') }}</p>
          <XMarkIcon @click="potentialMembersShown = false"
            class="h-5 w-5 cursor-pointer rounded text-gray-400 hover:bg-gray-300 hover:text-gray-600 group-hover:block" />
        </div>
        <div v-if="loadingUsers">{{ $t('Loading users...') }}</div>

        <!-- list -->
        <ul class="overflow-auto h-40">
          <li v-for="user in localPotentialMembers" :key="user.id" class="flex justify-between items-center py-4 pl-4 pr-6 hover:bg-slate-50 last:hover:rounded-b-lg">
            <!-- user name -->
            <div>{{ user.name }}</div>

            <!-- add button -->
            <span
              @click="store(user)"
              class="flex cursor-pointer rounded-md border border-gray-300 bg-gray-100 px-3 py-1 font-semibold text-gray-700 hover:border-solid hover:border-gray-500 hover:bg-gray-200">
              {{ $t('Add') }}
            </span>
          </li>
        </ul>
      </div>

      <div class="mx-auto max-w-2xl bg-white shadow sm:rounded-lg">
        <!-- header -->
        <div class="flex items-center justify-between border-b border-gray-200 px-4 py-2">
          <h2 class="text-lg font-medium text-gray-900">
            {{ $t('Users who can contribute to this project') }}
          </h2>

          <div>
            <PrimaryButton v-if="! potentialMembersShown" @click.prevent="showPotentialMembers()">{{ $t('Add member') }}</PrimaryButton>
          </div>
        </div>

        <!-- list of users -->
        <ul v-if="localMembers.length > 0" class="w-full">
          <li
            v-for="member in localMembers"
            :key="member.id"
            class="flex items-center justify-between py-4 pl-4 pr-6 hover:bg-slate-50 last:hover:rounded-b-lg">
            <div class="group mr-4 flex items-center">
              <Avatar :data="member.avatar" :url="member.url" class="mr-2 h-6 w-6 rounded" />
              <Link :href="member.url.show" class="text-gray-600 group-hover:text-blue-700 group-hover:underline">
                {{ member.name }}
              </Link>
            </div>

            <Menu v-if="page.props.auth.user.id !== member.id" as="div" class="icon-menu relative z-30 text-left">
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
                        @click="remove(member)"
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
