<script setup>
import { FaceSmileIcon } from '@heroicons/vue/24/outline';
import { reactive, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';

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

  axios
    .delete(reaction.url.destroy)
    .then(() => {
      let id = localReactions.value.findIndex((x) => x.id === reaction.id);
      localReactions.value.splice(id, 1);
    });
};
</script>

<template>
  <div class="flex items-center">
    <VMenu placement="bottom-start" class="mr-2">
      <div class="rounded-full p-1 bg-gray-200"><FaceSmileIcon class="w-4" /></div>

      <template #popper>
        <div class="p-2 flex">
          <div @click="submit('👍')" class="cursor-pointer px-2 py-1 bg-gray-200 rounded-lg mr-1">👍</div>
          <div @click="submit('👎')" class="cursor-pointer px-2 py-1 bg-gray-200 rounded-lg mr-1">👎</div>
          <div @click="submit('😁')" class="cursor-pointer px-2 py-1 bg-gray-200 rounded-lg mr-1">😁</div>
          <div @click="submit('🎉')" class="cursor-pointer px-2 py-1 bg-gray-200 rounded-lg mr-1">🎉</div>
          <div @click="submit('🫤')" class="cursor-pointer px-2 py-1 bg-gray-200 rounded-lg mr-1">🫤</div>
          <div @click="submit('😭')" class="cursor-pointer px-2 py-1 bg-gray-200 rounded-lg mr-1">😭</div>
          <div @click="submit('❤️')" class="cursor-pointer px-2 py-1 bg-gray-200 rounded-lg mr-1">❤️</div>
          <div @click="submit('🚀')" class="cursor-pointer px-2 py-1 bg-gray-200 rounded-lg">🚀</div>
        </div>
      </template>
    </VMenu>

    <!-- list of existing reactions -->
    <div v-if="localReactions" class="flex items-center">
      <div v-for="reaction in localReactions" key="reaction.id" class="flex px-1 py-1 rounded-lg mr-2"
        :class="{ 'bg-yellow-100 border border-yellow-200 cursor-pointer': $page.props.auth.user.id === reaction.author.id,
        'border border-gray-300': $page.props.auth.user.id !== reaction.author.id }"
        @click="destroy(reaction)"
      >

        <Avatar v-tooltip="reaction.author.name" :data="reaction.author.avatar" class="h-6 w-6 rounded mr-2" />

        <span>{{ reaction.emoji }}</span>
      </div>
    </div>
  </div>
</template>
