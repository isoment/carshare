<template>
    <div>
        <div class="relative md:mr-20">
            <div class="absolute -top-8 mb-12">
                <div class="rounded border-2 border-purple-400 bg-white px-6 py-3">
                    <h3 class="text-lg font-boldnosans font-bold">
                        <span>Manage Vehicles</span>
                        <customer-manage-vehicles-filter></customer-manage-vehicles-filter>
                    </h3>
                </div>
            </div>
        </div>

        <div class="py-8">
            Vehicle index will go here
        </div>
    </div>
</template>

<script>
    import CustomerManageVehiclesFilter from './dropdowns/CustomerManageVehiclesFilter';

    export default {
        components: {
            CustomerManageVehiclesFilter
        }
    }
</script>