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
                <h4>Hello There</h4>
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
        }
    }
</script>

<style>
    .demo-info-button {
        margin-top: 5px;
    }
</style>