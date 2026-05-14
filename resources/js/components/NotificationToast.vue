<script setup>
defineProps({
  notification: {
    type: Object,
    required: true,
  },
})
</script>

<template>
  <Transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="opacity-0 -translate-y-2"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-2"
  >
    <div
        v-if="notification.message"
        class="fixed top-4 right-4 z-50 max-w-sm p-4 rounded-lg border shadow-lg"
        :class="notification.type === 'success'
                ? 'bg-green-100 text-green-800 border-green-200'
                : 'bg-red-100 text-red-800 border-red-200'"
        role="status"
        aria-live="polite"
    >
      {{ notification.message }}
    </div>
  </Transition>
</template>
