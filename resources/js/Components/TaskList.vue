<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { ChevronDownIcon, ChevronUpIcon, EllipsisVerticalIcon } from '@heroicons/vue/24/outline';
import { Link } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { nextTick, reactive, ref } from 'vue';
import ConfettiExplosion from 'vue-confetti-explosion';

import Avatar from '@/Components/Avatar.vue';
import Checkbox from '@/Components/Checkbox.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { flash } from '@/methods.js';

const emit = defineEmits(['destroyed']);

const props = defineProps({
  taskList: {
    type: Object,
  },
  context: {
    type: String,
    default: 'project',
  },
});

const form = reactive({
  title: '',
  is_completed: false,
  task_list_id: 0,
  errors: '',
});

const loadingState = ref(false);
const taskList = ref(props.taskList);
const localTasks = ref(props.taskList.tasks);
const completionRate = ref(props.taskList.completion_rate);
const addTaskModalShown = ref(false);
const componentKey = ref(0);
const collapsed = ref(props.taskList.collapsed);
const editedTaskId = ref(null);
const visibleConfetti = ref(false);

const explode = async () => {
  visibleConfetti.value = false;
  await nextTick();
  visibleConfetti.value = true;
};

const showAddTask = () => {
  addTaskModalShown.value = true;
  form.title = '';
  form.task_list_id = taskList.value.id;
  collapsed.value = false;
};

const showEditTask = (task) => {
  editedTaskId.value = task.id;
  form.title = task.title;
  form.task_list_id = taskList.value.id;
};

const forceRerender = () => {
  componentKey.value += 1;
};

const submit = () => {
  loadingState.value = true;

  axios
    .post(taskList.value.url.store, form)
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

    if (completionRate.value >= 100) {
      explode();
    }
  });
};

const toggleTaskList = () => {
  axios.put(taskList.value.url.toggle).then(() => {
    collapsed.value = !collapsed.value;
  });
};

const edit = (task) => {
  loadingState.value = true;

  axios
    .put(task.url.update, form)
    .then((response) => {
      loadingState.value = false;
      let id = localTasks.value.findIndex((x) => x.id === task.id);
      localTasks.value[id] = response.data.data.task;
      flash(trans('Changes saved'));
      editedTaskId.value = 0;
      form.title = '';
    })
    .catch(() => {
      loadingState.value = false;
    });
};

const destroy = (task) => {
  if (confirm(trans('Are you sure? This action cannot be undone.'))) {
    axios.delete(task.url.destroy).then(() => {
      flash(trans('The task has been deleted'));
      let id = localTasks.value.findIndex((x) => x.id === task.id);
      localTasks.value.splice(id, 1);
    });
  }
};

const destroyList = () => {
  if (confirm(trans('Are you sure? This action cannot be undone.'))) {
    axios.delete(taskList.value.url.destroy).then(() => {
      flash(trans('The list has been deleted'));
      emit('destroyed');
    });
  }
};
</script>

<template>
  <div class="rounded-lg bg-white shadow">
    <!-- title of the task list -->
    <div class="flex items-center justify-between px-4 py-2" :class="{ 'border-b': !collapsed }">
      <!-- section title -->
      <p v-if="taskList.name" class="font-bold">{{ taskList.name }}</p>

      <div v-else>
        <p v-if="context === 'message'">{{ $t('Tasks') }}</p>
        <Link v-else :href="taskList.parent.url" class="text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
          {{ taskList.parent.title }}
        </Link>
      </div>

      <!-- progress and cta -->
      <div class="flex items-center">
        <!-- completion -->
        <div :key="componentKey" v-tooltip="$t('Completion rate')" class="mr-4 h-2 w-24 rounded-full bg-blue-200">
          <div class="h-full rounded-full bg-blue-600 text-center text-xs text-white" :style="'width: ' + completionRate + '%'"></div>
        </div>
        <ConfettiExplosion v-if="visibleConfetti" />

        <div class="flex items-center">
          <!-- button -->
          <p @click="showAddTask" class="mr-2 flex cursor-pointer items-center rounded-lg border border-dashed border-gray-300 bg-gray-50 px-3 py-1 text-sm hover:border-gray-400 hover:bg-gray-200">
            <svg class="mr-1 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>

            {{ $t('Add task') }}
          </p>

          <div v-if="collapsed" @click="toggleTaskList()" class="cursor-pointer rounded-lg px-1 py-1.5 text-gray-400 hover:bg-gray-100">
            <ChevronUpIcon class="h-5 w-5" />
          </div>

          <div v-else @click="toggleTaskList()" class="cursor-pointer rounded-lg px-1 py-1.5 text-gray-400 hover:bg-gray-100">
            <ChevronDownIcon class="h-5 w-5" />
          </div>
        </div>

        <!-- options -->
        <Menu v-if="taskList.name" as="div" class="icon-menu relative z-30 text-left">
          <MenuButton>
            <EllipsisVerticalIcon class="h-5 w-5 cursor-pointer hover:text-gray-500" />
          </MenuButton>

          <transition enter-active-class="transition duration-100 ease-out" enter-from-class="transform scale-95 opacity-0" enter-to-class="transform scale-100 opacity-100" leave-active-class="transition duration-75 ease-in" leave-from-class="transform scale-100 opacity-100" leave-to-class="transform scale-95 opacity-0">
            <MenuItems class="absolute right-0 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
              <div class="px-1 py-1">
                <MenuItem v-slot="{ active }">
                  <Link :href="taskList.url.edit" :class="[active ? 'bg-violet-500 text-white' : 'text-gray-900', 'group flex w-full items-center rounded-md px-2 py-2 text-sm']">
                    {{ $t('Edit') }}
                  </Link>
                </MenuItem>
                <MenuItem v-slot="{ active }">
                  <button @click="destroyList()" :class="[active ? 'bg-violet-500 text-white' : 'text-gray-900', 'group flex w-full items-center rounded-md px-2 py-2 text-sm']">
                    {{ $t('Delete') }}
                  </button>
                </MenuItem>
              </div>
            </MenuItems>
          </transition>
        </Menu>
      </div>
    </div>

    <!-- tasks in the list -->
    <div v-if="localTasks && !collapsed" class="rounded-b-lg bg-gray-50">
      <!-- list of tasks -->
      <div v-if="localTasks.length > 0">
        <div v-for="task in localTasks" :key="task.id" class="border-b px-4 py-2 last:border-b-0">
          <!-- content of the task -->
          <div v-if="task.id != editedTaskId" class="relative flex w-full items-center justify-between rounded-md border border-transparent px-2 py-1 hover:border hover:border-gray-200 hover:bg-white">
            <!-- title and checkbox -->
            <div class="flex items-center">
              <Checkbox @click="toggleTask(task)" :checked="task.is_completed" :name="'completed' + task.id" class="mr-2" />
              <Link :href="task.url.show" class="hover:underline">{{ task.title }}</Link>
            </div>

            <!-- options and assignees -->
            <div class="flex items-center">
              <!-- assignees -->
              <div v-if="task.assignees.length > 0">
                <div class="flex -space-x-3">
                  <Avatar v-for="assignee in task.assignees" :key="assignee.id" v-tooltip="assignee.name" :data="assignee.avatar" :url="assignee.url" class="h-6 w-6 cursor-pointer rounded-full border-2 border-white dark:border-gray-800" />
                </div>
              </div>

              <!-- options -->
              <Menu as="div" class="icon-menu relative z-30 text-left">
                <MenuButton>
                  <EllipsisVerticalIcon class="h-5 w-5 cursor-pointer hover:text-gray-500" />
                </MenuButton>

                <transition enter-active-class="transition duration-100 ease-out" enter-from-class="transform scale-95 opacity-0" enter-to-class="transform scale-100 opacity-100" leave-active-class="transition duration-75 ease-in" leave-from-class="transform scale-100 opacity-100" leave-to-class="transform scale-95 opacity-0">
                  <MenuItems class="absolute right-0 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                    <div class="px-1 py-1">
                      <MenuItem v-slot="{ active }">
                        <button @click="showEditTask(task)" :class="[active ? 'bg-violet-500 text-white' : 'text-gray-900', 'group flex w-full items-center rounded-md px-2 py-2 text-sm']">
                          {{ $t('Edit') }}
                        </button>
                      </MenuItem>
                      <MenuItem v-slot="{ active }">
                        <button @click="destroy(task)" :class="[active ? 'bg-violet-500 text-white' : 'text-gray-900', 'group flex w-full items-center rounded-md px-2 py-2 text-sm']">
                          {{ $t('Delete') }}
                        </button>
                      </MenuItem>
                    </div>
                  </MenuItems>
                </transition>
              </Menu>
            </div>
          </div>

          <!-- edit a task -->
          <form v-else @submit.prevent="edit(task)" class="flex items-center justify-between">
            <TextInput id="term" type="text" :placeholder="$t('Enter a title')" class="mr-3 w-full" v-model="form.title" autofocus @keydown.esc="editedTaskId = 0" required />

            <!-- actions -->
            <div class="flex items-center">
              <PrimaryButton class="mr-2" :loading="loadingState" :disabled="loadingState">
                {{ $t('Edit') }}
              </PrimaryButton>

              <span @click="editedTaskId = 0" class="flex cursor-pointer rounded-md border border-gray-300 bg-gray-100 px-3 py-1 font-semibold text-gray-700 hover:border-solid hover:border-gray-500 hover:bg-gray-200">
                {{ $t('Cancel') }}
              </span>
            </div>
          </form>
        </div>
      </div>

      <!-- blank state -->
      <p v-if="localTasks.length == 0 && !addTaskModalShown" class="px-4 py-2 text-sm">
        {{ $t('Use tasks to iterate on something that is essential to achieve.') }}
      </p>

      <!-- add task -->
      <div v-if="addTaskModalShown" class="px-4 py-2">
        <form @submit.prevent="submit" class="flex items-center justify-between">
          <TextInput id="term" type="text" :placeholder="$t('Enter a title')" class="mr-3 w-full" v-model="form.title" autofocus @keydown.esc="addTaskModalShown = false" required />

          <!-- actions -->
          <div class="flex items-center">
            <PrimaryButton class="mr-2" :loading="loadingState" :disabled="loadingState">
              {{ $t('Save') }}
            </PrimaryButton>

            <span @click="addTaskModalShown = false" class="flex cursor-pointer rounded-md border border-gray-300 bg-gray-100 px-3 py-1 font-semibold text-gray-700 hover:border-solid hover:border-gray-500 hover:bg-gray-200">
              {{ $t('Cancel') }}
            </span>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.icon-menu {
  top: 3px;
}
</style>
