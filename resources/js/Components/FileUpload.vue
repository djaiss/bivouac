<script setup>
import { router, useForm } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

import { PaperClipIcon } from '@heroicons/vue/24/solid';

const props = defineProps({
  url: {
    type: String,
  },
});

const form = useForm({
  file: null,
});

const submitForm = () => {
  form.post(props.url);
};

const onChangeFileUpload = () => {
  submitForm();
  //file.value = file.value.files[0];
}

const destroy = () => {
  if (confirm(trans('Are you sure? This action cannot be undone.'))) {
    axios.delete(props.data.message.url.destroy).then((response) => {
      localStorage.success = trans('The message has been deleted');
      router.visit(response.data.data);
    });
  }
};
</script>

<template>
  <div>
    <div class="flex items-center">
      <div class=" ">
        <label class="rounded-3xl bg-gray-200 p-1 hover:cursor-pointer hover:bg-lime-200 flex items-center"
          for="file">
          <PaperClipIcon class="w-4 mr-2" />

          <span class="text-sm pr-2">{{ $t('attach file') }}</span>
        </label>

        <input id="file" class="hidden" type="file"
          @change="onChangeFileUpload()"
          @input="form.file = $event.target.files[0]" />
      </div>
    </div>
  </div>
</template>
