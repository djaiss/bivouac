<script setup>
import { LinkIcon, XMarkIcon } from '@heroicons/vue/24/solid';
import { Head, Link } from '@inertiajs/vue3';
import { reactive, ref } from 'vue';

import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
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

const form = reactive({
  name: '',
  link: '',
  errors: '',
});

const addResourceShown = ref(false);

const showAddResource = () => {
  addResourceShown.value = true;
  form.name = '';
  form.link = '';
};
</script>

<template>
  <Head :title="$t('View project')" />

  <AuthenticatedLayout>
    <div class="mx-auto mb-6 mt-8 max-w-7xl space-y-6 px-12 sm:px-6 lg:px-8">
      <ProjectHeader :data="data" :menu="menu" />

      <!-- body -->
      <div class="grid grid-cols-[2fr_1fr] gap-4">
        <!-- left -->
        <div>
          <!-- detailed description-->
          <div class="mb-6 bg-white px-4 py-4 shadow sm:rounded-lg">
            <p class="mb-4 text-sm font-bold">{{ $t('Detailed description') }}</p>

            <!-- description, if it exists -->
            <div v-if="data.project.description" v-html="data.project.description" class=""></div>

            <!-- no description -->
            <div v-else class="text-gray-400">
              {{ $t('No details yet. Consider adding some under the Settings tab.') }}
            </div>
          </div>

          <!-- resources -->
          <div class="bg-white px-4 py-4 shadow sm:rounded-lg">
            <p class="mb-4 text-sm font-bold">{{ $t('Key resources') }}</p>
            <ul class="mb-2">
              <li class="group mb-3 flex items-center justify-between rounded-lg px-2 py-1 hover:bg-gray-100">
                <div class="flex items-center">
                  <LinkIcon class="mr-2 h-4 w-4 text-blue-400" />
                  <Link class="text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                    Project link
                  </Link>
                </div>

                <XMarkIcon class="hidden h-5 w-5 cursor-pointer text-gray-400 group-hover:block hover:bg-gray-300 hover:text-gray-600 rounded" />
              </li>
            </ul>

            <!-- cta to add resource-->
            <div v-if="!addResourceShown">
              <span
                @click="showAddResource"
                class="mr-2 cursor-pointer rounded-lg border border-dashed border-gray-300 bg-gray-50 px-3 py-1 text-sm hover:border-gray-400 hover:bg-gray-200">
                {{ $t('Add resource') }}
              </span>
            </div>

            <!-- add resource -->
            <div v-if="addResourceShown" class="flex justify-between">
              <div class="mr-2 flex w-full">
                <TextInput
                  id="term"
                  type="text"
                  :placeholder="$t('Enter a name')"
                  class="mr-3 w-full"
                  v-model="form.title"
                  autofocus
                  @keydown.esc="addResourceShown = false" />
                <TextInput
                  id="term"
                  type="text"
                  :placeholder="$t('Enter an URL')"
                  class="w-full"
                  v-model="form.title"
                  @keydown.esc="addResourceShown = false"
                  required />
              </div>
              <!-- actions -->
              <div class="flex items-center">
                <PrimaryButton class="mr-2" :loading="loadingState" :disabled="loadingState">
                  {{ $t('Edit') }}
                </PrimaryButton>

                <span
                  @click="addResourceShown = false"
                  class="flex cursor-pointer rounded-md border border-gray-300 bg-gray-100 px-3 py-1 font-semibold text-gray-700 hover:border-solid hover:border-gray-500 hover:bg-gray-200">
                  {{ $t('Cancel') }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- right -->
        <div class="bg-white shadow sm:rounded-lg"></div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
