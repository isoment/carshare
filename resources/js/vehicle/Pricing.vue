<template>
    <div v-if="pricing">
        <div class="mb-1">
            <span class="font-bold text-xl font-boldnosans">${{formatNumber(pricing.price_day)}}</span>
            <span>/</span>
            <span>day</span>
        </div>
        <div class="text-xs text-gray-600 mb-3 flex justify-between items-center">
            <div class="underline">${{formatNumber(pricing.total)}} est total</div>
            <div class="text-purple-400 font-bold">{{pricing.days}} days</div>
        </div>
        <hr>
    </div>
</template>

<script>
    import { wholeDollars } from './../shared/utils/currency';

    export default {
        props: {
            pricing: Object
        },

        methods: {
            formatNumber(value) {
                return wholeDollars(value);
            }
        },
    }
</script>