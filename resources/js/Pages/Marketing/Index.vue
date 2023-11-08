<script setup>
import { Head } from '@inertiajs/vue3';
</script>

<template>
  <Head :title="$t('Bivouac')" />

  <div class="bg-white">
    <header class="absolute inset-x-0 top-0 z-50">
      <nav class="flex items-start justify-between p-6 lg:px-8">
        <div class="flex lg:flex-1"></div>
        <div class="flex flex-col items-center">
          <div class="mb-5">
            <img src="img/marketing-logo.svg" />
          </div>
        </div>
        <div class="hidden lg:flex lg:flex-1 lg:justify-end">
          <a href="/login" class="text-sm font-semibold leading-6 text-gray-900">
            Log in
            <span aria-hidden="true">&rarr;</span>
          </a>
        </div>
      </nav>
    </header>

    <div class="relative isolate px-6 pt-72">
      <div class="mx-auto max-w-4xl">
        <div class="mb-14 text-center">
          <h1 class="text-4xl font-bold text-gray-900 sm:text-6xl">Simple project management and 1:1s software.</h1>
          <p class="mt-6 text-lg leading-8 text-gray-600">Bivouac is simple to use and doesn’t have all the complex features of all those expensive and closed softwares that you are used to.</p>
          <p class="mt-6 text-lg leading-8 text-gray-600">It’s also completely open and free to use if you choose to host it yourself. You can also use our own hosted version for a one-time, non recurring fee if you prefer.</p>
          <div class="mt-10 flex items-center justify-center gap-x-6">
            <a href="/register" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Get started</a>
            <a href="https://github.com/djaiss/bivouac" class="text-sm font-semibold leading-6 text-gray-900">
              Learn more on Github
              <span aria-hidden="true">→</span>
            </a>
          </div>
        </div>

        <div>
          <p class="mb-4 text-center">This is what it looks like.</p>
          <img class="rounded bg-gray-100 p-2" src="/img/marketing-message.png" />
        </div>

        <div class="bg-white py-24 sm:py-32">
          <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:text-center">
              <h2 class="text-base font-semibold leading-7 text-indigo-600">Projects and 1:1s like they should be</h2>
              <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Everything you need to manage your company</p>
              <p class="mt-6 text-lg leading-8 text-gray-600">Bivouac comes with a wide range of features, designed to be simple to use, and make your smile.</p>
            </div>
            <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-4xl">
              <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-10 lg:max-w-none lg:grid-cols-2 lg:gap-y-16">
                <div class="relative pl-16">
                  <dt class="text-base font-semibold leading-7 text-gray-900">
                    <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600">
                      <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                      </svg>
                    </div>
                    Messages
                  </dt>
                  <dd class="mt-2 text-base leading-7 text-gray-600">Project management is about communication. Use this feature to replace emails by centralizing all your communication.</dd>
                </div>
                <div class="relative pl-16">
                  <dt class="text-base font-semibold leading-7 text-gray-900">
                    <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600">
                      <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                      </svg>
                    </div>
                    Tasks
                  </dt>
                  <dd class="mt-2 text-base leading-7 text-gray-600">Assign tasks, add comments, and make sure that someone is accountable to actually move things forward.</dd>
                </div>
                <div class="relative pl-16">
                  <dt class="text-base font-semibold leading-7 text-gray-900">
                    <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600">
                      <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                      </svg>
                    </div>
                    1:1s
                  </dt>
                  <dd class="mt-2 text-base leading-7 text-gray-600">Build collaborative agendas to have meaningfull 1:1s, without losing the history of the past.</dd>
                </div>
                <div class="relative pl-16">
                  <dt class="text-base font-semibold leading-7 text-gray-900">
                    <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600">
                      <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.864 4.243A7.5 7.5 0 0119.5 10.5c0 2.92-.556 5.709-1.568 8.268M5.742 6.364A7.465 7.465 0 004.5 10.5a7.464 7.464 0 01-1.15 3.993m1.989 3.559A11.209 11.209 0 008.25 10.5a3.75 3.75 0 117.5 0c0 .527-.021 1.049-.064 1.565M12 10.5a14.94 14.94 0 01-3.6 9.75m6.633-4.596a18.666 18.666 0 01-2.485 5.33" />
                      </svg>
                    </div>
                    Open source and tracking free
                  </dt>
                  <dd class="mt-2 text-base leading-7 text-gray-600">We are 100% open source, and we do not have any tracking whatsoever.</dd>
                </div>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="mb-10 bg-white">
      <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl sm:text-center">
          <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Simple pricing</h2>
          <p class="mt-6 text-lg leading-8 text-gray-600">
            Bivouac is MIT-licensed, and can be installed for free.
            <a class="cursor-pointer text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white" href="https://github.com/djaiss/bivouac">Visit Github</a>
            to know more. We also offer a hosted version if your prefer.
          </p>
        </div>
        <div class="mx-auto mt-16 max-w-2xl rounded-3xl ring-1 ring-gray-200 sm:mt-20 lg:mx-0 lg:flex lg:max-w-none">
          <div class="p-8 sm:p-10 lg:flex-auto">
            <h3 class="text-2xl font-bold tracking-tight text-gray-900">Lifetime license</h3>
            <p class="mt-6 text-base leading-7 text-gray-600">You can use Bivouac on our hosted version for a one-time, non recurring fee.</p>
            <div class="mt-10 flex items-center gap-x-4">
              <h4 class="flex-none text-sm font-semibold leading-6 text-indigo-600">What’s included</h4>
              <div class="h-px flex-auto bg-gray-100"></div>
            </div>
            <ul role="list" class="mt-8 grid grid-cols-1 gap-4 text-sm leading-6 text-gray-600 sm:grid-cols-2 sm:gap-6">
              <li class="flex gap-x-3">
                <svg class="h-6 w-5 flex-none text-indigo-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                </svg>
                Unlimited projects
              </li>
              <li class="flex gap-x-3">
                <svg class="h-6 w-5 flex-none text-indigo-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                </svg>
                Lifetime updates
              </li>
              <li class="flex gap-x-3">
                <svg class="h-6 w-5 flex-none text-indigo-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                </svg>
                Regular database backups
              </li>
              <li class="flex gap-x-3">
                <svg class="h-6 w-5 flex-none text-indigo-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                </svg>
                Support the open source project
              </li>
            </ul>
          </div>
          <div class="-mt-2 p-2 lg:mt-0 lg:w-full lg:max-w-md lg:flex-shrink-0">
            <div class="rounded-2xl bg-gray-50 py-10 text-center ring-1 ring-inset ring-gray-900/5 lg:flex lg:flex-col lg:justify-center lg:py-16">
              <div class="mx-auto max-w-xs px-8">
                <p class="text-base font-semibold text-gray-600">Pay once, no recurring fees</p>
                <p class="mt-6 flex items-baseline justify-center gap-x-2">
                  <span class="text-5xl font-bold tracking-tight text-gray-900">$1000</span>
                  <span class="text-sm font-semibold leading-6 tracking-wide text-gray-600">CAD</span>
                </p>
                <a href="/register" class="mt-10 block w-full rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Get access</a>
                <p class="mt-6 text-xs leading-5 text-gray-600">Invoices and receipts available for easy company reimbursement</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="mb-20 bg-white text-center">
      <div class="mx-auto max-w-7xl px-6 lg:px-8">2023. Bivouac.</div>
    </div>
  </div>
</template>
