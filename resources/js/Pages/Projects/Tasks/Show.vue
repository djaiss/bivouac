<script setup>
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { reactive, ref } from 'vue';

import Checkbox from '@/Components/Checkbox.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Reactions from '@/Components/Reactions.vue';
import Comments from '@/Components/Comments.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { flash } from '@/methods.js';
import ProjectHeader from '@/Pages/Projects/Partials/ProjectHeader.vue';

const props = defineProps({
  data: {
    type: Array,
  },
  menu: {
    type: Array,
  },
});

const form = reactive({
  title: '',
  description: '',
  is_completed: false,
  errors: '',
});

const loadingState = ref(false);
const task = ref(props.data.task);
const title = ref(props.data.task.title);
const description = ref(props.data.task.description);
const editTitleShown = ref(false);

const editTitle = () => {
  form.title = title.value;
  editTitleShown.value = true;
};

const update = () => {
  loadingState.value = true;

  axios
    .put(task.value.url.update, form)
    .then((response) => {
      loadingState.value = false;
      task.value = response.data.data.task;
      title.value = form.title;
      flash(trans('Changes saved'));
      editTitleShown.value = false;
    })
    .catch(() => {
      loadingState.value = false;
    });
};
</script>

<template>
  <Head :title="$t('Task detail')" />

  <AuthenticatedLayout>
    <div class="mx-auto mb-6 mt-8 max-w-7xl space-y-6 px-12 sm:px-6 lg:px-8">
      <ProjectHeader :data="data" :menu="menu" />

      <div class="grid grid-cols-[3fr_1fr] gap-4 px-4">
        <!-- left -->
        <div>
          <!-- task -->
          <div class="relative mb-4 bg-white shadow sm:rounded-lg">
            <!-- body of the task  -->
            <div class="border-b px-6 py-6">
              <!-- task header -->
              <div
                v-if="!editTitleShown"
                class="flex cursor-pointer items-start rounded-lg border border-transparent px-2 py-2 hover:border-gray-200">
                <Checkbox
                  @click="toggleTask(task)"
                  :checked="task.is_completed"
                  :name="'completed' + task.id"
                  class="checkbox-title relative mr-3 h-6 w-6" />
                <p class="text-xl" @click="editTitle()">{{ title }}</p>
              </div>

              <!-- edit title box -->
              <div v-if="editTitleShown">
                <form @submit.prevent="update()" class="flex items-center justify-between">
                  <TextInput
                    id="term"
                    type="text"
                    :placeholder="$t('Enter a title')"
                    class="mr-3 w-full"
                    v-model="form.title"
                    autofocus
                    @keydown.esc="editTitleShown = false"
                    required />

                  <!-- actions -->
                  <div class="flex items-center">
                    <PrimaryButton class="mr-2" :loading="loadingState" :disabled="loadingState">
                      {{ $t('Save') }}
                    </PrimaryButton>

                    <span
                      @click="editTitleShown = false"
                      class="flex cursor-pointer rounded-md bg-gray-100 px-3 py-1.5 font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-200 hover:bg-gray-200">
                      {{ $t('Cancel') }}
                    </span>
                  </div>
                </form>
              </div>

              <!-- message body -->
              <div v-html="description" class="prose mx-auto"></div>
            </div>

            <!-- message footer -->
            <div class="rounded-b-lg bg-gray-50 p-3">
              <Reactions :reactions="task.reactions" :url="task.url" />
            </div>
          </div>

          <!-- comments -->
          <Comments :comments="task.comments" :url="task.url" />
        </div>

        <!-- right -->
        <div>
          <div class="rounded-lg shadow">
            <div class="flex items-center justify-between rounded-t-lg border-b bg-white px-6 py-4">
              <Link
                :href="data.task.url.edit"
                class="text-sm font-medium text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                {{ $t('Edit') }}
              </Link>
            </div>

            <!-- markdown help -->
            <div class="prose rounded-b-lg bg-gray-50 px-6 py-4 text-sm">
              <span
                @click="destroy()"
                class="cursor-pointer font-medium text-red-700 underline hover:rounded-sm hover:bg-red-700 hover:text-white">
                {{ $t('Delete') }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style lang="scss" scoped>
.checkbox-title {
  top: 3px;
}
</style>
