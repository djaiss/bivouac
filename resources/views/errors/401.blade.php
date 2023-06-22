@extends('errors::minimal')

<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
  <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
    <div class="mb-4">
        <img src="/img/police.png" alt="logo" class="text-center mx-auto mb-4 block w-40" />

        <h2 class="font-bold text-center mb-2">{{ __('This action is not authorized.') }}</h2>
        <h3 class="text-sm text-gray-700 mb-4 text-center">{{ __('Have a great day nonetheless.') }}</h3>
      </div>
  </div>
</div>
