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
      <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 flex">
        <div class="w-full">
          <!-- search box -->
          <div class="bg-white shadow rounded-lg mb-10">
            <form @submit.prevent="submit" class="p-3 flex items-center justify-between">
              <TextInput
                id="term"
                type="text"
                :placeholder="$t('Search anything')"
                class="w-full mr-3"
                v-model="form.term"
                autofocus
                required />

              <PrimaryButton :loading="loadingState" :disabled="loadingState">{{ $t('Search') }}</PrimaryButton>
            </form>
          </div>

          <!-- search results -->
          <div v-if="results.length !== 0">
            <!-- users -->
            <div class="flex items-center mb-2">
              <BriefcaseIcon class="h-4 w-4" />
              <span class="ml-2 font-bold">{{ $t('Users') }}</span>
            </div>
            <ul v-if="results.users.length > 0" class="bg-white shadow rounded-lg w-full">
              <li
                v-for="user in results.users"
                :key="user.id"
                class="group flex items-center justify-between px-6 py-4 hover:bg-slate-50 first:hover:rounded-t-lg last:hover:rounded-b-lg">
                <!-- user information -->
                <div class="flex items-center">
                  <Avatar :data="user.avatar" class="w-10 mr-4" />

                  <div class="flex flex-col mr-6">
                    <div>
                      <Link
                        :href="user.url.show"
                        class="text-blue-700 hover:bg-blue-700 hover:text-white hover:rounded-sm underline"
                        >{{ user.name }}</Link
                      >
                    </div>
                    <div class="flex">
                      <div class="text-sm inline">
                        <span class="flex items-center">
                          <span>{{ user.email }}</span>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
            <div v-else class="bg-white shadow rounded-lg w-full p-6 flex items-center justify-center text-gray-600">
              <CircleStackIcon class="h-4 w-4 mr-4" />
              <span>{{ $t('No users match the given criteria.') }}</span>
            </div>

            <!-- projects -->
            <div class="flex items-center mt-10 mb-2">
              <BriefcaseIcon class="h-4 w-4" />
              <span class="ml-2 font-bold">{{ $t('Projects') }}</span>
            </div>
            <ul v-if="results.projects.length > 0" class="bg-white shadow rounded-lg w-full">
              <li
                v-for="project in results.projects"
                :key="project.id"
                class="group flex items-center justify-between px-6 py-4 hover:bg-slate-50 first:hover:rounded-t-lg last:hover:rounded-b-lg">
                <div class="flex items-center">
                  <div>
                    <Link
                      :href="project.url.show"
                      class="text-blue-700 hover:bg-blue-700 hover:text-white hover:rounded-sm underline"
                      >{{ project.name }}</Link
                    >
                  </div>
                </div>
              </li>
            </ul>
            <div v-else class="bg-white shadow rounded-lg w-full p-6 flex items-center justify-center text-gray-600">
              <CircleStackIcon class="h-4 w-4 mr-4" />
              <span>{{ $t('No projects match the given criteria.') }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
