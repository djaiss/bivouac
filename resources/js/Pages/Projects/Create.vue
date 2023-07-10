<script setup>
import { ChevronRightIcon } from '@heroicons/vue/24/solid';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { reactive, ref } from 'vue';

import Error from '@/Components/Error.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextArea from '@/Components/TextArea.vue';
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
  description: '',
  is_public: true,
  errors: '',
});

const submit = () => {
  loadingState.value = true;

  axios
    .post(props.data.url.store, form)
    .then((response) => {
      localStorage.success = trans('The project has been created');
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
                  <Link
                    :href="data.url.breadcrumb.projects"
                    class="text-sm text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white"
                    >{{ $t('Projects') }}</Link
                  >
                </div>
              </li>
              <li>
                <div class="flex items-center">
                  <ChevronRightIcon class="h-4 w-4 text-gray-400" />
                  <span class="ml-1 text-sm text-gray-500 dark:text-gray-400 md:ml-2">{{
                    $t('Create a project')
                  }}</span>
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
            <div class="h-3w-32 relative mx-auto mb-4 w-32 overflow-hidden rounded-full">
              <img src="/img/project_create.png" alt="logo" class="mx-auto block text-center" />
            </div>
            <h1 class="mb-2 text-center text-lg font-bold">{{ $t('Create a project') }}</h1>
            <h3 class="mb-4 text-center text-sm text-gray-700">
              {{ $t('Projects are at the heart of everything in the organization.') }}
            </h3>
          </div>

          <div class="relative border-b px-6 py-4">
            <!-- Title -->
            <div class="mb-4">
              <InputLabel for="title" :value="$t('What is the name of the project?')" class="mb-1" />

              <TextInput id="title" type="text" class="block w-full" v-model="form.name" autofocus required />
            </div>

            <!-- Description -->
            <div class="mb-4">
              <InputLabel
                for="description"
                :value="$t('Provide a description for this project.')"
                :required="false"
                class="mb-1" />

              <TextArea id="description" type="text" class="block w-full" v-model="form.description" />
            </div>

            <Error :errors="form.errors" />
          </div>

          <div class="space-y-2 px-6 py-4">
            <div class="flex items-center gap-x-2">
              <input
                id="hidden"
                v-model="form.is_public"
                value="true"
                name="public"
                type="radio"
                class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600" />
              <label for="hidden" class="block text-sm font-medium leading-6 text-gray-900">{{
                $t('Public: everyone can see and participate')
              }}</label>
            </div>
            <div class="flex items-center gap-x-2">
              <input
                id="month_day"
                v-model="form.is_public"
                value="false"
                name="public"
                type="radio"
                class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600" />
              <label for="month_day" class="block text-sm font-medium leading-6 text-gray-900">{{
                $t('Private: only selected users can access this project')
              }}</label>
            </div>
          </div>

          <div class="flex items-center justify-between border-t bg-gray-50 px-6 py-4">
            <Link
              :href="data.url.breadcrumb.users"
              class="text-sm font-medium text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white"
              >{{ $t('Back') }}</Link
            >

            <PrimaryButton class="ml-4" :loading="loadingState" :disabled="loadingState">
              {{ $t('Create') }}
            </PrimaryButton>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
