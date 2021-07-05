<template>
    <div>
        <div class="flex border-b border-gray-200 mt-5 pb-5"
             v-for="review in reviews.data" :key="review.id">
            <div class="flex-shrink-0 mr-4">
                <img src="/img/avatar-female.jpeg" alt="avatar" 
                    class="h-12 w-12 rounded-full">
            </div>
            <div>
                <div class="flex">
                    <span><i class="fas fa-star text-purple-500 text-lg"></i></span>
                    <span><i class="fas fa-star text-purple-500 text-lg"></i></span>
                    <span><i class="fas fa-star text-purple-500 text-lg"></i></span>
                    <span><i class="fas fa-star text-purple-500 text-lg"></i></span>
                    <span><i class="fas fa-star text-purple-500 text-lg"></i></span>
                </div>
                <div class="text-xs my-1">
                    <span>User Name</span>
                    <span class="text-gray-500">Mar 14, 2020</span>
                </div>
                <div>
                    <p>{{review.content}}</p>
                </div>
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