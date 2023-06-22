<script setup>
import { router } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { reactive, ref } from 'vue';

import Error from '@/Components/Error.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
  data: {
    type: Array,
  },
});

const loadingState = ref(false);
const form = reactive({
  errors: null,
});

const update = () => {
  loadingState.value = true;

  axios
    .put(props.data.url.avatar_update)
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
  <section>
    <header class="w-full">
      <h2 class="px-4 py-2 border-b border-gray-200 text-lg font-medium text-gray-900">
        {{ $t('Profile picture') }}
      </h2>
    </header>

    <div class="flex">
      <!-- instructions -->
      <div class="text-sm p-4 w-96 mr-8">
        {{
          $t(
            'You can choose to display an avatar either by using the default one based on your nickname or by uploading a photo.',
          )
        }}
      </div>

      <div class="p-4">
        <Error :errors="form.errors" />

        <form @submit.prevent="update" class="space-y-6 max-w-3xl">
          <PrimaryButton :loading="loadingState" :disabled="loadingState">{{
            $t('Generate new avatar')
          }}</PrimaryButton>
        </form>
      </div>
    </div>
  </section>
</template>
