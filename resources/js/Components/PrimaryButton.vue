<script setup>
defineProps({
  loading: {
    type: Boolean,
    default: false,
  },
});
</script>

<template>
  <button
    class="rounded-md bg-indigo-500 px-3 py-1.5 font-semibold text-white shadow-sm ring-1 ring-inset ring-indigo-600 hover:bg-indigo-700 flex">
    <span v-if="loading" class="mr-2">
      <div class="lds-ring">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
      </div>
    </span>
    <slot />
  </button>
</template>

<style lang="scss" scoped>
.lds-ring {
  display: inline-block;
  position: relative;
  width: 13px;
  height: 13px;
}
.lds-ring div {
  box-sizing: border-box;
  display: block;
  position: absolute;
  width: 16px;
  height: 16px;
  margin: 0;
  border: 2px solid #fff;
  border-radius: 50%;
  animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
  border-color: #fff transparent transparent transparent;
}
.lds-ring div:nth-child(1) {
  animation-delay: -0.45s;
}
.lds-ring div:nth-child(2) {
  animation-delay: -0.3s;
}
.lds-ring div:nth-child(3) {
  animation-delay: -0.15s;
}
@keyframes lds-ring {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>
