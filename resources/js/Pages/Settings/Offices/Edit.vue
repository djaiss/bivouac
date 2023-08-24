<script setup>
import { ChevronRightIcon } from '@heroicons/vue/24/solid';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { onMounted, reactive, ref } from 'vue';

import Checkbox from '@/Components/Checkbox.vue';
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
  is_main_office: false,
  errors: '',
});

onMounted(() => {
  form.name = props.data.name;
  form.is_main_office = props.data.is_main_office;
});

const update = () => {
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
  <Head :title="$t('Manage offices')" />

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
                    :href="data.url.breadcrumb.offices"
                    class="text-sm text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                    {{ $t('Manage offices') }}
                  </Link>
                </div>
              </li>
              <li>
                <div class="flex items-center">
                  <ChevronRightIcon class="h-4 w-4 text-gray-400" />
                  <span class="ml-1 text-sm text-gray-500 dark:text-gray-400 md:ml-2">{{ props.data.name }}</span>
                </div>
              </li>
            </ol>
          </nav>
        </div>
      </div>
    </div>

    <div class="pb-12">
      <div class="mx-auto max-w-lg overflow-hidden rounded-lg bg-white shadow-md dark:bg-gray-800">
        <form @submit.prevent="update">
          <div class="relative border-b px-6 py-4">
            <div class="h-3w-32 relative mx-auto mb-4 w-32 overflow-hidden rounded-full">
              <img src="/img/office_create.png" alt="logo" class="mx-auto block text-center" />
            </div>
            <h1 class="text-center text-lg font-bold">{{ $t('Edit an office') }}</h1>
          </div>

          <Error :errors="form.errors" />

          <div class="relative border-b px-6 py-4">
            <!-- Name -->
            <div class="mb-4">
              <InputLabel
                for="office-name"
                :value="
                  $t(
                    'What is the name of the office? It can be the name of the city, the street or whatever defines this office.',
                  )
                " />

              <TextInput
                id="office-name"
                type="text"
                autocomplete="off"
                class="mt-2 block w-full"
                v-model="form.name"
                autofocus
                required />
            </div>

            <!-- main office -->
            <div class="flex items-center">
              <Checkbox id="main-office" class="mr-2" v-model:checked="form.is_main_office" />
              <InputLabel for="main-office" :value="$t('Designate this office as the main office')" />
            </div>
          </div>

          <div class="flex items-center justify-between bg-gray-50 px-6 py-4">
            <Link
              :href="data.url.breadcrumb.offices"
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
