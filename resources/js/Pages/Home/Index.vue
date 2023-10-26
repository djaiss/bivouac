<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Avatar from '@/Components/Avatar.vue';
import Checkbox from '@/Components/Checkbox.vue';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { ref } from 'vue';

const props = defineProps({
  data: {
    type: Array,
  },
});

const page = usePage();
const loggedUser = computed(() => page.props.auth.user);
const welcomeMessageShown = ref(props.data.welcome_message_displayed);

const hide = () => {
  axios.post(props.data.url.hide).then(() => {
    welcomeMessageShown.value = false;
  });
};
</script>

<template>
  <Head :title="$t('Dashboard')" />

  <AuthenticatedLayout>
    <div class="mt-10 pb-12">
      <div class="mx-auto flex max-w-3xl sm:px-6 lg:px-8">
        <div class="w-full">
          <h1 v-if="!welcomeMessageShown" class="mb-6 text-2xl">{{ $t('Good day, :name !', { name: loggedUser.name }) }}</h1>

          <!-- welcome message -->
          <div v-show="welcomeMessageShown" class="mb-6 bg-white px-6 py-4 shadow sm:rounded-lg">
            <p class="mb-5 flex justify-center">
              <img src="/img/welcome.png" class="w-2h-28 mr-3 h-28 rounded-full" alt="lumberjack being embarrassed" />
            </p>
            <h1 class="mb-4 text-center text-xl font-semibold">{{ $t('Welcome to Bivouac!') }}</h1>

            <p class="mb-1">{{ $t('We are delighted that you chose to give Bivouac a go! Bivouac makes it easy to keep track of projects and 1:1s.') }}</p>
            <p class="mb-1">{{ $t('Go ahead, invite some users, and start contributing.') }}</p>
            <p class="mb-5">{{ $t('We hope that you will like it and enjoy our work.') }}</p>
            <div class="mb-4 flex items-center">
              <img src="/img/avatar.png" class="mr-3 h-20 w-20 rounded-full" alt="lumberjack being embarrassed" />
              <div>
                <a href="https://twitter.com/maazarin" target="_blank" class="font-bold">Régis</a>
                <p class="text-xs text-gray-600">maker of Bivouac</p>
              </div>
            </div>
            <p @click="hide" class="text-center text-xs">
              <span class="cursor-pointer text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">{{ $t('Hide this message') }}</span>
            </p>
          </div>

          <!-- assigned tasks -->
          <div class="mb-6 bg-white px-6 py-4 shadow sm:rounded-lg">
            <h2 class="mb-3 font-semibold">{{ $t('Assigned tasks') }}</h2>

            <!-- blank state -->
            <div v-if="props.data.tasks.length == 0">
              {{ $t('You have not been assigned any tasks yet. Get involved in a project to get tasks assigned and start making a difference.') }}
            </div>

            <div v-for="task in props.data.tasks" :key="task.id" class="mb-3 last:mb-0">
              <div class="relative mb-2 flex w-full items-center justify-between rounded-md border border-transparent px-2 py-1 hover:border hover:border-gray-200 hover:bg-white">
                <!-- title and checkbox -->
                <div class="flex items-center">
                  <Checkbox @click="toggleTask(task)" :checked="task.is_completed" :name="'completed' + task.id" class="mr-2" />
                  <Link :href="task.url.show" class="hover:underline">{{ task.title }}</Link>
                </div>

                <!-- options and assignees -->
                <div class="flex items-center">
                  <!-- assignees -->
                  <div v-if="task.assignees.length > 0">
                    <div class="flex -space-x-3">
                      <Avatar v-for="assignee in task.assignees" :key="assignee.id" v-tooltip="assignee.name" :data="assignee.avatar" class="h-6 w-6 cursor-pointer rounded-full border-2 border-white dark:border-gray-800" />
                    </div>
                  </div>
                </div>
              </div>

              <div class="ml-7 flex items-center text-xs text-gray-700">
                <svg class="mr-1 h-4 w-4 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
                </svg>

                <Link :href="task.project.url.show" class="hover:underline">{{ task.project.name }}</Link>
              </div>
            </div>
          </div>

          <!-- visits -->
          <div v-if="data.latest_visits.length > 0" class="mb-6 bg-white px-6 py-4 shadow sm:rounded-lg">
            <h2 class="mb-3 font-semibold">{{ $t('Recently visited') }}</h2>

            <div class="flex">
              <ul class="w-full">
                <li v-for="visit in data.latest_visits" :key="visit.id" class="border border-b-0 px-6 py-4 first:rounded-t-lg last:rounded-b-lg last:border-b hover:bg-slate-50 first:hover:rounded-t-lg last:hover:rounded-b-lg">
                  <!-- project information -->
                  <div class="flex items-center justify-between">
                    <div class="mr-6 flex flex-col">
                      <!-- project name -->
                      <div class="flex items-center">
                        <span v-if="!visit.project.is_public" v-tooltip="$t('This project is private')">
                          <LockClosedIcon class="mr-2 h-4 w-4 text-blue-500" />
                        </span>
                        <Link :href="visit.project.url.show" class="text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                          {{ visit.project.name }}
                        </Link>
                      </div>

                      <!-- description & last activity -->
                      <div class="mt-2 flex items-center">
                        <p v-if="visit.project.short_description" class="mr-4 text-sm text-gray-600">
                          {{ visit.project.short_description }}
                        </p>

                        <div class="flex">
                          <svg class="mr-1 w-6" viewBox="0 0 201 127" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                              d="M72.7119 0.403809L69.8206 8.5089L49.9031 63.3197C28.0589 63.294 23.4324 63.3197 0.27002 63.3197V68.4557C24.2563 68.4557 27.6834 68.43 51.67 68.4557H53.4369L54.0794 66.7706L71.7481 18.2997L95.4402 117.81L97.4482 126.396L100.339 118.05L126.601 41.6523L139.291 67.0112L140.014 68.4557H141.62H185.792C186.86 71.4302 189.676 73.5917 193.02 73.5917C197.278 73.5917 200.73 70.1426 200.73 65.8877C200.73 61.6327 197.278 58.1837 193.02 58.1837C189.676 58.1837 186.86 60.3451 185.792 63.3197H143.226L128.529 33.8679L125.799 28.4109L123.791 34.1889L98.4119 108.019L74.7198 8.74981L72.7119 0.403809Z"
                              fill="#7A7A7A" />
                          </svg>
                          <div class="text-xs">{{ visit.project.updated_at }}</div>
                        </div>
                      </div>
                    </div>

                    <!-- contributors -->
                    <div class="flex -space-x-4">
                      <div v-for="member in visit.project.members" :key="member.id">
                        <Avatar v-tooltip="member.name" :data="member.avatar" class="mr-2 h-8 w-8 cursor-pointer rounded-full border-2 border-white" />
                      </div>
                      <div v-if="visit.project.other_members_counter > 0" class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-white bg-gray-700 text-xs font-medium text-white hover:bg-gray-600 dark:border-gray-800">
                        <span>+{{ visit.project.other_members_counter }}</span>
                      </div>
                    </div>
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
