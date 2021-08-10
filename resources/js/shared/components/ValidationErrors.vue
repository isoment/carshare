<template>
    <div>
        <div class="text-red-500 text-xs italic my-1"
             v-if="errors"
             v-text="errors[0]">
        </div>
        <!--
            Maybe we don't want to display all the errors?
            ----------------------------------------------             
            v-for="error in errors"
            :key="error.id"
            v-text="error">
         -->
    </div>
</template>

<script>
    export default {
        props: [
            'errors',
        ],
    }
</script>