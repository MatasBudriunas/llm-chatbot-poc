<template>
    <aside class="w-64 bg-white shadow-md border-r flex flex-col">
        <div class="p-4 border-b flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-800">Agents</h2>
            <button
                class="text-sm text-blue-600 hover:underline"
                @click="$emit('open-create-agent')"
            >
                + New
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto">
            <div
                v-for="agent in agents"
                :key="agent.slug"
                :class="[
                    'px-4 py-2 flex items-center justify-between cursor-pointer hover:bg-blue-50',
                    activeSlug === agent.slug ? 'bg-blue-100 text-blue-800 font-semibold' : 'text-gray-700'
                ]"
                @click="selectAgent(agent.slug)"
            >
                <span>{{ agent.icon }} {{ agent.name }}</span>
                <span class="text-xs text-white bg-blue-600 rounded-full px-2 py-0.5">Online</span>
            </div>

            <div
                v-for="agent in lockedAgents"
                :key="agent.name"
                class="px-4 py-2 text-gray-400 flex items-center justify-between opacity-60 cursor-not-allowed hover:bg-gray-50"
                :title="agent.tooltip"
            >
                <span>{{ agent.icon }} {{ agent.name }}</span>
                <span class="text-xs">🔒</span>
            </div>
        </nav>
    </aside>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import axios from 'axios'

const agents = ref([])
const activeSlug = ref('bitsy') // default
const emit = defineEmits(['agent-selected', 'open-create-agent'])

const selectAgent = (slug) => {
    activeSlug.value = slug
    emit('agent-selected', slug)
}

const lockedAgents = [
    {
        name: 'Satoshi',
        icon: '🧙',
        tooltip: 'Unlock access to the founder of the blockchain.',
    },
    {
        name: 'Vitalik',
        icon: '🦄',
        tooltip: 'A cryptic oracle of DeFi truths. Locked for now.',
    },
    {
        name: '0xOracle',
        icon: '🔮',
        tooltip: 'The chainseer of forgotten ledgers. Access denied.',
    },
]

onMounted(async () => {
    const { data } = await axios.get('/agents')
    agents.value = data.map(agent => ({
        ...agent,
        icon: '🤖'
    }))

    if (agents.value.length) {
        const fallback = agents.value.find(a => a.slug === 'bitsy') || agents.value[0]
        activeSlug.value = fallback.slug
        emit('agent-selected', fallback.slug)
    }
})
</script>
