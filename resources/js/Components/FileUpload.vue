<script setup>
import { useForm } from '@inertiajs/vue3';
import { PaperClipIcon } from '@heroicons/vue/24/solid';

const emit = defineEmits(['fileUploaded']);

const props = defineProps({
  url: {
    type: String,
  },
});

const form = useForm({
  file: null,
});

const submitForm = () => {
  const formData = new FormData();
  formData.append('file', form.file);

  axios
    .post(props.url, formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })
    .then(() => {
      emit('fileUploaded');
    });
};

const onChangeFileUpload = () => {
  submitForm();
};
</script>

<template>
  <div>
    <div class="flex items-center">
      <div>
        <label class="flex items-center rounded-3xl bg-gray-200 p-1 hover:cursor-pointer hover:bg-lime-200" for="file">
          <PaperClipIcon class="mr-2 w-4" />

          <span class="pr-2 text-sm">{{ $t('attach file') }}</span>
        </label>

        <input id="file" class="hidden" type="file" @change.prevent="onChangeFileUpload()" @input="form.file = $event.target.files[0]" />
      </div>
    </div>
  </div>
</template>
