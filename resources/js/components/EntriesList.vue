<script setup>
import {inject, onMounted, ref} from 'vue'
import {fetchEntries as fetchEntriesApi} from '../api.js'

const showNotification = inject('showNotification')

const entries = ref([])
const meta = ref({})
const searchQuery = ref('')

let debounceTimer = null

const loadEntries = async (page = 1) => {
  try {
    const res = await fetchEntriesApi({page, search: searchQuery.value})
    if (res.status === 'success') {
      entries.value = res.data
      meta.value = res.meta
    }
    else {
      showNotification(res.message, 'error')
    }
  } catch (err) {
    showNotification('Could not load entries', 'error')
  }
}

const debounceSearch = () => {
  clearTimeout(debounceTimer)
  debounceTimer = setTimeout(() => {
    loadEntries(1)
  }, 300)
}

onMounted(() => {
  loadEntries()
})
</script>

<template>
  <section class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="p-6 border-b flex justify-between items-center">
      <h2 class="text-2xl font-bold">Recorded Entries</h2>
      <button type="button" class="text-sm text-blue-600 hover:underline" @click="loadEntries(1)">
        Refresh
      </button>
    </div>

    <div class="p-4 border-b">
      <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
        <input
            v-model="searchQuery"
            type="text"
            placeholder="Search name, email or phone..."
            class="pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none w-full md:w-80"
            @input="debounceSearch"
        >
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left">
        <thead class="bg-gray-50 uppercase text-xs font-semibold text-gray-600">
        <tr>
          <th class="px-6 py-3">Name</th>
          <th class="px-6 py-3">Contact</th>
          <th class="px-6 py-3">Message</th>
          <th class="px-6 py-3">Date</th>
        </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
        <tr v-for="entry in entries" :key="entry.id" class="hover:bg-gray-50">
          <td class="px-6 py-4 font-medium">{{ entry.name }}</td>
          <td class="px-6 py-4 text-sm">
            <div>{{ entry.email }}</div>
            <div class="text-gray-400">{{ entry.phone }}</div>
          </td>
          <td class="px-6 py-4 text-sm text-gray-600 truncate max-w-xs">{{ entry.message }}</td>
          <td class="px-6 py-4 text-xs text-gray-400">{{ entry.created_at }}</td>
        </tr>
        <tr v-if="entries.length === 0">
          <td colspan="4" class="px-6 py-10 text-center text-gray-400">No entries found.</td>
        </tr>
        </tbody>
      </table>
    </div>

    <div v-if="meta.total_pages > 1" class="p-4 border-t flex justify-center space-x-2">
      <button
          type="button"
          class="px-3 py-1 border rounded disabled:opacity-30"
          :disabled="!meta.has_prev"
          @click="loadEntries(meta.current_page - 1)"
      >
        Prev
      </button>
      <span class="px-3 py-1">Page {{ meta.current_page }} of {{ meta.total_pages }}</span>
      <button
          type="button"
          class="px-3 py-1 border rounded disabled:opacity-30"
          :disabled="!meta.has_next"
          @click="loadEntries(meta.current_page + 1)"
      >
        Next
      </button>
    </div>
  </section>
</template>
