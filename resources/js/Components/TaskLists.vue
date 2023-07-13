<script setup>
import { FaceSmileIcon } from '@heroicons/vue/24/outline';
import { reactive, ref } from 'vue';

import Avatar from '@/Components/Avatar.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const loadingState = ref(false);

const props = defineProps({
  reactions: {
    type: Array,
  },
  url: {
    type: Array,
  },
});

const form = reactive({
  title: '',
  errors: '',
});

const localTasks = ref(props.reactions);
const addTaskModalShown = ref(false);

const showAddTask = () => {
  addTaskModalShown.value = true;
  form.title = '';
};
</script>

<template>
  <div class="shadow bg-white rounded-lg">
    <!-- title of the task list -->
    <div class="px-4 py-2 border-b flex items-center justify-between">
      <p>{{ $t('Tasks') }}</p>

      <!-- button -->
      <p @click="showAddTask" class="text-sm border border-dashed border-gray-500 px-3 py-1.5 bg-gray-50 cursor-pointer hover:bg-gray-200 rounded-lg">Add</p>
    </div>

    <!-- tasks in the list -->
    <div class="px-4 py-2 bg-gray-50 rounded-b-lg">
      <!-- list of tasks -->
      <div v-if="localTasks.length > 0" v-for="task in localTasks" :key="task.id">
        {{ task.title }}
      </div>

      <!-- blank state -->
      <p v-if="localTasks.length == 0 && !addTaskModalShown" class="text-sm">{{ $t('Use tasks to iterate on something that is essential to achieve.') }}</p>

      <!-- add task -->
      <div v-if="addTaskModalShown">
        <form @submit.prevent="submit" class="flex items-center justify-between">
          <TextInput
            id="term"
            type="text"
            :placeholder="$t('Enter a title')"
            class="mr-3 w-full"
            v-model="form.title"
            autofocus
            required />

          <PrimaryButton :loading="loadingState" :disabled="loadingState">{{ $t('Add') }}</PrimaryButton>
        </form>
      </div>
    </div>
  </div>
</template>
