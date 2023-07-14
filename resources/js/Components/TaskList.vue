<script setup>
import { ChevronDownIcon } from '@heroicons/vue/24/outline';
import { ChevronUpIcon } from '@heroicons/vue/24/outline';
import { trans } from 'laravel-vue-i18n';
import { reactive, ref } from 'vue';

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

const taskList = ref(props.taskList);
const localTasks = ref(props.taskList.tasks);
const completionRate = ref(props.taskList.completion_rate);
const addTaskModalShown = ref(false);
const componentKey = ref(0);
const collapsed = ref(props.taskList.collapsed);

const showAddTask = () => {
  addTaskModalShown.value = true;
  form.title = '';
  collapsed.value = false;
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

const toggleTask = (task) => {
  form.title = task.title;
  form.is_completed = !task.is_completed;

  axios.put(task.url.update, form).then((response) => {
    let id = localTasks.value.findIndex((x) => x.id === task.id);
    localTasks.value[id] = response.data.data.task;
    completionRate.value = response.data.data.completion_rate;
    forceRerender();
  });
};

const toggleTaskList = () => {
  axios.put(taskList.value.url.toggle).then(() => {
    collapsed.value = !collapsed.value;
  });
};
</script>

<template>
  <div class="rounded-lg bg-white shadow">
    <!-- title of the task list -->
    <div class="flex items-center justify-between px-4 py-2" :class="{ 'border-b': !collapsed }">
      <p>{{ $t('Tasks') }}</p>

      <div class="flex items-center">
        <!-- completion -->
        <div :key="componentKey" class="mr-4 h-2 w-24 rounded-full bg-blue-200">
          <div
            class="h-full rounded-full bg-blue-600 text-center text-xs text-white"
            :style="'width: ' + completionRate + '%'"></div>
        </div>

        <div class="flex items-center">
          <!-- button -->
          <p
            @click="showAddTask"
            class="mr-2 cursor-pointer rounded-lg border border-dashed border-gray-300 bg-gray-50 px-3 py-1.5 text-sm hover:border-solid hover:bg-gray-200">
            {{ $t('Add') }}
          </p>

          <div
            v-if="collapsed"
            @click="toggleTaskList()"
            class="cursor-pointer rounded-lg px-1 py-1.5 text-gray-400 hover:bg-gray-100">
            <ChevronUpIcon class="h-5 w-5" />
          </div>

          <div
            v-else
            @click="toggleTaskList()"
            class="cursor-pointer rounded-lg px-1 py-1.5 text-gray-400 hover:bg-gray-100">
            <ChevronDownIcon class="h-5 w-5" />
          </div>
        </div>
      </div>
    </div>

    <!-- tasks in the list -->
    <div v-if="localTasks && !collapsed" class="rounded-b-lg bg-gray-50">
      <!-- list of tasks -->
      <div v-if="localTasks.length > 0">
        <div v-for="task in localTasks" :key="task.id" class="border-b px-4 py-2 last:border-b-0">
          <div
            class="flex w-full items-center rounded-md border border-transparent px-2 py-1 hover:border hover:border-gray-200 hover:bg-white">
            <Checkbox
              @click="toggleTask(task)"
              :checked="task.is_completed"
              :name="'completed' + task.id"
              class="mr-2" />
            <span>{{ task.title }}</span>
          </div>
        </div>
      </div>

      <!-- blank state -->
      <p v-if="localTasks.length == 0 && !addTaskModalShown" class="px-4 py-2 text-sm">
        {{ $t('Use tasks to iterate on something that is essential to achieve.') }}
      </p>

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
