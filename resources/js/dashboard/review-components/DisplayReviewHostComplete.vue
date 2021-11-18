<template>
    <div>
        <div v-for="review in reviews.data" :key="review.id">
            <div class="mt-2">
                <div>{{review.host.name}}</div>
                <div>{{review.hostReview.id}}</div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            reviews: Object
        }
    }
</script>