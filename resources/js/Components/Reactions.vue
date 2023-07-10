<script setup>
import { FaceSmileIcon } from '@heroicons/vue/24/outline';
</script>

<template>
  <div class="flex">
    <VMenu placement="bottom-start" class="mr-2">
      <div class="rounded-full p-1 bg-gray-100"><FaceSmileIcon class="w-4" /></div>

      <template #popper>
        <div class="p-2 flex">
          <div class="px-2 py-1 bg-gray-200 rounded-lg mr-1">👍</div>
          <div class="px-2 py-1 bg-gray-200 rounded-lg mr-1">👎</div>
          <div class="px-2 py-1 bg-gray-200 rounded-lg mr-1">😁</div>
          <div class="px-2 py-1 bg-gray-200 rounded-lg mr-1">🎉</div>
          <div class="px-2 py-1 bg-gray-200 rounded-lg mr-1">🫤</div>
          <div class="px-2 py-1 bg-gray-200 rounded-lg mr-1">😭</div>
          <div class="px-2 py-1 bg-gray-200 rounded-lg mr-1">❤️</div>
          <div class="px-2 py-1 bg-gray-200 rounded-lg">🚀</div>
        </div>
      </template>
    </VMenu>

    <!-- list of existing reactions -->
    <div class="">dds</div>
  </div>
</template>
