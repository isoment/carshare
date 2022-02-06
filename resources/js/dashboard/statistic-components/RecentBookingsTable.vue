<template>
    <div class="bg-white rounded-md w-full">
        <div>
            <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 pt-3 pb-4 overflow-x-auto">
                <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Vehicle
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    From
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    To
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Total Price
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Booking Date
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="booking in bookings" :key="booking.id"
                                class="border border-b">
                                <router-link :to="{ 
                                    name: 'customer-show-booking', 
                                    params: { id: booking.booking.id } 
                                }">
                                    <td class="px-5 py-2 bg-white text-sm">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="h-16 w-24 rounded-sm"
                                                    :style="{ 'background-image': 'url(' + booking.vehicle.image + ')' }"
                                                    style="background-size: cover; background-position: 50% 50%;">
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <h6 class="font-bold font-lg tracking-wider font-boldnosans text-gray-700">
                                                    {{booking.vehicle.year}} {{booking.vehicle.make}}
                                                </h6>
                                                <h6 class="text-gray-600">{{booking.vehicle.model}}</h6>
                                            </div>
                                        </div>
                                    </td>
                                </router-link>
                                <td class="px-5 py-2 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">
                                        {{dateFormat(booking.booking.from)}}
                                    </p>
                                </td>
                                <td class="px-5 py-2 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">
                                        {{dateFormat(booking.booking.to)}}
                                    </p>
                                </td>
                                <td class="px-5 py-2 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap font-bold text-xs">
                                        {{moneyFormat(booking.booking.price_total)}}
                                    </p>
                                </td>
                                <td class="px-5 py-2 bg-white">
                                    <span
                                        class="relative inline-block px-3 py-1 text-white leading-tight">
                                        <span aria-hidden
                                            class="absolute inset-0 bg-purple-500 opacity-50 rounded-md"></span>
                                    <span class="relative text-sm">{{dateFormat(booking.booking.created_at)}}</span>
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { dollarFormat } from '../../shared/utils/currency';
    import { monthDayYearNumbericSlash } from '../../shared/utils/dateFormats';

    export default {
        props: ['bookings'],

        methods: {
            moneyFormat(value) {
                return dollarFormat(value);
            },

            dateFormat(value) {
                return monthDayYearNumbericSlash(value);
            },
        },
    }
</script>