<script setup>
</script>

<template>
  <div>
    <div class="mb-4 bg-white px-4 py-4 shadow sm:rounded-lg">
      <p class="mb-4 text-sm font-bold">{{ $t('Key people') }}</p>

      <!-- cta -->
      <div v-if="!addUpdateShown">
        <p class="mb-4">
          {{ $t("Are there any updates to share about the project? Let project members and followers know what's happening.") }}
        </p>

        <PrimaryButton class="mr-2" @click="showAddModal()">
          {{ $t('Write update') }}
        </PrimaryButton>
      </div>

      <!-- add an update -->
      <form @submit.prevent="store()" v-else>
        <div class="mb-4">
          <ul class="mb-5 inline-block text-sm">
            <li @click="showWriteTab" class="inline cursor-pointer rounded-l-md border px-3 py-1 pr-2" :class="{ 'border-blue-600 text-blue-600': activeTab === 'write' }">
              {{ $t('Post') }}
            </li>
            <li @click="showPreviewTab" class="inline cursor-pointer rounded-r-md border-b border-r border-t px-3 py-1" :class="{ 'border-l border-blue-600 text-blue-600': activeTab === 'preview' }">
              {{ $t('Preview') }}
            </li>
          </ul>

          <!-- write mode -->
          <div v-if="activeTab === 'write'">
            <TextArea id="description" class="block w-full" required v-model="form.body" @esc-key-pressed="addUpdateShown = false" />
          </div>

          <!-- preview mode -->
          <div v-if="activeTab === 'preview'" class="w-full rounded-lg border bg-gray-50 p-4">
            <div v-html="formattedBody" class="prose"></div>
          </div>
        </div>

        <div class="mt-4 flex justify-start">
          <PrimaryButton class="mr-2" :loading="loadingState" :disabled="loadingState">
            {{ $t('Save') }}
          </PrimaryButton>

          <span @click="addUpdateShown = false" class="flex cursor-pointer rounded-md border border-gray-300 bg-gray-100 px-3 py-1 font-semibold text-gray-700 hover:border-solid hover:border-gray-500 hover:bg-gray-200">
            {{ $t('Cancel') }}
          </span>
        </div>
      </form>
    </div>
  </div>
</template>
