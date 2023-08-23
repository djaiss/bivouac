<script setup>
import { ChevronRightIcon } from '@heroicons/vue/24/solid';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { reactive, ref } from 'vue';

import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { flash } from '@/methods.js';

const props = defineProps({
  data: {
    type: Array,
  },
});

const loadingState = ref(false);
const isUpgraded = ref(props.data.upgraded);
const hasError = ref(false);

const form = reactive({
  licence_key: '',
  errors: '',
});

const update = () => {
  loadingState.value = true;

  axios
    .put(props.data.url.upgrade, form)
    .then(() => {
      isUpgraded.value = true;
      loadingState.value = false;
      flash(trans('Account unlocked'));
    })
    .catch(() => {
      hasError.value = true;
      loadingState.value = false;
    });
};
</script>

<template>
  <Head :title="$t('Unlock account')" />

  <AuthenticatedLayout>
    <!-- header -->
    <div class="mb-6">
      <div class="bg-white px-4 py-2 shadow">
        <!-- Breadcrumb -->
        <nav class="flex py-3 text-gray-700">
          <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
              <Link
                :href="data.url.breadcrumb.home"
                class="text-sm text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                {{ $t('Home') }}
              </Link>
            </li>
            <li>
              <div class="flex items-center">
                <ChevronRightIcon class="mr-2 h-4 w-4 text-gray-400" />
                <Link
                  :href="data.url.breadcrumb.settings"
                  class="text-sm text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                  {{ $t('Account settings') }}
                </Link>
              </div>
            </li>
            <li>
              <div class="flex items-center">
                <ChevronRightIcon class="h-4 w-4 text-gray-400" />
                <span class="ml-1 text-sm text-gray-500 dark:text-gray-400 md:ml-2">
                  {{ $t('Unlock your account') }}
                </span>
              </div>
            </li>
          </ol>
        </nav>
      </div>
    </div>

    <div class="pb-12">
      <div class="mx-auto flex max-w-5xl sm:px-6 lg:px-8">
        <div class="w-full">
          <div v-if="!isUpgraded">
            <h1 class="mb-4 mt-20 text-center text-3xl">{{ $t('Unlock unlimited project management, forever.') }}</h1>
            <p class="mb-10 text-center">
              {{ $t("Get everything we have right now, plus any new features we'll add in the future.") }}
            </p>

            <div class="mb-14 grid grid-cols-1 gap-6 md:grid-cols-2 lg:gap-8">
              <div class="bg-white p-8 shadow sm:rounded-lg">
                <img src="/img/upgrade_individual.png" class="mx-auto mb-3 w-40" alt="" />
                <p class="mb-2 text-center text-xl font-bold">{{ $t('Personal') }}</p>
                <p class="mb-6 text-center">{{ $t('For individuals and non-profits') }}</p>
                <div class="mb-6 flex flex-col rounded-lg border border-gray-100 bg-gray-100 p-10 text-center">
                  <div class="mb-4 flex items-center">
                    <p class="mr-4 text-4xl">CA$ 400</p>
                    <p>{{ $t('one time payment') }}</p>
                  </div>

                  <a
                    :href="data.url.store"
                    target="_blank"
                    class="rounded-md bg-indigo-500 px-3 py-1.5 text-center font-semibold text-white shadow-sm ring-1 ring-inset ring-indigo-600 hover:bg-indigo-700">
                    {{ $t('Unlock') }}
                  </a>
                </div>
                <ul class="text-center text-sm">
                  <li class="mr-2 inline">{{ $t('Lifetime access.') }}</li>
                  <li class="mr-2 inline">{{ $t('Unlimited projects.') }}</li>
                  <li class="inline">{{ $t('Free updates.') }}</li>
                </ul>
              </div>

              <div class="bg-white p-8 shadow sm:rounded-lg">
                <img src="/img/upgrade_teams.png" class="mx-auto mb-3 w-40" alt="" />
                <p class="mb-2 text-center text-xl font-bold">{{ $t('Teams') }}</p>
                <p class="mb-6 text-center">{{ $t('For companies') }}</p>
                <div class="mb-6 flex flex-col rounded-lg border border-gray-100 bg-gray-100 p-10 text-center">
                  <div class="mb-4 flex items-center">
                    <p class="mr-4 text-3xl">CA$ 1000</p>
                    <p>{{ $t('one time payment') }}</p>
                  </div>

                  <a
                    :href="data.url.store"
                    target="_blank"
                    class="rounded-md bg-indigo-500 px-3 py-1.5 text-center font-semibold text-white shadow-sm ring-1 ring-inset ring-indigo-600 hover:bg-indigo-700">
                    {{ $t('Unlock') }}
                  </a>
                </div>
                <ul class="text-center text-sm">
                  <li class="mr-2 inline">{{ $t('Lifetime access.') }}</li>
                  <li class="mr-2 inline">{{ $t('Unlimited projects.') }}</li>
                  <li class="inline">{{ $t('Free updates.') }}</li>
                </ul>
              </div>
            </div>

            <form @submit.prevent="update()" class="mx-auto mb-14 max-w-2xl rounded-lg bg-white p-8 shadow">
              <div class="mb-4">
                <InputLabel
                  for="licence_key"
                  :value="$t('Grab a licence key above, and paste it here to unlock your account')"
                  class="mb-1" />

                <TextInput
                  id="licence_key"
                  type="text"
                  class="block w-full text-center"
                  v-model="form.licence_key"
                  autofocus
                  required />
              </div>

              <div v-if="hasError">
                <div class="border-red mb-3 flex items-center rounded border p-3">
                  <img src="/img/error.png" class="h-2w-24 w-24" alt="lumberjack being embarrassed" />

                  <div class="mb-3">
                    <p class="mb-4 text-sm">{{ $t("We've found some errors. Sorry about that.") }}</p>
                    <p>{{ $t('Invalid licence key.') }}</p>
                  </div>
                </div>
              </div>

              <PrimaryButton :loading="loadingState" :disabled="loadingState">
                {{ $t('Activate') }}
              </PrimaryButton>
            </form>

            <div class="px-14">
              <p class="mb-10 text-2xl">{{ $t('Frequently Asked Questions') }}</p>

              <p class="mb-3 text-xl font-bold">{{ $t('What does "Lifetime access" mean?') }}</p>
              <p class="mb-6">
                {{
                  $t(
                    "For a single fee, you'll get access to Bivouac for life – no need to pay more than once.Pay now and enjoy it for years to come.To be crystal clear, there's no ongoing subscription required.",
                  )
                }}
              </p>

              <p class="mb-3 text-xl font-bold">{{ $t("Isn't that too expensive?") }}</p>
              <p class="mb-6">
                {{
                  $t(
                    'Most software packages require a subscription, and the cost is based on the number of users. If your software costs $100 a month, then it will cost you $1,200 a year. Our deal seems like a pretty good deal.',
                  )
                }}
              </p>

              <p class="mb-3 text-xl font-bold">{{ $t('What does "Free updates" mean?') }}</p>
              <p class="mb-6">
                {{
                  $t(
                    'It means that all updates, including new features and bug fixes, will be available to you at no extra cost, forever.',
                  )
                }}
              </p>

              <p class="mb-3 text-xl font-bold">{{ $t('What is your refund policy?') }}</p>
              <p class="mb-6">
                {{
                  $t(
                    "If you're unhappy with your purchase for any reason, please send us an email within 30 days and we'll refund you in full. After those 30 days, we will not issue a refund.",
                  )
                }}
              </p>
            </div>
          </div>

          <div v-else class="rounded-lg bg-white p-8 shadow">
            <h1 class="mb-4 text-center text-3xl">{{ $t('Your account is unlocked.') }}</h1>
            <p class="mb-10 text-center">{{ $t('Enjoy unlimited projects, forever.') }}</p>
            <img src="/img/upgrade_success.png" class="mx-auto mb-10 w-80" alt="" />
            <p class="mx-auto max-w-md text-center font-semibold">
              {{
                $t("We are truly grateful for your support. Without you, we wouldn't be where we are today.Thank you!")
              }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
