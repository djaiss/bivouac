<script setup>
import { FaceSmileIcon } from '@heroicons/vue/24/outline';
import { usePage } from '@inertiajs/vue3';
import { reactive, ref } from 'vue';

import Avatar from '@/Components/Avatar.vue';

const page = usePage();

const props = defineProps({
  reactions: {
    type: Array,
  },
  url: {
    type: Array,
  },
});

const form = reactive({
  emoji: '',
  errors: '',
});

const localReactions = ref(props.reactions);
</script>

<template>
  <div class="shadow bg-white rounded-lg">
    <!-- title of the task list -->
    <div class="px-4 py-2 border-b flex items-center justify-between">
      <p>Tasks</p>

      <!-- button -->
      <p>Add</p>
    </div>

    <!-- tasks in the list -->
    <div class="px-4 py-2 bg-gray-50 rounded-b-lg">
      <p class="text-sm">Use tasks to iterate on something that is essential to achieve.</p>
    </div>
  </div>
</template>
