<template>
    <div>
        <button class="focus:outline-none demo-info-button mr-2"
                :class="[iconColorStyle, hoverColorStyle]"
                @click="showDemoInfoModal = true">
            <svg class="w-7 h-7" fill="none" 
                 stroke="currentColor" 
                 viewBox="0 0 24 24" 
                 xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </button>

        <simple-modal v-model="showDemoInfoModal" @close="closeDemoInfoModal">
            <div class="text-gray-800 text-sm">
                <div class="relative mb-6 z-30">
                    <h4 class="font-bold text-2xl z-40">Demo Mode</h4>
                    <div class="absolute bg-purple-200 demo-header-bar"></div>
                </div>
                <p>Welcome to Carshare! The application is currently in demo mode which means that the database
                    has been seeded with data for demonstration purposes. You will notice some Lorem Ipsum text
                    and images that don't match the vehicle type. Thats normal. There are also some
                    dummy links to enhance the UI of the site and fill it out a bit.
                </p>
                <p class="my-4">
                    For the stripe checkout process you can use the testing card number below. It works
                    with any CCV and expiration date in the future...
                </p>
                <p class="font-bold text-lg">4242 4242 4242 4242</p>
                <p class="my-4">Click the info icon in the top right navigation bar to bring up this dialogue at any time.</p>
                <div class="text-center">
                    <button class="bg-purple-400 text-white font-bold px-3 py-1 focus:outline-none"
                            @click="closeDemoInfoModal()">Got It!</button>
                </div>
            </div>
        </simple-modal>
    </div>
</template>

<script>
    import SimpleModal from '../../shared/components/SimpleModal.vue';

    export default {
        components: {
            SimpleModal
        },

        props: {
            color: String,
            hoverColor: String
        },

        computed: {
            iconColorStyle() {
                return this.color ? `text-${this.color}` : 'text-gray-400';
            },

            hoverColorStyle() {
                return this.hoverColor ? `hover:text-${this.hoverColor}` : 'hover:text-white'
            }
        },

        data() {
            return {
                showDemoInfoModal: false
            }
        },

        methods: {
            closeDemoInfoModal() {
                this.showDemoInfoModal = false;
            }
        },

        created() {
            if (!localStorage.appPreviouslyLoaded) {
                this.showDemoInfoModal = true;

                localStorage.setItem('appPreviouslyLoaded', 1);
            }
        }
    }
</script>

<style>
    .demo-info-button {
        margin-top: 5px;
    }

    .demo-header-bar {
        height: 1rem;
        width: 6.7rem;
        z-index: -1;
        top: 13px;
        left: 8px;
    }
</style>