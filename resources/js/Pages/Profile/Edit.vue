<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
  <Head title="Profile" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profile</h2>
    </template>

    <!-- header -->
    <div class="px-12 pt-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 mb-6">
      <div class="bg-white shadow sm:rounded-lg">
        <!-- avatar, name, description -->
        <div class="flex border-b border-gray-200 p-4 sm:p-8">
          <!-- avatar -->
          <img src="https://pbs.twimg.com/profile_images/1638147187119931394/sg8c4mRW_x96.png" class="w-30 h-30 rounded mr-6" alt="profile picture">

          <div>
            <h1 class="font-bold text-xl">Regis Freyd</h1>
          </div>
        </div>

        <!-- menu -->
        <div class="px-5">
          <div class="font-medium text-center text-gray-500 dark:text-gray-400">
            <ul class="flex flex-wrap -mb-px">
              <li class="mr-2">
                <a href="#" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-blue-600 hover:border-blue-300 dark:hover:text-gray-300">{{ $t('Presentation') }}</a>
              </li>
              <li class="mr-2">
                <a href="#" class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500">{{ $t('Work') }}</a>
              </li>
              <li class="mr-2">
                <a href="#" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-blue-600 hover:border-blue-300 dark:hover:text-gray-300">{{ $t('Performance') }}</a>
              </li>
              <li class="mr-2">
                <a href="#" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-blue-600 hover:border-blue-300 dark:hover:text-gray-300">{{ $t('Administration') }}</a>
              </li>
              <li class="mr-2">
                <a href="#" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-blue-600 hover:border-blue-300 dark:hover:text-gray-300">{{ $t('Settings') }}</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="pb-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white shadow sm:rounded-lg">
          <UpdateProfileInformationForm
            :must-verify-email="mustVerifyEmail"
            :status="status"
          />
        </div>

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
          <UpdatePasswordForm class="max-w-xl" />
        </div>

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
          <DeleteUserForm class="max-w-xl" />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
