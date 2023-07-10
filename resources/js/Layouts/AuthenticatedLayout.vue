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
    <div class="flex min-h-screen flex-1 flex-col sm:flex-row">
      <!-- main content -->
      <main class="flex-1 bg-slate-100">
        <slot />
      </main>

      <!-- sidebar -->
      <nav class="order-first bg-slate-800 sm:w-60">
        <div class="flex-1">
          <!-- Bivouac logo -->
          <div class="mb-6 bg-slate-900 px-8 py-4">
            <div class="flex items-center justify-center">
              <img src="/img/logo.svg" class="mr-4 h-6 w-6 fill-current text-white" alt="Bivouac logo" />
              <p class="app-name text-xl text-white">Bivouac</p>
            </div>
          </div>

          <!-- search and notifications -->
          <ul class="mb-4 border-b border-slate-700 pb-4 text-slate-400">
            <li class="group flex items-center px-4 py-2 hover:bg-slate-900 hover:text-white">
              <MagnifyingGlassIcon
                class="h-4 w-4 transition ease-in-out group-hover:fill-current group-hover:text-blue-500" />
              <span class="ml-2">
                <Link :href="page.props.url.search">{{ $t('Search') }}</Link>
              </span>
            </li>

            <li class="group flex items-center px-4 py-2 hover:bg-slate-900 hover:text-white">
              <BellIcon class="h-4 w-4 transition ease-in-out group-hover:fill-current group-hover:text-blue-500" />
              <span class="ml-2">{{ $t('Notifications') }}</span>
            </li>
          </ul>

          <!-- general menu -->
          <ul class="mb-4 border-b border-slate-700 pb-4 text-slate-400">
            <!-- dashboard -->
            <li class="group flex items-center px-4 py-2 hover:bg-slate-900 hover:text-white">
              <HomeIcon class="h-4 w-4 transition ease-in-out group-hover:fill-current group-hover:text-blue-500" />
              <span class="ml-2">{{ $t('Home') }}</span>
            </li>

            <!-- company -->
            <li class="group flex items-center px-4 py-2 hover:bg-slate-900 hover:text-white">
              <BuildingOffice2Icon
                class="h-4 w-4 transition ease-in-out group-hover:fill-current group-hover:text-blue-500" />
              <span class="ml-2">Basecamp</span>
            </li>

            <!-- projects -->
            <li class="group flex items-center px-4 py-2 hover:bg-slate-900 hover:text-white">
              <BriefcaseIcon
                class="h-4 w-4 transition ease-in-out group-hover:fill-current group-hover:text-blue-500" />
              <span class="ml-2">
                <Link :href="page.props.url.projects">{{ $t('Projects') }}</Link>
              </span>
            </li>

            <!-- asset management -->
            <li class="group flex items-center px-4 py-2 hover:bg-slate-900 hover:text-white">
              <ComputerDesktopIcon
                class="h-4 w-4 transition ease-in-out group-hover:fill-current group-hover:text-blue-500" />
              <span class="ml-2">{{ $t('Asset management') }}</span>
            </li>

            <!-- settings -->
            <li
              v-if="user.permissions !== 'user'"
              class="group flex items-center px-4 py-2 hover:bg-slate-900 hover:text-white">
              <Cog8ToothIcon
                class="h-4 w-4 transition ease-in-out group-hover:fill-current group-hover:text-blue-500" />
              <span class="ml-2">
                <Link :href="page.props.url.settings.personalize">{{ $t('Account settings') }}</Link>
              </span>
            </li>
          </ul>

          <!-- help and user -->
          <ul class="mb-4 border-b border-slate-700 pb-4 text-slate-400">
            <li class="group flex items-center px-4 py-2 hover:bg-slate-900 hover:text-white">
              <QuestionMarkCircleIcon
                class="h-4 w-4 transition ease-in-out group-hover:fill-current group-hover:text-blue-500" />
              <span class="ml-2">{{ $t('Help') }}</span>
            </li>

            <li class="group flex items-center px-4 py-2 hover:bg-slate-900 hover:text-white">
              <ArrowLeftOnRectangleIcon
                class="h-4 w-4 transition ease-in-out group-hover:fill-current group-hover:text-blue-500" />
              <span class="ml-2">
                <Link :href="route('logout')" method="post">{{ $t('Logout') }}</Link>
              </span>
            </li>
          </ul>

          <!-- user -->
          <ul class="mb-4 text-slate-400">
            <li class="group flex items-center px-4 py-2 hover:bg-slate-900 hover:text-white">
              <div v-html="user.avatar.content" class="mr-2 h-7 w-7 rounded" />
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
