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
    <div class="pb-12 pt-20">
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
