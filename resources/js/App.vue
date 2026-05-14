<script setup>
import { ref, provide } from 'vue'
import NavBar from './components/NavBar.vue'
import NotificationToast from './components/NotificationToast.vue'
import ContactForm from './components/ContactForm.vue'
import EntriesList from './components/EntriesList.vue'

const view = ref('form')
const notification = ref({ type: '', message: '' })

let notificationTimer = null
const showNotification = (message, type = 'success') => {
    notification.value = { message, type }
    clearTimeout(notificationTimer)
    notificationTimer = setTimeout(() => {
        notification.value = { type: '', message: '' }
    }, 5000)
}

provide('showNotification', showNotification)

const handleSubmitted = () => {
    view.value = 'list'
}
</script>

<template>
    <NotificationToast :notification="notification" />
    <NavBar v-model:view="view" />

    <main class="max-w-5xl mx-auto px-4 pb-12">
        <ContactForm v-if="view === 'form'" @submitted="handleSubmitted" />
        <EntriesList v-else-if="view === 'list'" />
    </main>
</template>
