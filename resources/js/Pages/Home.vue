<template>
    <div class="h-screen flex font-sans bg-gray-100">
        <!-- Sidebar -->
        <AgentSidebar />

        <!-- Chat Window -->
        <main class="flex-1 flex flex-col">
            <header class="p-4 bg-white shadow flex items-center justify-between border-b">
                <h1 class="text-xl font-semibold text-gray-800">Bitsy the Blockchain Helper</h1>
                <span class="text-sm text-gray-500">Friendly L2 Chat Agent</span>
            </header>

            <section ref="chatContainer" class="scroll-smooth flex-1 overflow-y-auto p-6 space-y-4">
                <div v-for="(msg, index) in messages" :key="index" class="flex">
                    <div :class="msg.role === 'user' ? 'ml-auto text-right' : 'mr-auto text-left'">
                        <div
                            :class="msg.role === 'user' ? 'bg-blue-500 text-white' : msg.role === 'assistant' ? 'bg-green-100 text-gray-800' : 'bg-red-100 text-red-800'"
                            class="inline-block px-4 py-2 rounded-lg max-w-xs break-words whitespace-pre-line">
                            {{ msg.content }}
                        </div>
                        <div class="text-xs text-gray-400 mt-1">
                            {{ msg.role }}
                        </div>
                    </div>
                </div>

                <div v-if="isTyping" class="text-left text-sm text-gray-500 animate-pulse">
                    Bitsy is typing<span class="dots ml-1"></span>
                </div>
            </section>

            <form @submit.prevent="sendMessage" class="p-4 bg-white border-t flex gap-2">
                <input
                    v-model="input"
                    type="text"
                    placeholder="Type your message..."
                    class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                <button
                    type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 disabled:opacity-50"
                    :disabled="isTyping">
                    Send
                </button>
            </form>
        </main>
    </div>
</template>

<script setup>
import { ref, nextTick } from 'vue'
import axios from 'axios'
import AgentSidebar from '@/Components/AgentSidebar.vue'

const input = ref('')
const messages = ref([])
const isTyping = ref(false)
const chatContainer = ref(null)

const sessionIdKey = 'chat_session_id'
const sessionId = localStorage.getItem(sessionIdKey) || crypto.randomUUID()
localStorage.setItem(sessionIdKey, sessionId)

const scrollToBottom = () => {
    nextTick(() => {
        setTimeout(() => {
            const el = chatContainer.value
            if (el) el.scrollTop = el.scrollHeight
        }, 0)
    })
}

const sendMessage = async () => {
    const userMessage = input.value.trim()
    if (!userMessage) return

    messages.value.push({ role: 'user', content: userMessage })
    input.value = ''
    isTyping.value = true
    scrollToBottom()

    try {
        const { data } = await axios.post('/chat', {
            input: userMessage,
            session_id: sessionId,
        })
        messages.value.push({ role: 'assistant', content: data.reply })
    } catch (e) {
        messages.value.push({ role: 'error', content: 'Failed to get response.' })
        console.error(e)
    } finally {
        isTyping.value = false
        scrollToBottom()
    }
}
</script>

<style scoped>
@keyframes blink {
    0%, 80%, 100% {
        opacity: 0.2;
    }
    40% {
        opacity: 1;
    }
}

.dots::after {
    content: '...';
    animation: blink 1.4s infinite;
}

.scroll-smooth {
    scroll-behavior: smooth;
}
</style>
