<script setup>
defineProps({
  view: {type: String, required: true},
})

defineEmits(['update:view'])
</script>

<template>
  <nav class="bg-white shadow-sm mb-8">
    <div class="max-w-5xl mx-auto px-4 py-4 flex justify-between">
      <span class="font-bold text-xl text-blue-600">Contact Portal</span>
      <div>
        <button
            type="button"
            class="px-4"
            :class="view === 'form' ? 'text-blue-600 font-bold' : ''"
            @click="$emit('update:view', 'form')"
        >
          Submit Form
        </button>
        <button
            type="button"
            class="px-4"
            :class="view === 'list' ? 'text-blue-600 font-bold' : ''"
            @click="$emit('update:view', 'list')"
        >
          View Entries
        </button>
      </div>
    </div>
  </nav>
</template>
