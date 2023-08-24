<script setup>
import { ChevronRightIcon } from '@heroicons/vue/24/solid';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { onMounted, reactive, ref } from 'vue';

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
  permissions: '',
  errors: '',
});

onMounted(() => {
  form.permissions = props.data.permissions;
});

const submit = () => {
  loadingState.value = true;

  axios
    .put(props.data.url.update, form)
    .then((response) => {
      localStorage.success = trans('Changes saved');
      router.visit(response.data.data);
    })
    .catch((error) => {
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
      <div class="bg-white px-4 py-2 shadow">
        <div class="">
          <!-- Breadcrumb -->
          <nav class="flex py-3 text-gray-700">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
              <li class="inline-flex items-center">
                <Link
                  :href="data.url.breadcrumb.home"
                  class="text-sm text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                  {{ $t('Home') }}
                </Link>
              </li>
              <li>
                <div class="flex items-center">
                  <ChevronRightIcon class="mr-2 h-4 w-4 text-gray-400" />
                  <Link
                    :href="data.url.breadcrumb.settings"
                    class="text-sm text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                    {{ $t('Account settings') }}
                  </Link>
                </div>
              </li>
              <li>
                <div class="flex items-center">
                  <ChevronRightIcon class="mr-2 h-4 w-4 text-gray-400" />
                  <Link
                    :href="data.url.breadcrumb.users"
                    class="text-sm text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                    {{ $t('Manage users') }}
                  </Link>
                </div>
              </li>
              <li>
                <div class="flex items-center">
                  <ChevronRightIcon class="h-4 w-4 text-gray-400" />
                  <span class="ml-1 text-sm text-gray-500 dark:text-gray-400 md:ml-2">{{ $t('Edit user') }}</span>
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
            <div class="relative mx-auto mb-4 h-32 w-32 overflow-hidden rounded-full">
              <Avatar :data="props.data.avatar" class="w-32" />
            </div>
            <h1 class="mb-2 text-center text-lg font-bold">{{ props.data.name }}</h1>
          </div>

          <div class="relative px-6 py-4">
            <InputLabel :value="$t('What permissions should this person have?')" class="mb-3" />
            <div class="space-y-2">
              <div class="flex items-center gap-x-2">
                <input
                  id="account_manager"
                  v-model="form.permissions"
                  value="account_manager"
                  name="permissions"
                  type="radio"
                  class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600" />
                <label for="account_manager" class="block text-sm font-medium leading-6 text-gray-900">
                  {{ $t('Account manager') }}
                </label>
              </div>
              <div class="flex items-center gap-x-2">
                <input
                  id="administrator"
                  v-model="form.permissions"
                  value="administrator"
                  name="permissions"
                  type="radio"
                  class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600" />
                <label for="administrator" class="block text-sm font-medium leading-6 text-gray-900">
                  {{ $t('Administrator') }}
                </label>
              </div>
              <div class="flex items-center gap-x-2">
                <input
                  id="user"
                  v-model="form.permissions"
                  value="user"
                  name="permissions"
                  type="radio"
                  class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600" />
                <label for="user" class="block text-sm font-medium leading-6 text-gray-900">{{ $t('User') }}</label>
              </div>
            </div>
          </div>

          <div class="flex items-center justify-between border-t bg-gray-50 px-6 py-4">
            <Link
              :href="data.url.breadcrumb.users"
              class="text-sm font-medium text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
              {{ $t('Back') }}
            </Link>

            <PrimaryButton class="ml-4" :loading="loadingState" :disabled="loadingState">
              {{ $t('Save') }}
            </PrimaryButton>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
