<script setup>
import { BriefcaseIcon, CircleStackIcon } from '@heroicons/vue/24/outline';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { reactive, ref } from 'vue';

import Avatar from '@/Components/Avatar.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  data: {
    type: Array,
  },
});

const form = reactive({
  term: '',
  errors: null,
});

const results = ref([]);
const loadingState = ref(false);

const submit = () => {
  if (form.term != '' && form.term.length >= 3) {
    loadingState.value = true;

    axios
      .post(props.data.url.search, form)
      .then((response) => {
        results.value = response.data.data;
        loadingState.value = false;
      })
      .catch((error) => {
        loadingState.value = false;
        form.errors = error.response.data;
      });
  }
};
</script>

<template>
  <Head :title="$t('Search')" />

  <AuthenticatedLayout>
    <div class="mt-8 pb-12">
      <div class="mx-auto flex max-w-3xl sm:px-6 lg:px-8">
        <div class="w-full">
          <!-- search box -->
          <div class="mb-10 rounded-lg bg-white shadow">
            <form @submit.prevent="submit" class="flex items-center justify-between p-3">
              <TextInput
                id="term"
                type="text"
                :placeholder="$t('Search anything')"
                class="mr-3 w-full"
                v-model="form.term"
                autofocus
                required />

              <PrimaryButton :loading="loadingState" :disabled="loadingState">{{ $t('Search') }}</PrimaryButton>
            </form>
          </div>

          <!-- search results -->
          <div v-if="results.length !== 0">
            <!-- users -->
            <div class="mb-2 flex items-center">
              <BriefcaseIcon class="h-4 w-4" />
              <span class="ml-2 font-bold">{{ $t('Users') }}</span>
            </div>
            <ul v-if="results.users.length > 0" class="w-full rounded-lg bg-white shadow">
              <li
                v-for="user in results.users"
                :key="user.id"
                class="group flex items-center justify-between px-6 py-4 hover:bg-slate-50 first:hover:rounded-t-lg last:hover:rounded-b-lg">
                <!-- user information -->
                <div class="flex items-center">
                  <Avatar :data="user.avatar" class="mr-4 w-10" />

                  <div class="mr-6 flex flex-col">
                    <div>
                      <Link
                        :href="user.url.show"
                        class="text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white"
                        >{{ user.name }}</Link
                      >
                    </div>
                    <div class="flex">
                      <div class="inline text-sm">
                        <span class="flex items-center">
                          <span>{{ user.email }}</span>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
            <div v-else class="flex w-full items-center justify-center rounded-lg bg-white p-6 text-gray-600 shadow">
              <CircleStackIcon class="mr-4 h-4 w-4" />
              <span>{{ $t('No users match the given criteria.') }}</span>
            </div>

            <!-- projects -->
            <div class="mb-2 mt-10 flex items-center">
              <BriefcaseIcon class="h-4 w-4" />
              <span class="ml-2 font-bold">{{ $t('Projects') }}</span>
            </div>
            <ul v-if="results.projects.length > 0" class="w-full rounded-lg bg-white shadow">
              <li
                v-for="project in results.projects"
                :key="project.id"
                class="group flex items-center justify-between px-6 py-4 hover:bg-slate-50 first:hover:rounded-t-lg last:hover:rounded-b-lg">
                <div class="flex items-center">
                  <div>
                    <Link
                      :href="project.url.show"
                      class="text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white"
                      >{{ project.name }}</Link
                    >
                  </div>
                </div>
              </li>
            </ul>
            <div v-else class="flex w-full items-center justify-center rounded-lg bg-white p-6 text-gray-600 shadow">
              <CircleStackIcon class="mr-4 h-4 w-4" />
              <span>{{ $t('No projects match the given criteria.') }}</span>
            </div>

            <!-- messages -->
            <div class="mb-2 mt-10 flex items-center">
              <BriefcaseIcon class="h-4 w-4" />
              <span class="ml-2 font-bold">{{ $t('Messages') }}</span>
            </div>
            <ul v-if="results.messages.length > 0" class="w-full rounded-lg bg-white shadow">
              <li
                v-for="message in results.messages"
                :key="message.id"
                class="group flex items-center justify-between px-6 py-4 hover:bg-slate-50 first:hover:rounded-t-lg last:hover:rounded-b-lg">
                <div class="flex items-center">
                  <div>
                    <Link
                      :href="message.url.show"
                      class="text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white"
                      >{{ message.title }}</Link
                    >
                  </div>
                </div>
              </li>
            </ul>
            <div v-else class="flex w-full items-center justify-center rounded-lg bg-white p-6 text-gray-600 shadow">
              <CircleStackIcon class="mr-4 h-4 w-4" />
              <span>{{ $t('No messages match the given criteria.') }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
