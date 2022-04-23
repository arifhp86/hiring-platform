<template>
    <AppLayout :coins="coins">
        <div class="p-10">
            <h1 class="text-4xl font-bold">Candidates</h1>
        </div>
        <div class="p-10 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-5">
            <div v-for="candidate in candidates" :key="candidate.id" class="rounded overflow-hidden shadow-lg">
                <img class="w-full" src="/avatar.png" alt="">
                <div class="px-6 py-4">
                    <div class="font-bold text-xl mb-2">{{candidate.name}}</div>
                    <p class="text-gray-700 text-base">{{candidate.description}}</p>
                </div>
                <div class="px-6 pt-4 pb-2">
                    <span v-for="strength in JSON.parse(candidate.strengths)" :key="strength" class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">{{strength}}</span>
                </div>
                <div class="p-6 flex justify-between items-center">
                    <div>
                        <span v-if="hires.includes(candidate.id)" class="rounded-full px-3 py-1 text-sm font-semibold text-teal-500 flex">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span>Hired</span>
                        </span>
                        <span v-else-if="contacts.includes(candidate.id)" class="rounded-full text-sm font-semibold text-teal-500 flex">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span>Contacted</span>
                        </span>
                    </div>
                    <div>
                        <button v-if="! contacts.includes(candidate.id)" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">Contact</button>
                        <button v-if="! hires.includes(candidate.id)" class="bg-white text-gray-800 font-semibold py-2 px-4 border border-gray-400 hover:bg-teal-100 rounded shadow ml-2">Hire</button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import axios from 'axios';
import AppLayout from "../layouts/AppLayout.vue";

export default {
    components: {
        AppLayout,
    },
    metaInfo: {
      title: 'Candidates',
    },
    data() {
        return {
            candidates: [],
            coins: 0,
            company: null,
        };
    },
    computed: {
        contacts() {
            console.log(this.company.contacts.map(item => item.id));
            return this.company.contacts.map(item => item.id);
        },
        hires() {
            return this.company.hires.map(item => item.id);
        }
    },
    mounted() {
        axios.get('/candidates')
            .then((res) => {
                this.candidates = res.data.candidates;
                this.coins = res.data.coins;
                this.company = res.data.company;
            });
    }
}
</script>
