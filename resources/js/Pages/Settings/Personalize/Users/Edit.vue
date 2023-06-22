<script setup>
import { ChevronRightIcon } from '@heroicons/vue/24/solid';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { reactive, ref } from 'vue';

import Avatar from '@/Components/Avatar.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  data: {
    type: Array,
  },
});

const loadingState = ref(false);

const form = reactive({
  permission: '',
  errors: '',
});

const submit = () => {
  loadingState.value = true;

  axios
    .post(props.data.url.invite_store, form)
    .then((response) => {
      localStorage.success = trans('The user has been invited');
      router.visit(response.data.data);
    })
    .catch((error) => {
      console.log(error);
      loadingState.value = false;
      form.errors = error.response.data;
    });
};
</script>

<template>
  <Head :title="$t('Manage users')" />

  <AuthenticatedLayout>
    <!-- header -->
    <div class="mb-12">
      <div class="bg-white shadow px-4 py-2">
        <div class="">
          <!-- Breadcrumb -->
          <nav class="flex py-3 text-gray-700">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
              <li class="inline-flex items-center">
                <Link
                  :href="data.url.breadcrumb.home"
                  class="text-sm text-blue-700 hover:bg-blue-700 hover:text-white hover:rounded-sm underline"
                  >{{ $t('Home') }}</Link
                >
              </li>
              <li>
                <div class="flex items-center">
                  <ChevronRightIcon class="w-4 h-4 text-gray-400 mr-2" />
                  <Link
                    :href="data.url.breadcrumb.settings"
                    class="text-sm text-blue-700 hover:bg-blue-700 hover:text-white hover:rounded-sm underline"
                    >{{ $t('Account settings') }}</Link
                  >
                </div>
              </li>
              <li>
                <div class="flex items-center">
                  <ChevronRightIcon class="w-4 h-4 text-gray-400 mr-2" />
                  <Link
                    :href="data.url.breadcrumb.users"
                    class="text-sm text-blue-700 hover:bg-blue-700 hover:text-white hover:rounded-sm underline"
                    >{{ $t('Manage users') }}</Link
                  >
                </div>
              </li>
              <li>
                <div class="flex items-center">
                  <ChevronRightIcon class="w-4 h-4 text-gray-400" />
                  <span class="ml-1 text-sm text-gray-500 md:ml-2 dark:text-gray-400">{{ $t('Edit user') }}</span>
                </div>
              </li>
            </ol>
          </nav>
        </div>
      </div>
    </div>

    <div class="pb-12">
      <div class="mx-auto max-w-lg bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg">
        <form @submit.prevent="submit">
          <div class="px-6 py-4 relative border-b">
            <div class="mx-auto mb-4 relative w-32 h-32 overflow-hidden rounded-full">
              <Avatar :data="props.data.avatar" class="w-32" />
            </div>
            <h1 class="font-bold text-lg text-center mb-2">{{ props.data.name }}</h1>
          </div>

          <div class="px-6 py-4 relative border-b">
            <InputLabel :value="$t('What permissions should this person have?')" class="mb-3" />
            <div class="space-y-2">
              <div class="flex items-center gap-x-2">
                <input
                  id="hidden"
                  v-model="form.age_preferences"
                  value="hidden"
                  name="date-birth"
                  type="radio"
                  class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600" />
                <label for="hidden" class="block text-sm font-medium leading-6 text-gray-900">{{
                  $t('Never display the date of birth to anyone')
                }}</label>
              </div>
              <div class="flex items-center gap-x-2">
                <input
                  id="month_day"
                  v-model="form.age_preferences"
                  value="month_day"
                  name="date-birth"
                  type="radio"
                  class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600" />
                <label for="month_day" class="block text-sm font-medium leading-6 text-gray-900">{{
                  $t('Only show the day and the month')
                }}</label>
              </div>
              <div class="flex items-center gap-x-2">
                <input
                  id="full"
                  v-model="form.age_preferences"
                  value="full"
                  name="date-birth"
                  type="radio"
                  class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600" />
                <label for="full" class="block text-sm font-medium leading-6 text-gray-900">{{
                  $t('Display the full date of birth')
                }}</label>
              </div>
            </div>
          </div>

          <div class="border-t flex items-center justify-between px-6 py-4 bg-gray-50">
            <Link
              :href="data.url.breadcrumb.users"
              class="text-sm font-medium text-blue-700 hover:bg-blue-700 hover:text-white hover:rounded-sm underline"
              >{{ $t('Back') }}</Link
            >

            <PrimaryButton class="ml-4" :loading="loadingState" :disabled="loadingState">
              {{ $t('Send') }}
            </PrimaryButton>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
