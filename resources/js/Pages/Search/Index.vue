<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

import Avatar from '@/Components/Avatar.vue';
import HelpInput from '@/Components/HelpInput.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { reactive, ref } from 'vue';
import Error from '@/Components/Error.vue';

const form = reactive({
  term: '',
  last_name: '',
  password: '',
  errors: null,
});

const loadingState = ref(false);
</script>

<template>
  <Head :title="$t('Search')" />

  <AuthenticatedLayout>
    <div class="mt-8 pb-12">
      <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 flex">
        <div class="w-full">
          <div class="bg-white shadow sm:rounded-lg">
            <div class="p-3 flex items-center justify-between">
              <TextInput
                id="term"
                type="text"
                :placeholder="$t('Search anything')"
                class="block w-full mr-3"
                v-model="form.term"
                autofocus
                required />

              <PrimaryButton :loading="loadingState" :disabled="loadingState">{{ $t('Search') }}</PrimaryButton>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
