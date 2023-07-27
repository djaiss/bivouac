<script setup>
import 'v-calendar/style.css';

import { trans } from 'laravel-vue-i18n';
import { DatePicker } from 'v-calendar';
import { reactive, ref } from 'vue';
import { onMounted } from 'vue';

import Error from '@/Components/Error.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { flash } from '@/methods.js';

const props = defineProps({
  data: {
    type: Array,
  },
});

onMounted(() => {
  form.born_at = props.data.user.born_at;
  form.age_preferences = props.data.user?.age_preferences;
});

const masks = ref({
  modelValue: 'YYYY-MM-DD',
});

const loadingState = ref(false);
const form = reactive({
  born_at: null,
  age_preferences: '',
  errors: null,
});

const update = () => {
  loadingState.value = true;

  axios
    .put(props.data.url.birthdate_update, form)
    .then(() => {
      flash(trans('Changes saved'));
      loadingState.value = false;
      form.errors = null;
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
      <h2 class="border-b border-gray-200 px-4 py-2 text-lg font-medium text-gray-900">
        {{ $t('Date of birth') }}
      </h2>
    </header>

    <div class="flex">
      <!-- instructions -->
      <div class="mr-8 w-96 p-4 text-sm">
        {{
          $t('On your profile, you can indicate your age. You can choose how to display this information to others.')
        }}
      </div>

      <div class="p-4">
        <Error :errors="form.errors" />

        <form @submit.prevent="update" class="max-w-3xl space-y-6">
          <!-- date picker -->
          <div>
            <InputLabel :value="$t('Date of birth')" class="mb-1" />
            <DatePicker
              v-model.string="form.born_at"
              class="inline-block h-full"
              :masks="masks"
              :update-on-input="false">
              <template #default="{ inputValue, inputEvents }">
                <input
                  class="rounded border bg-white px-2 py-1 dark:bg-gray-900"
                  :value="inputValue"
                  v-on="inputEvents" />
              </template>
            </DatePicker>
          </div>

          <!-- options -->
          <div class="mb-4 space-y-2">
            <div class="flex items-center gap-x-2">
              <input
                id="hidden"
                v-model="form.age_preferences"
                value="hidden"
                name="date-birth"
                type="radio"
                class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600" />
              <label for="hidden" class="block text-sm font-medium leading-6 text-gray-900">
                {{ $t('Never display the date of birth to anyone') }}
              </label>
            </div>
            <div class="flex items-center gap-x-2">
              <input
                id="month_day"
                v-model="form.age_preferences"
                value="month_day"
                name="date-birth"
                type="radio"
                class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600" />
              <label for="month_day" class="block text-sm font-medium leading-6 text-gray-900">
                {{ $t('Only show the day and the month') }}
              </label>
            </div>
            <div class="flex items-center gap-x-2">
              <input
                id="full"
                v-model="form.age_preferences"
                value="full"
                name="date-birth"
                type="radio"
                class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600" />
              <label for="full" class="block text-sm font-medium leading-6 text-gray-900">
                {{ $t('Display the full date of birth') }}
              </label>
            </div>
          </div>

          <PrimaryButton :loading="loadingState" :disabled="loadingState">{{ $t('Save') }}</PrimaryButton>
        </form>
      </div>
    </div>
  </section>
</template>
