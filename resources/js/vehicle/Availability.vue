<template>
    <div class="w-3/4 md:w-full">
        <div class="flex flex-col">
            <label for="start" class="font-bold text-xs tracking-wider">Trip start</label>
            <input type="text" class="border border-gray-300 mt-1 py-1 px-2 focus:outline-none">
        </div>
        <div class="flex flex-col mt-3">
            <label for="start" class="font-bold text-xs tracking-wider">Trip end</label>
            <input type="text" class="border border-gray-300 mt-1 py-1 px-2 focus:outline-none">
        </div>
        <div class="mt-6">
            <button class="bg-purple-500 text-white font-bold py-2 w-full text-sm 
                            tracking-widest hover:bg-purple-400 transition-all duration-200">Continue</button>
        </div>
    </div>
</template>

<script>
    export default {
        
    }
</script>