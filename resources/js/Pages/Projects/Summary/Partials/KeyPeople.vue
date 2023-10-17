<script setup>
import { MagnifyingGlassIcon, XMarkIcon } from '@heroicons/vue/24/solid';
import debounce from 'lodash.debounce';
import Avatar from '@/Components/Avatar.vue';
import { Link } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { reactive, ref } from 'vue';
import { flash } from '@/methods.js';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
  data: {
    type: Array,
  },
});

const form = reactive({
  user_id: 0,
  role: '',
  term: '',
  errors: '',
});

const loadingState = ref(false);
const addPeopleShown = ref(false);
const localKeyPeople = ref(props.data.key_people);
const searchResults = ref([]);
const chosenUser = ref(null);

const showAddPeople = () => {
  addPeopleShown.value = true;
  form.user_id = 0;
  form.role = '';
  form.term = '';
  cancelSearch();
};

const searchUsers = debounce(() => {
  if (form.term.length < 3) {
    return;
  }

  axios
    .post(props.data.project.url.search_users, form)
    .then((response) => {
      searchResults.value = response.data.data;
    })
    .catch(() => {
      loadingState.value = false;
    });
}, 500);

const cancelSearch = () => {
  searchResults.value = [];
  form.term = '';
  chosenUser.value = null;
};

const setUser = (user) => {
  form.user_id = user.id;
  chosenUser.value = user;
};

const store = () => {
  axios.post(props.data.project.url.store_key_people, form).then((response) => {
    flash(trans('Changes saved'));
    localKeyPeople.value.push(response.data.data);
    addPeopleShown.value = false;
  });
};

const destroy = (people) => {
  if (confirm(trans('Are you sure? This action cannot be undone.'))) {
    axios.delete(people.url.destroy).then(() => {
      flash(trans('The resource has been deleted'));
      let id = localKeyPeople.value.findIndex((x) => x.id === people.id);
      localKeyPeople.value.splice(id, 1);
    });
  }
};
</script>

<template>
  <div>
    <div class="mb-4 bg-white px-4 py-4 shadow sm:rounded-lg">
      <div class="mb-4 flex items-center justify-between">
        <p class="text-sm font-bold">{{ $t('Key people') }}</p>

        <span @click="showAddPeople" class="mr-2 cursor-pointer rounded-lg border border-dashed border-gray-300 bg-gray-50 px-3 py-1 text-sm hover:border-gray-400 hover:bg-gray-200">
          {{ $t('Add someone') }}
        </span>
      </div>

      <!-- add a key people -->
      <form @submit.prevent="store()" v-if="addPeopleShown" class="mb-4 rounded border bg-gray-50">
        <div class="border-b px-6 py-4">
          <!-- role -->
          <div class="mb-5">
            <InputLabel for="role" :value="$t('What is the role?')" class="mb-2" />

            <TextInput @keydown.esc="addPeopleShown = false" v-model="form.role" id="role" type="text" class="w-full" autofocus required />
          </div>

          <!-- search users -->
          <div v-if="!chosenUser" class="mb-4">
            <InputLabel for="role" :value="$t('Who should have this role?')" class="mb-2" />
            <div class="relative">
              <TextInput v-model="form.term" @input="searchUsers()" @keydown.esc="searchShown = false" type="text" autocomplete="off" :placeholder="$t('Find someone')" class="mt-2 block w-full" required />

              <MagnifyingGlassIcon v-if="!form.term" class="absolute right-3 top-3 h-5 w-5 text-gray-400 transition ease-in-out" />
              <XMarkIcon @click="cancelSearch()" v-else class="absolute right-3 top-3 h-5 w-5 cursor-pointer text-gray-400 transition ease-in-out" />
            </div>

            <!-- search results -->
            <div v-if="searchResults.length > 0" class="mt-3 rounded-lg border border-gray-200">
              <div v-for="result in searchResults" :key="result.id" class="item-list flex items-center justify-between border-b px-5 py-2 last:border-0 hover:bg-slate-50">
                <Avatar v-tooltip="result.name" :data="result.avatar" class="mr-2 h-6 w-6 cursor-pointer rounded-full" />

                <p>{{ result.name }}</p>

                <span @click="setUser(result)" class="flex cursor-pointer rounded-md border border-gray-300 bg-gray-100 px-3 py-1 font-semibold text-gray-700 hover:border-solid hover:border-gray-500 hover:bg-gray-200">
                  {{ $t('Add') }}
                </span>
              </div>
            </div>
          </div>

          <!-- user, if set -->
          <div v-else>
            <p class="mb-2 block text-sm font-medium text-gray-700">{{ $t('Who should have this role?') }}</p>

            <div class="flex items-center">
              <Avatar v-if="chosenUser.avatar" :data="chosenUser.avatar" class="mr-2 h-6 w-6 cursor-pointer rounded-full" />
              <p>{{ chosenUser.name }}</p>
            </div>
          </div>
        </div>

        <div class="flex items-center justify-between px-6 py-4">
          <PrimaryButton class="mr-2" :loading="loadingState" :disabled="loadingState || form.user_id == 0">
            {{ $t('Save') }}
          </PrimaryButton>

          <span @click="addPeopleShown = false" class="flex cursor-pointer rounded-md border border-gray-300 bg-gray-100 px-3 py-1 font-semibold text-gray-700 hover:border-solid hover:border-gray-500 hover:bg-gray-200">
            {{ $t('Cancel') }}
          </span>
        </div>
      </form>

      <!-- list of people -->
      <div v-for="people in localKeyPeople" :key="people.id" class="mb-4 last:mb-0">
        <p class="mb-1 text-sm font-semibold uppercase">{{ people.role }}</p>

        <div class="group flex items-center justify-between rounded-lg px-2 py-1 hover:bg-gray-100">
          <div class="flex items-center">
            <Avatar v-if="people.user.avatar" :data="people.user.avatar" :url="people.user.url" class="mr-2 w-5" />
            <Link :href="people.user.url" class="inline text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
              {{ people.user.name }}
            </Link>
          </div>

          <XMarkIcon @click="destroy(people)" class="hidden h-5 w-5 cursor-pointer rounded text-gray-400 hover:bg-gray-300 hover:text-gray-600 group-hover:block" />
        </div>
      </div>

      <!-- blank state -->
      <p v-if="localKeyPeople.length == 0" class="text-gray-400">
        {{ $t('Define who has an important role in this project.') }}
      </p>
    </div>
  </div>
</template>
