<script setup>
import { HomeIcon } from '@heroicons/vue/24/solid';
import { BuildingOffice2Icon } from '@heroicons/vue/24/solid';
import { MagnifyingGlassIcon } from '@heroicons/vue/24/solid';
import { BellIcon } from '@heroicons/vue/24/solid';
import { QuestionMarkCircleIcon } from '@heroicons/vue/24/solid';
import { BriefcaseIcon } from '@heroicons/vue/24/solid';
import { Cog8ToothIcon } from '@heroicons/vue/24/solid';
import { ArrowLeftOnRectangleIcon } from '@heroicons/vue/24/solid';
import { ComputerDesktopIcon } from '@heroicons/vue/24/solid';
import { Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { computed, onMounted } from 'vue';

import Toaster from '@/Components/Toaster.vue';
import { flash } from '@/methods.js';

const page = usePage();
const user = computed(() => page.props.auth.user);

onMounted(() => {
  if (localStorage.success) {
    flash(localStorage.success, 'success');
    localStorage.removeItem('success');
  }
});
</script>

<template>
  <div>
    <div class="min-h-screen flex-1 flex flex-col sm:flex-row">
      <!-- main content -->
      <main class="flex-1 bg-slate-100">
        <slot />
      </main>

      <!-- sidebar -->
      <nav class="order-first sm:w-60 bg-slate-800">
        <div class="flex-1">
          <!-- Bivouac logo -->
          <div class="bg-slate-900 px-8 py-4 mb-6">
            <div class="flex items-center justify-center">
              <img src="/img/logo.svg" class="h-6 w-6 fill-current text-white mr-4" alt="Bivouac logo" />
              <p class="app-name text-white text-xl">Bivouac</p>
            </div>
          </div>

          <!-- search and notifications -->
          <ul class="text-slate-400 pb-4 mb-4 border-b border-slate-700">
            <li class="px-4 py-2 group hover:bg-slate-900 hover:text-white flex items-center">
              <MagnifyingGlassIcon
                class="h-4 w-4 group-hover:fill-current group-hover:text-blue-500 transition ease-in-out" />
              <span class="ml-2">{{ $t('Search') }}</span>
            </li>

            <li class="px-4 py-2 group hover:bg-slate-900 hover:text-white flex items-center">
              <BellIcon class="h-4 w-4 group-hover:fill-current group-hover:text-blue-500 transition ease-in-out" />
              <span class="ml-2">{{ $t('Notifications') }}</span>
            </li>
          </ul>

          <!-- general menu -->
          <ul class="text-slate-400 pb-4 mb-4 border-b border-slate-700">
            <!-- dashboard -->
            <li class="px-4 py-2 group hover:bg-slate-900 hover:text-white flex items-center">
              <HomeIcon class="h-4 w-4 group-hover:fill-current group-hover:text-blue-500 transition ease-in-out" />
              <span class="ml-2">{{ $t('Home') }}</span>
            </li>

            <!-- company -->
            <li class="px-4 py-2 group hover:bg-slate-900 hover:text-white flex items-center">
              <BuildingOffice2Icon
                class="h-4 w-4 group-hover:fill-current group-hover:text-blue-500 transition ease-in-out" />
              <span class="ml-2">Basecamp</span>
            </li>

            <!-- projects -->
            <li class="px-4 py-2 group hover:bg-slate-900 hover:text-white flex items-center">
              <BriefcaseIcon
                class="h-4 w-4 group-hover:fill-current group-hover:text-blue-500 transition ease-in-out" />
              <span class="ml-2">{{ $t('Projects') }}</span>
            </li>

            <!-- asset management -->
            <li class="px-4 py-2 group hover:bg-slate-900 hover:text-white flex items-center">
              <ComputerDesktopIcon
                class="h-4 w-4 group-hover:fill-current group-hover:text-blue-500 transition ease-in-out" />
              <span class="ml-2">{{ $t('Asset management') }}</span>
            </li>

            <!-- settings -->
            <li
              v-if="user.permissions !== 'user'"
              class="px-4 py-2 group hover:bg-slate-900 hover:text-white flex items-center">
              <Cog8ToothIcon
                class="h-4 w-4 group-hover:fill-current group-hover:text-blue-500 transition ease-in-out" />
              <span class="ml-2">
                <Link :href="page.props.url.settings.personalize">{{ $t('Account settings') }}</Link>
              </span>
            </li>
          </ul>

          <!-- help and user -->
          <ul class="text-slate-400 pb-4 mb-4 border-b border-slate-700">
            <li class="px-4 py-2 group hover:bg-slate-900 hover:text-white flex items-center">
              <QuestionMarkCircleIcon
                class="h-4 w-4 group-hover:fill-current group-hover:text-blue-500 transition ease-in-out" />
              <span class="ml-2">{{ $t('Help') }}</span>
            </li>

            <li class="px-4 py-2 group hover:bg-slate-900 hover:text-white flex items-center">
              <ArrowLeftOnRectangleIcon
                class="h-4 w-4 group-hover:fill-current group-hover:text-blue-500 transition ease-in-out" />
              <span class="ml-2">
                <Link :href="route('logout')" method="post">{{ $t('Logout') }}</Link>
              </span>
            </li>
          </ul>

          <!-- user -->
          <ul class="text-slate-400 mb-4">
            <li class="px-4 py-2 group hover:bg-slate-900 hover:text-white flex items-center">
              <div v-html="user.avatar.content" class="h-7 w-7 rounded mr-2" />
              <span class="ml-2">
                <Link :href="page.props.url.profile">{{ user.name }}</Link>
              </span>
            </li>
          </ul>
        </div>
      </nav>
    </div>

    <!-- toaster, applied on all the pages -->
    <Toaster />
  </div>
</template>

<style lang="scss" scoped>
.app-name {
  font-family: 'Song Myung', serif;
}
</style>
