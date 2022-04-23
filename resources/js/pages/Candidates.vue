<template>
    <AppLayout :coins="coins">
        <div class="p-10">
            <h1 class="text-4xl font-bold">Candidates</h1>
        </div>
        <div class="p-10 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-5">
            <div v-for="candidate in candidates" :key="candidate.id" class="rounded overflow-hidden shadow-lg">
                <img class="w-full" src="/avatar.png" alt="">
                <div class="px-6 py-4">
                    <div class="font-bold text-xl mb-2">{{ candidate.name }}</div>
                    <p class="text-gray-700 text-base">{{ candidate.description }}</p>
                </div>
                <div class="px-6 pt-4 pb-2">
                    <span v-for="strength in candidate.strengths" :key="strength.id" class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">{{ strength.name }}</span>
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
                        <AppButton v-if="! contacts.includes(candidate.id)" @click="confirmContact(candidate)" class="btn btn-secondary flex items-center justify-between space-x-1">
                            <LoadingIndicator v-if="processingContactRequest === candidate" />
                            <span>Contact</span>
                        </AppButton>
                        <AppButton v-if="! hires.includes(candidate.id)" @click="confirmHire(candidate)" class="btn btn-primary flex items-center justify-between space-x-1 ml-2">
                            <LoadingIndicator v-if="processingHireRequest === candidate" />
                            <span>Hire</span>
                        </AppButton>
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
import AppButton from '@/components/AppButton.vue';

export default {
    components: {
        AppLayout,
        LoadingIndicator,
        CheckIcon,
        AppButton,
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
            if(this.coins < 5) {
                this.$swal({
                    icon: 'error',
                    text: 'Sorry, you don not have enough coins to perform this action, please recharge.'
                });
                
                return;
            }

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
                .then(result => {
                    this.newContacts.push(candidate.id);
                    this.coins = result.data.wallet_balance;
                    this.processingContactRequest = null;

                    this.$swal({
                        toast: true,
                        icon: 'success',
                        position: 'bottom-end',
                        showConfirmButton: false,
                        timer: 3000,
                        title: 'Contacted successfully'
                    });
                });
        },

        confirmHire(candidate) {
            if(! this.contacts.includes(candidate.id)) {
                this.$swal({
                    icon: 'error',
                    text: 'Sorry, you can only hire a candidate after you contact him'
                });

                return;
            }

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
                        position: 'bottom-end',
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
