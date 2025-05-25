<template>
    <div v-if="visible" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded shadow w-full max-w-md">
            <h2 class="text-lg font-semibold mb-4">Create New Agent</h2>

            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Agent Name</label>
                    <input v-model="name" type="text" class="w-full border px-3 py-2 rounded" placeholder="e.g. Gandalf" />
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Personality Prompt</label>
                    <textarea v-model="prompt" rows="5" class="w-full border px-3 py-2 rounded" placeholder="Describe the agent's tone, knowledge, style..."></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Model</label>
                    <input type="text" value="llama3" class="w-full border px-3 py-2 rounded bg-gray-100" disabled />
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" @click="close" class="px-4 py-2 text-gray-600 hover:text-black">Cancel</button>
                    <button
                        type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                        :disabled="loading"
                    >
                        {{ loading ? 'Creating...' : 'Create' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const props = defineProps({ visible: Boolean })
const emit = defineEmits(['close', 'created'])

const name = ref('')
const prompt = ref('')
const loading = ref(false)

const close = () => {
    emit('close')
    name.value = ''
    prompt.value = ''
}

const submit = async () => {
    if (!name.value || !prompt.value) return

    loading.value = true

    try {
        await axios.post('/agent', {
            model: 'llama3',
            prompt: prompt.value,
            name: name.value,
        })
        emit('created')
        close()
    } catch (error) {
        alert('Failed to create agent.')
        console.error(error)
    } finally {
        loading.value = false
    }
}
</script>
