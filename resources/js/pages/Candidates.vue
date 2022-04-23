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
                            <CheckIcon />
                            <span>Hired</span>
                        </span>
                        <span v-else-if="contacts.includes(candidate.id)" class="rounded-full text-sm font-semibold text-teal-500 flex">
                            <CheckIcon />
                            <span>Contacted</span>
                        </span>
                    </div>
                    <div class="flex">
                        <button v-if="! contacts.includes(candidate.id)" @click="confirmContact(candidate)" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow flex items-center justify-between space-x-1">
                            <LoadingIndicator v-if="processingContactRequest === candidate" />
                            <span>Contact</span>
                        </button>
                        <button v-if="! hires.includes(candidate.id)" @click="confirmHire(candidate)" class="bg-white text-gray-800 font-semibold py-2 px-4 border border-gray-400 hover:bg-teal-100 rounded shadow ml-2 flex items-center justify-between space-x-1">
                            <LoadingIndicator v-if="processingHireRequest === candidate" />
                            <span>Hire</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import axios from 'axios';
import AppLayout from "@/layouts/AppLayout.vue";
import LoadingIndicator from '@/components/LoadingIndicator.vue';
import CheckIcon from '@/components/CheckIcon.vue';

export default {
    components: {
        AppLayout,
        LoadingIndicator,
        CheckIcon,
    },
    metaInfo: {
      title: 'Candidates',
    },
    data() {
        return {
            candidates: [],
            coins: 0,
            company: null,
            newContacts: [],
            newHires: [],
            processingContactRequest: null,
            processingHireRequest: null,
        };
    },
    computed: {
        contacts() {
            return [
                ...this.company.contacts.map(item => item.id),
                ...this.newContacts
            ];
        },
        hires() {
            return [
                ...this.company.hires.map(item => item.id),
                ...this.newHires
            ];
        }
    },
    methods: {
        confirmContact(candidate) {
            this.$swal({
                icon: 'info',
                html: `Contacting <strong>${candidate.name}</strong> will cost <strong>5 coins</strong> from your wallet, click confirm button to proceed`,
                showCancelButton: true,
                confirmButtonText: 'Confirm'
            }).then(result => {
                if(result.isConfirmed) {
                    this.contactCandidate(candidate);
                }
            });
        },

        contactCandidate(candidate) {
            this.processingContactRequest = candidate;
            axios.post('candidates/'+candidate.id+'/contact')
                .then(res => {
                    this.newContacts.push(candidate.id);
                    this.coins = res.data.wallet_balance;
                    this.processingContactRequest = null;

                    this.$swal({
                        toast: true,
                        icon: 'success',
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        title: 'Contacted successfully'
                    });
                });
        },

        confirmHire(candidate) {
            this.$swal({
                icon: 'info',
                html: 'Are you sure you want to hire this candidate, we will <strong>refund you 5 coins</strong> if you do, click confirm button to proceed',
                showCancelButton: true,
                confirmButtonText: 'Confirm'
            }).then(result => {
                if(result.isConfirmed) {
                    this.hireCandidate(candidate);
                }
            });
        },

        hireCandidate(candidate) {
            this.processingHireRequest = candidate;
            axios.post('candidates/'+candidate.id+'/hire')
                .then(res => {
                    this.newHires.push(candidate.id);
                    this.coins = res.data.wallet_balance;
                    this.processingHireRequest = null;

                    this.$swal({
                        toast: true,
                        icon: 'success',
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        title: 'Hired successfully'
                    });
                });
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
