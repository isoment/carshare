<template>
    <div>
        <div class="text-red-500 text-xs italic my-2"
            v-for="error in errors"
            :key="error.id"
            v-text="error">
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'errors',
        ],
    }
</script>