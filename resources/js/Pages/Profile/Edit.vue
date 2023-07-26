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
    <div class="mx-auto mb-6 max-w-7xl space-y-6 px-12 pt-6 sm:px-6 lg:px-8">
      <div class="bg-white shadow sm:rounded-lg">
        <!-- avatar, name, description -->
        <div class="flex border-b border-gray-200 p-4">
          <!-- avatar -->
          <div v-html="data.user.avatar.content" class="mr-4 h-24 w-24 rounded" />

          <div class="w-full">
            <h1 class="text-xl font-bold">Regis Freyd</h1>
          </div>
        </div>

        <!-- menu -->
        <div class="px-4">
          <div class="text-center font-medium text-gray-500 dark:text-gray-400">
            <ul class="-mb-px flex flex-wrap">
              <li class="mr-2">
                <a
                  href="#"
                  class="inline-block rounded-t-lg border-b-2 border-transparent p-4 hover:border-blue-300 hover:text-blue-600 dark:hover:text-gray-300">
                  {{ $t('Presentation') }}
                </a>
              </li>
              <li class="mr-2">
                <a
                  href="#"
                  class="active inline-block rounded-t-lg border-b-2 border-blue-600 p-4 text-blue-600 dark:border-blue-500 dark:text-blue-500">
                  {{ $t('Work') }}
                </a>
              </li>
              <li class="mr-2">
                <a
                  href="#"
                  class="inline-block rounded-t-lg border-b-2 border-transparent p-4 hover:border-blue-300 hover:text-blue-600 dark:hover:text-gray-300">
                  {{ $t('Performance') }}
                </a>
              </li>
              <li class="mr-2">
                <a
                  href="#"
                  class="inline-block rounded-t-lg border-b-2 border-transparent p-4 hover:border-blue-300 hover:text-blue-600 dark:hover:text-gray-300">
                  {{ $t('Administration') }}
                </a>
              </li>
              <li class="mr-2">
                <a
                  href="#"
                  class="inline-block rounded-t-lg border-b-2 border-transparent p-4 hover:border-blue-300 hover:text-blue-600 dark:hover:text-gray-300">
                  {{ $t('Settings') }}
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="pb-12">
      <div class="mx-auto flex max-w-7xl sm:px-6 lg:px-8">
        <div class="w-full space-y-6">
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
