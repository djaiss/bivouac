<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { EnvelopeIcon } from '@heroicons/vue/24/outline';
import { EllipsisVerticalIcon } from '@heroicons/vue/24/outline';
import { ChevronRightIcon } from '@heroicons/vue/24/solid';
import { KeyIcon } from '@heroicons/vue/24/solid';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { onMounted, ref, reactive } from 'vue';

import Avatar from '@/Components/Avatar.vue';
import PrimaryLinkButton from '@/Components/PrimaryLinkButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { flash } from '@/methods.js';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextArea from '@/Components/TextArea.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
  data: {
    type: Array,
  },
});

const form = reactive({
  licence: '',
  errors: '',
});
</script>

<template>
  <Head :title="$t('Unlock account')" />

  <AuthenticatedLayout>
    <!-- header -->
    <div class="mb-6">
      <div class="bg-white shadow px-4 py-2">
        <!-- Breadcrumb -->
        <nav class="flex py-3 text-gray-700">
          <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
              <Link :href="data.url.breadcrumb.home"
                class="text-sm text-blue-700 hover:bg-blue-700 hover:text-white hover:rounded-sm underline">{{ $t('Home')
                }}</Link>
            </li>
            <li>
              <div class="flex items-center">
                <ChevronRightIcon class="w-4 h-4 text-gray-400 mr-2" />
                <Link :href="data.url.breadcrumb.settings"
                  class="text-sm text-blue-700 hover:bg-blue-700 hover:text-white hover:rounded-sm underline">{{
                    $t('Account settings') }}</Link>
              </div>
            </li>
            <li>
              <div class="flex items-center">
                <ChevronRightIcon class="w-4 h-4 text-gray-400" />
                <span class="ml-1 text-sm text-gray-500 md:ml-2 dark:text-gray-400">{{ $t('Unlock your account') }}</span>
              </div>
            </li>
          </ol>
        </nav>
      </div>
    </div>

    <div class="pb-12">
      <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 flex">
        <div class="w-full">
          <div v-if="!data.upgraded">
            <h1 class="text-center mt-20 text-3xl mb-4">{{ $t('Unlock unlimited project management, forever.') }}</h1>
            <p class="text-center mb-10">{{ $t('Get everything we have right now, plus any new features we\'ll add in the future.') }}</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 mb-14">
              <div class="bg-white shadow sm:rounded-lg p-8">
                <img src="/img/upgrade_individual.png" class="w-40 mx-auto mb-3" alt="">
                <p class="text-center text-xl font-bold mb-2">{{ $t('Personal') }}</p>
                <p class="mb-6 text-center">{{ $t('For individuals and non-profits') }}</p>
                <div class="flex flex-col bg-gray-100 border rounded-lg border-gray-100 text-center p-10 mb-6">
                  <div class="flex items-center mb-4">
                    <p class="text-4xl mr-4">CA$ 400</p>
                    <p>{{ $t('one time payment') }}</p>
                  </div>

                  <a :href="data.url.store" target="_blank" class="rounded-md bg-indigo-500 px-3 py-1.5 font-semibold text-white shadow-sm ring-1 ring-inset ring-indigo-600 hover:bg-indigo-700 text-center">{{ $t('Unlock') }}</a>
                </div>
                <ul class="text-sm text-center">
                  <li class="inline mr-2">{{ $t('Lifetime access.') }}</li>
                  <li class="inline mr-2">{{ $t('Unlimited projects.') }}</li>
                  <li class="inline">{{ $t('Free updates.') }}</li>
                </ul>
              </div>

              <div class="bg-white shadow sm:rounded-lg p-8">
                <img src="/img/upgrade_teams.png" class="w-40 mx-auto mb-3" alt="">
                <p class="text-center text-xl font-bold mb-2">{{ $t('Teams') }}</p>
                <p class="mb-6 text-center">{{ $t('For companies') }}</p>
                <div class="flex flex-col bg-gray-100 border rounded-lg border-gray-100 text-center p-10 mb-6">
                  <div class="flex items-center mb-4">
                    <p class="text-3xl mr-4">CA$ 1000</p>
                    <p>{{ $t('one time payment') }}</p>
                  </div>

                  <a :href="data.url.store" target="_blank" class="rounded-md bg-indigo-500 px-3 py-1.5 font-semibold text-white shadow-sm ring-1 ring-inset ring-indigo-600 hover:bg-indigo-700 text-center">{{ $t('Unlock') }}</a>
                </div>
                <ul class="text-sm text-center">
                  <li class="inline mr-2">{{ $t('Lifetime access.') }}</li>
                  <li class="inline mr-2">{{ $t('Unlimited projects.') }}</li>
                  <li class="inline">{{ $t('Free updates.') }}</li>
                </ul>
              </div>
            </div>

            <div class="mb-14 rounded-lg shadow bg-white p-8 max-w-2xl mx-auto">
              <div class="mb-4">
                <InputLabel for="title" :value="$t('Grab a licence key above, and paste it here to unlock your account')" class="mb-1" />

                <TextInput id="title" type="text" class="block w-full text-center" v-model="form.name" autofocus required />
              </div>
              <PrimaryButton :loading="loadingState" :disabled="loadingState">
                {{ $t('Activate') }}
              </PrimaryButton>
            </div>

            <div class="px-14">
              <p class="mb-10 text-2xl">{{ $t('Frequently Asked Questions') }}</p>

              <p class="text-xl font-bold mb-3">{{ $t('What does "Lifetime access" mean?') }}</p>
              <p class="mb-6">{{ $t('For a single fee, you\'ll get access to Bivouac for life – no need to pay more than once.Pay now and enjoy it for years to come.To be crystal clear, there\'s no ongoing subscription required.') }}</p>

              <p class="text-xl font-bold mb-3">{{ $t('Isn\'t that too expensive?') }}</p>
              <p class="mb-6">{{ $t('Most software packages require a subscription, and the cost is based on the number of users. If your software costs $100 a month, then it will cost you $1,200 a year. Our deal seems like a pretty good deal.') }}</p>

              <p class="text-xl font-bold mb-3">{{ $t('What does "Free updates" mean?') }}</p>
              <p class="mb-6">{{ $t('It means that all updates, including new features and bug fixes, will be available to you at no extra cost, forever.') }}</p>

              <p class="text-xl font-bold mb-3">{{ $t('What is your refund policy?') }}</p>
              <p class="mb-6">{{ $t('If you\'re unhappy with your purchase for any reason, please send us an email within 30 days and we\'ll refund you in full. After those 30 days, we will not issue a refund.') }}</p>
            </div>
          </div>

          <div v-else>
            <h1 class="text-center mt-20 text-3xl mb-4">{{ $t('Your account is unlocked.') }}</h1>
            <p class="text-center mb-10">{{ $t('Enjoy unlimited projects, forever.') }}</p>
            <img src="/img/upgrade_success.png" class="w-80 mx-auto mb-10" alt="">
            <p class="text-center max-w-md mx-auto font-semibold">{{ $t('We are truly grateful for your support. Without you, we wouldn\'t be where we are today.Thank you!') }}</p>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
