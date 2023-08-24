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

const submit = (emoji) => {
  form.emoji = emoji;

  axios
    .post(props.url.store_reaction, form)
    .then((response) => {
      form.emoji = '';
      localReactions.value.push(response.data.data);
    })
    .catch((error) => {
      form.errors = error.response.data;
    });
};

const destroy = (reaction) => {
  if (page.props.auth.user.id !== reaction.author.id) {
    return;
  }

  axios.delete(reaction.url.destroy).then(() => {
    let id = localReactions.value.findIndex((x) => x.id === reaction.id);
    localReactions.value.splice(id, 1);
  });
};
</script>

<template>
  <div class="flex items-center">
    <VMenu placement="bottom-start" class="mr-2">
      <div class="rounded-full bg-gray-200 p-1 hover:bg-lime-200 hover:cursor-pointer"><FaceSmileIcon class="w-4" /></div>

      <template #popper>
        <div class="flex p-2">
          <div @click="submit('👍')" class="mr-1 cursor-pointer rounded-lg bg-gray-200 hover:bg-gray-300 px-2 py-1">👍</div>
          <div @click="submit('👎')" class="mr-1 cursor-pointer rounded-lg bg-gray-200 hover:bg-gray-300 px-2 py-1">👎</div>
          <div @click="submit('😁')" class="mr-1 cursor-pointer rounded-lg bg-gray-200 hover:bg-gray-300 px-2 py-1">😁</div>
          <div @click="submit('🎉')" class="mr-1 cursor-pointer rounded-lg bg-gray-200 hover:bg-gray-300 px-2 py-1">🎉</div>
          <div @click="submit('🫤')" class="mr-1 cursor-pointer rounded-lg bg-gray-200 hover:bg-gray-300 px-2 py-1">🫤</div>
          <div @click="submit('😭')" class="mr-1 cursor-pointer rounded-lg bg-gray-200 hover:bg-gray-300 px-2 py-1">😭</div>
          <div @click="submit('❤️')" class="mr-1 cursor-pointer rounded-lg bg-gray-200 hover:bg-gray-300 px-2 py-1">❤️</div>
          <div @click="submit('🚀')" class="cursor-pointer rounded-lg bg-gray-200 hover:bg-gray-300 px-2 py-1">🚀</div>
        </div>
      </template>
    </VMenu>

    <!-- list of existing reactions -->
    <div v-if="localReactions" class="flex items-center">
      <div
        v-for="reaction in localReactions"
        :key="reaction.id"
        class="mr-2 flex rounded-lg px-1 py-1"
        :class="{
          'cursor-pointer border border-yellow-200 bg-yellow-100': $page.props.auth.user.id === reaction.author.id,
          'border border-gray-300': $page.props.auth.user.id !== reaction.author.id,
        }"
        @click="destroy(reaction)">
        <Avatar v-tooltip="reaction.author.name" :data="reaction.author.avatar" class="mr-2 h-6 w-6 rounded" />

        <span>{{ reaction.emoji }}</span>
      </div>
    </div>
  </div>
</template>
