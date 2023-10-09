<script setup>
import { ChevronRightIcon } from '@heroicons/vue/24/solid';
import { Head, Link, router } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { reactive, ref } from 'vue';

import Error from '@/Components/Error.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  data: {
    type: Array,
  },
});

const loadingState = ref(false);

const form = reactive({
  name: '',
  errors: '',
});

const submit = () => {
  loadingState.value = true;

  axios
    .post(props.data.url.store, form)
    .then((response) => {
      localStorage.success = trans('The list has been created');
      router.visit(response.data.data);
    })
    .catch((error) => {
      loadingState.value = false;
      form.errors = error.response.data;
    });
};
</script>

<template>
  <Head :title="$t('Create a project')" />

  <AuthenticatedLayout>
    <!-- header -->
    <div class="mb-12">
      <div class="bg-white px-4 py-2 shadow">
        <div class="">
          <!-- Breadcrumb -->
          <nav class="flex py-3 text-gray-700">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
              <li>
                <div class="flex items-center">
                  <Link :href="data.url.breadcrumb.projects" class="text-sm text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                    {{ $t('Projects') }}
                  </Link>
                </div>
              </li>
              <li>
                <div class="flex items-center">
                  <ChevronRightIcon class="mr-2 h-4 w-4 text-gray-400" />
                  <Link :href="data.url.breadcrumb.project" class="text-sm text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                    {{ data.project.name }}
                  </Link>
                </div>
              </li>
              <li>
                <div class="flex items-center">
                  <ChevronRightIcon class="mr-2 h-4 w-4 text-gray-400" />
                  <Link :href="data.url.breadcrumb.tasks" class="text-sm text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                    {{ $t('Tasks') }}
                  </Link>
                </div>
              </li>
              <li>
                <div class="flex items-center">
                  <ChevronRightIcon class="h-4 w-4 text-gray-400" />
                  <span class="ml-1 text-sm text-gray-500 dark:text-gray-400 md:ml-2">
                    {{ $t('Create a task list') }}
                  </span>
                </div>
              </li>
            </ol>
          </nav>
        </div>
      </div>
    </div>

    <div class="pb-12">
      <div class="mx-auto max-w-lg overflow-hidden rounded-lg bg-white shadow-md dark:bg-gray-800">
        <form @submit.prevent="submit">
          <div class="relative border-b px-6 py-4">
            <h1 class="text-center text-lg font-bold">{{ $t('Create a task list') }}</h1>
          </div>

          <div class="relative px-6 py-4">
            <!-- Title -->
            <div>
              <InputLabel for="title" :value="$t('What is the name of the list?')" class="mb-1" />

              <TextInput id="title" type="text" class="block w-full" v-model="form.name" autofocus required />
            </div>

            <Error :errors="form.errors" />
          </div>

          <div class="flex items-center justify-between border-t bg-gray-50 px-6 py-4">
            <Link :href="data.url.breadcrumb.tasks" class="text-sm font-medium text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
              {{ $t('Back') }}
            </Link>

            <PrimaryButton class="ml-4" :loading="loadingState" :disabled="loadingState">
              {{ $t('Create') }}
            </PrimaryButton>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
