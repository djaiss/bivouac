<script setup>
import { LockClosedIcon } from '@heroicons/vue/24/solid';
import { Head, Link } from '@inertiajs/vue3';

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
    <div class="mx-auto mb-4 mt-10 flex max-w-5xl justify-end sm:px-6 lg:px-8">
      <!-- menu -->
      <div v-if="!data.needs_upgrade">
        <PrimaryLinkButton :href="data.url.create">{{ $t('Create project') }}</PrimaryLinkButton>
      </div>
      <div v-else>
        <span v-tooltip="$t('Please upgrade your account to add another project')" class="flex cursor-not-allowed rounded-md bg-indigo-500 px-3 py-1.5 font-semibold text-white shadow-sm ring-1 ring-inset ring-indigo-600 hover:bg-indigo-700">
          {{ $t('Create project') }}
        </span>
      </div>
    </div>

    <div class="pb-12">
      <div class="mx-auto flex max-w-5xl sm:px-6 lg:px-8">
        <div class="w-full">
          <div class="bg-white shadow sm:rounded-lg">
            <!-- list of projects -->
            <div v-if="props.data.projects.length > 0" class="flex">
              <ul class="w-full">
                <li v-for="project in props.data.projects" :key="project.id" class="px-6 py-4 hover:bg-slate-50 first:hover:rounded-t-lg last:hover:rounded-b-lg">
                  <!-- project information -->
                  <div class="flex items-center justify-between">
                    <div class="mr-6 flex flex-col">
                      <!-- project name -->
                      <div class="flex items-center">
                        <span v-if="!project.is_public" v-tooltip="$t('This project is private')">
                          <LockClosedIcon class="mr-2 h-4 w-4 text-blue-500" />
                        </span>
                        <Link :href="project.url.show" class="text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                          {{ project.name }}
                        </Link>
                      </div>

                      <!-- description & last activity -->
                      <div class="mt-2 flex items-center">
                        <p v-if="project.short_description" class="mr-4 text-sm text-gray-600">
                          {{ project.short_description }}
                        </p>

                        <div class="flex">
                          <svg class="mr-1 w-6" viewBox="0 0 201 127" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                              d="M72.7119 0.403809L69.8206 8.5089L49.9031 63.3197C28.0589 63.294 23.4324 63.3197 0.27002 63.3197V68.4557C24.2563 68.4557 27.6834 68.43 51.67 68.4557H53.4369L54.0794 66.7706L71.7481 18.2997L95.4402 117.81L97.4482 126.396L100.339 118.05L126.601 41.6523L139.291 67.0112L140.014 68.4557H141.62H185.792C186.86 71.4302 189.676 73.5917 193.02 73.5917C197.278 73.5917 200.73 70.1426 200.73 65.8877C200.73 61.6327 197.278 58.1837 193.02 58.1837C189.676 58.1837 186.86 60.3451 185.792 63.3197H143.226L128.529 33.8679L125.799 28.4109L123.791 34.1889L98.4119 108.019L74.7198 8.74981L72.7119 0.403809Z"
                              fill="#7A7A7A" />
                          </svg>
                          <div class="text-xs">{{ project.updated_at }}</div>
                        </div>
                      </div>
                    </div>

                    <!-- contributors -->
                    <div class="flex -space-x-4">
                      <div v-for="member in project.members" :key="member.id">
                        <Avatar v-tooltip="member.name" :data="member.avatar" class="mr-2 h-8 w-8 cursor-pointer rounded-full border-2 border-white" />
                      </div>
                      <div v-if="project.other_members_counter > 0" class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-white bg-gray-700 text-xs font-medium text-white hover:bg-gray-600 dark:border-gray-800">
                        <span>+{{ project.other_members_counter }}</span>
                      </div>
                    </div>
                  </div>
                </li>
              </ul>
            </div>

            <!-- blank state -->
            <div v-else>
              <div class="px-4 py-6 text-center">
                <h3 class="mb-2 text-lg font-medium text-gray-900">{{ $t("You haven't started a project yet.") }}</h3>
                <p class="mb-10 text-gray-500">{{ $t('Get started by adding your first project.') }}</p>
                <img src="/img/projects.png" class="mx-auto block h-60 w-60" alt="projects" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
