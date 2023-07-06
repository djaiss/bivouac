<script setup>
import { Head } from '@inertiajs/vue3';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdateAvatarForm from './Partials/UpdateAvatarForm.vue';
import UpdateDateOfBirthForm from './Partials/UpdateDateOfBirthForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';

defineProps({
  data: {
    type: Array,
  },
});
</script>

<template>
  <Head :title="$t('Profile')" />

  <AuthenticatedLayout>
    <!-- header -->
    <div class="px-12 pt-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 mb-6">
      <div class="bg-white shadow sm:rounded-lg">
        <!-- avatar, name, description -->
        <div class="flex border-b border-gray-200 p-4">
          <!-- avatar -->
          <div v-html="data.user.avatar.content" class="w-24 h-24 rounded mr-4" />

          <div class="w-full">
            <h1 class="font-bold text-xl">Regis Freyd</h1>
          </div>
        </div>

        <!-- menu -->
        <div class="px-4">
          <div class="font-medium text-center text-gray-500 dark:text-gray-400">
            <ul class="flex flex-wrap -mb-px">
              <li class="mr-2">
                <a
                  href="#"
                  class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-blue-600 hover:border-blue-300 dark:hover:text-gray-300"
                  >{{ $t('Presentation') }}</a
                >
              </li>
              <li class="mr-2">
                <a
                  href="#"
                  class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500"
                  >{{ $t('Work') }}</a
                >
              </li>
              <li class="mr-2">
                <a
                  href="#"
                  class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-blue-600 hover:border-blue-300 dark:hover:text-gray-300"
                  >{{ $t('Performance') }}</a
                >
              </li>
              <li class="mr-2">
                <a
                  href="#"
                  class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-blue-600 hover:border-blue-300 dark:hover:text-gray-300"
                  >{{ $t('Administration') }}</a
                >
              </li>
              <li class="mr-2">
                <a
                  href="#"
                  class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-blue-600 hover:border-blue-300 dark:hover:text-gray-300"
                  >{{ $t('Settings') }}</a
                >
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="pb-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex">
        <div class="space-y-6 w-full">
          <div class="bg-white shadow sm:rounded-lg">
            <UpdateProfileInformationForm :data="data" />
          </div>

          <div class="bg-white shadow sm:rounded-lg">
            <UpdateAvatarForm :data="data" />
          </div>

          <div class="bg-white shadow sm:rounded-lg">
            <UpdateDateOfBirthForm :data="data" />
          </div>

          <div class="bg-white shadow sm:rounded-lg">
            <UpdatePasswordForm />
          </div>

          <div class="bg-white shadow sm:rounded-lg">
            <DeleteUserForm class="max-w-xl" />
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
