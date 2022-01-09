<template>
    <vue-final-modal v-slot="{ params, close }"
                     v-bind="$attrs"
                     classes="flex justify-center items-center"
                     content-class="relative flex flex-col simple-modal-dimensions mx-4 p-4 border dark:border-gray-800 
                                    rounded bg-white dark:bg-gray-900"
                     v-on="$listeners">
        <div class="flex-grow overflow-y-auto overflow-x-auto">
            <slot v-bind:params="params"></slot>
        </div>
        <div class="flex-shrink-0 flex justify-center items-center pt-4">
            <button class="bg-purple-400 px-2 py-1 text-white focus:outline-none" 
                    @click="$emit('close', close)">
                Close
            </button>
        </div>
    </vue-final-modal>
</template>

<script>
    import { VueFinalModal, ModalsContainer } from 'vue-final-modal';

    export default {
        components: {
            VueFinalModal,
            ModalsContainer
        },

        name: 'SimpleModal',

        inheritAttrs: false
    }
</script>

<style>
    .simple-modal-dimensions {
        max-height: 80%;
        max-width: 95%;
    }
</style>