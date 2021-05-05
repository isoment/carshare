<template>
    <div>
        <div class="text-gray-600 h-screen flex items-center justify-center">
            <div class="text-center">
                <div class="text-7xl">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <h1 class="text-4xl font-bold1 mt-4 mb-2">Page Not Found</h1>
                <div class="text-gray-500">
                    <span>The page you requested does not exist, try checking the</span>
                    <span>
                        <router-link :to="{ name: 'main-page' }" 
                                     class="text-purple-300 hover:text-purple-500 font-bold">
                            home page
                        </router-link>
                    </span>
                    <span>for more options.</span>
                </div>
            </div>
        </div>
    </div>
</template>