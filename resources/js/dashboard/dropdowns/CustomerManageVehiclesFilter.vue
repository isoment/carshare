<template>
    <div class="inline">
        <!-- Toggle Button -->
        <button>
            <i class="fas fa-cogs ml-1 cursor-pointer"
            ref="menubutton"
            @click="toggleFilterMenu"></i>
        </button>

        <!-- Filter Dropdown -->
        <transition name="fade">
            <div class="absolute py-4 px-2 bg-white border border-grey-50 rounded-md shadow-md 
                        left-0 top-16 filtermenu text-sm font-normal"
                ref="filtermenu"
                v-if="menuState">
                Test menu content
            </div>
        </transition>
    </div>
</template>

<script>
    import dropdownToggle from './../../shared/mixins/dropdownToggle';

    export default {
        mixins: [dropdownToggle]
    }
</script>

<style scoped>
    .filtermenu {
        width: 16rem;
    }
</style>