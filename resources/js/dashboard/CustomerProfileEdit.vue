<template>
    <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="relative md:mr-20">
            <div class="absolute -top-14 mb-12">
                <img :src="user.profile.image" 
                    alt="avatar"
                    class="rounded-full h-28 w-28 border-8 border-white">
            </div>
            <div class="mt-20">
                <h3 class="text-3xl font-extrabold">{{user.name}}</h3>
            </div>
        </div>
        <div class="mt-12 md:flex md:justify-center md:items-center md:mt-0">
            Col 2
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            user: Object
        },
    }
</script>