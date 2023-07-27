<script setup>
import { router } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { ref } from 'vue';

import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
  data: {
    type: Array,
  },
});

const confirmingProjectDeletion = ref(false);

const destroy = () => {
  axios.delete(props.data.url.destroy).then((response) => {
    localStorage.success = trans('The project has been deleted');
    router.visit(response.data.data);
  });
};

const confirmProjectDeletion = () => {
  confirmingProjectDeletion.value = true;
};

const closeModal = () => {
  confirmingProjectDeletion.value = false;
};
</script>

<template>
  <section>
    <header class="w-full">
      <h2 class="border-b border-gray-200 px-4 py-2 text-lg font-medium text-gray-900">
        {{ $t('Delete project') }}
      </h2>
    </header>

    <div class="flex">
      <!-- instructions -->
      <div class="prose mr-8 w-96 p-4 text-sm">
        {{ $t('Deleting the project is instantaneous. This will remove everything, including any files uploaded.') }}
      </div>

      <div class="w-full p-4">
        <DangerButton @click="confirmProjectDeletion">{{ $t('Delete project') }}</DangerButton>

        <Modal :show="confirmingProjectDeletion" @close="closeModal">
          <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">{{ $t('Are you sure you want to delete this project?') }}</h2>

            <p class="mt-1 text-sm text-gray-600">
              {{ $t('Once the project is deleted, all of its resources and data will be permanently deleted.') }}
            </p>

            <div class="mt-6 flex justify-end">
              <SecondaryButton @click="closeModal">{{ $t('Cancel') }}</SecondaryButton>

              <DangerButton class="ml-3" @click="destroy">
                {{ $t('Delete') }}
              </DangerButton>
            </div>
          </div>
        </Modal>
      </div>
    </div>
  </section>
</template>
