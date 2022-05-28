<template>
    <vue-final-modal v-slot="{ params, close }"
                     v-bind="$attrs"
                     classes="flex justify-center items-center"
                     content-class="relative flex flex-col simple-modal-dimensions mx-4 p-4 border dark:border-gray-800 
                                    rounded bg-white dark:bg-gray-900"
                     :lock-scroll="false"
                     v-on="$listeners">
        <div class="text-right">
            <button class="text-purple-900 focus:outline-none" 
                    @click="$emit('close', close)">
                <i class="fas text-lg fa-times"></i>
            </button>
        </div>
        <div class="flex-grow overflow-y-auto overflow-x-auto">
            <slot v-bind:params="params"></slot>
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

    @media screen and (min-width: 750px) {
        .simple-modal-dimensions {
            max-width: 35rem;
        }
    }
</style>