<template>
    <aside class="w-64 bg-white shadow-md border-r flex flex-col">
        <div class="p-4 border-b">
            <h2 class="text-lg font-bold text-gray-800">Agents</h2>
        </div>
        <nav class="flex-1 overflow-y-auto">
            <div
                class="px-4 py-2 flex items-center justify-between bg-blue-100 cursor-pointer"
                :class="{ 'bg-blue-100': activeAgent === 'Bitsy' }"
                title="Bitsy is here to help you with general queries.">
                <span class="font-medium text-blue-800">🤖 Bitsy</span>
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
const activeAgent = 'Bitsy'

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
</script>
