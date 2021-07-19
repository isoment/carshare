<template>
    <div>
        <div class="flex pb-5 items-center">
            <div class="flex-shrink-0 mr-4">
                <img src="/storage/avatar-seeder-img/default.svg" alt="avatar" 
                    class="h-12 w-12 rounded-full">
            </div>
            <div>
                <div class="flex">
                    <span>
                        <i class="fas fa-star text-gray-200"></i>
                        <i class="fas fa-star text-gray-200"></i>
                        <i class="fas fa-star text-gray-200"></i>
                        <i class="fas fa-star text-gray-200"></i>
                        <i class="fas fa-star text-gray-200"></i>
                    </span>
                </div>
                <div class="text-sm text-gray-600 font-bold my-1">
                    <span>No reviews yet</span>
                </div>
                <div v-if="message">
                    <p>{{message}}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            message: String
        }
    }
</script>