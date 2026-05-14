<script setup>
import {inject, ref} from 'vue'
import {submitEntry} from '../api.js'

const emit = defineEmits(['submitted'])
const showNotification = inject('showNotification')

const loading = ref(false)
const errors = ref({})
const form = ref({
  name: '',
  email: '',
  phone: '',
  message: '',
})

const fieldErrors = (field) => {
  const value = errors.value[field]
  if (!value) return []
  return Array.isArray(value) ? value : [value]
}

const submitForm = async () => {
  loading.value = true
  errors.value = {}

  try {
    const {status, data} = await submitEntry(form.value)

    if (status === 419) {
      showNotification(
          'Your session expired. Please refresh the page and try again.',
          'error'
      )
      return
    }

    if (status === 422) {
      errors.value = data?.errors ?? {}
    } else if (data?.status === 'success') {
      showNotification(data.message || 'Your message has been saved.')
      form.value = {name: '', email: '', phone: '', message: ''}
      emit('submitted')
    } else {
      showNotification(data?.message ?? 'Submission failed', 'error')
    }
  } catch (err) {
    showNotification('An unexpected error occurred', 'error')
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <section class="bg-white p-8 rounded-xl shadow-md max-w-2xl mx-auto">
    <h2 class="text-2xl font-bold mb-6">Contact Us</h2>
    <form class="space-y-4" @submit.prevent="submitForm">
      <div>
        <label class="block text-sm font-medium text-gray-700">Full Name</label>
        <input
            v-model="form.name"
            type="text"
            class="w-full mt-1 p-2 border rounded-md"
            placeholder="John Doe"
        >
        <p
            v-for="(msg, i) in fieldErrors('name')"
            :key="`name-${i}`"
            class="text-red-500 text-xs mt-1"
        >
          {{ msg }}
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Email Address</label>
          <input
              v-model="form.email"
              type="email"
              class="w-full mt-1 p-2 border rounded-md"
              placeholder="john@example.com"
          >
          <p
              v-for="(msg, i) in fieldErrors('email')"
              :key="`email-${i}`"
              class="text-red-500 text-xs mt-1"
          >
            {{ msg }}
          </p>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">SA Phone Number</label>
          <input
              v-model="form.phone"
              type="text"
              class="w-full mt-1 p-2 border rounded-md"
              placeholder="071 123 4567"
          >
          <p
              v-for="(msg, i) in fieldErrors('phone')"
              :key="`phone-${i}`"
              class="text-red-500 text-xs mt-1"
          >
            {{ msg }}
          </p>
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Message</label>
        <textarea
            v-model="form.message"
            rows="4"
            class="w-full mt-1 p-2 border rounded-md"
        ></textarea>
        <p
            v-for="(msg, i) in fieldErrors('message')"
            :key="`message-${i}`"
            class="text-red-500 text-xs mt-1"
        >
          {{ msg }}
        </p>
      </div>

      <button
          type="submit"
          :disabled="loading"
          class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 disabled:opacity-50"
      >
        {{ loading ? 'Processing...' : 'Send Message' }}
      </button>
    </form>
  </section>
</template>
