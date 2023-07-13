<script setup>
import { FaceSmileIcon } from '@heroicons/vue/24/outline';
import { trans } from 'laravel-vue-i18n';
import { reactive, ref } from 'vue';

import Avatar from '@/Components/Avatar.vue';
import Checkbox from '@/Components/Checkbox.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { flash } from '@/methods.js';

const loadingState = ref(false);

const props = defineProps({
  taskList: {
    type: Object,
  },
  url: {
    type: Array,
  },
});

const form = reactive({
  title: '',
  is_completed: false,
  errors: '',
});

const localTasks = ref(props.taskList.tasks);
const completionRate = ref(props.taskList.completion_rate);
const addTaskModalShown = ref(false);
const componentKey = ref(0);

const showAddTask = () => {
  addTaskModalShown.value = true;
  form.title = '';
};

const forceRerender = () => {
  componentKey.value += 1;
};

const submit = () => {
  loadingState.value = true;

  axios
    .post(props.url.store_task, form)
    .then((response) => {
      localTasks.value.push(response.data.data.task);
      loadingState.value = false;
      flash(trans('The task has been added'));
      addTaskModalShown.value = false;
      form.title = '';
      completionRate.value = response.data.data.completion_rate;
      forceRerender();
    })
    .catch(() => {
      loadingState.value = false;
    });
};

const toggle = (task) => {
  form.title = task.title;
  form.is_completed = !task.is_completed;

  axios
    .put(task.url.update, form)
    .then((response) => {
      let id = localTasks.value.findIndex((x) => x.id === task.id);
      localTasks.value[id] = response.data.data.task;
      completionRate.value = response.data.data.completion_rate;
      forceRerender();
    });
};
</script>

<template>
  <div class="shadow bg-white rounded-lg">
    <!-- title of the task list -->
    <div class="px-4 py-2 border-b flex items-center justify-between">
      <p>{{ $t('Tasks') }}</p>

      <div class="flex items-center">
        <!-- completion -->
        <div :key="componentKey" class="w-24 h-2 bg-blue-200  mr-4 rounded-full">
          <div class="h-full text-center text-xs text-white bg-blue-600 rounded-full" :style="'width: ' + completionRate + '%'"></div>
        </div>

        <!-- button -->
        <p @click="showAddTask" class="text-sm border hover:border-solid border-dashed border-gray-500 px-3 py-1.5 bg-gray-50 cursor-pointer hover:bg-gray-200 rounded-lg">{{ $t('Add') }}</p>
      </div>
    </div>

    <!-- tasks in the list -->
    <div v-if="localTasks" class="bg-gray-50 rounded-b-lg">

      <!-- list of tasks -->
      <div v-if="localTasks.length > 0" v-for="task in localTasks" :key="task.id" class="border-b px-4 py-2">
        <div class="w-full border border-transparent hover:bg-white hover:border hover:border-gray-200 px-2 py-1 rounded-md flex items-center">
          <Checkbox @click="toggle(task)" :checked="task.is_completed" :name="'completed' + task.id" class="mr-2" />
          <span>{{ task.title }}</span>
        </div>
      </div>

      <!-- blank state -->
      <p v-if="localTasks.length == 0 && !addTaskModalShown" class="text-sm px-4 py-2">{{ $t('Use tasks to iterate on something that is essential to achieve.') }}</p>

      <!-- add task -->
      <div v-if="addTaskModalShown" class="px-4 py-2">
        <form @submit.prevent="submit" class="flex items-center justify-between">
          <TextInput
            id="term"
            type="text"
            :placeholder="$t('Enter a title')"
            class="mr-3 w-full"
            v-model="form.title"
            autofocus
            @keydown.esc="addTaskModalShown = false"
            required />

          <PrimaryButton :loading="loadingState" :disabled="loadingState">{{ $t('Add') }}</PrimaryButton>
        </form>
      </div>
    </div>
  </div>
</template>
