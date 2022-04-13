<template>
    <div class="relative">
        <div class="search rounded-sm shadow-md bg-white px-3 py-1">
            <input type="text" v-model="searchAddress"
                   class="text-sm focus:outline-none" 
                   v-on:change="searchLocation()"
                   placeholder="Address" />
            <button class="focus:outline-none">
                <i class="fas fa-search-location text-purple-500"></i>
            </button>
        </div>
        <gmap-map style="width: 100%; height: 100%"
                  :center="location"
                  :zoom="14"
                  :options="options">
            <gmap-marker :position="location"
                         :icon="{url:'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABMAAAATCAYAAAByUDbMAAACGklEQVQ4ja2UTUgUYRjHf7O7bs0mruuh09qy7mQJhiwJxpKJgQhKnYNOwRb0ISYenLOH8BQhfRFdkrp26lQHFaRSgqEIK1kr2o5h20az66pMPIOzjTu2bEv/0/vM+/x/8768z/MolmWxiw4CZ4GTQALYB5hABpgFHgErlTYbpmtG+cNUJnkdGHPi719L5b1INOj23tA1Y8zlI+AKGoE54OinxQJLD/J8WMhRMAtYyOkVQqqKdixMz7kmEqnQ1alMsg/o0zXjpzACrj89B44szuR5ci3LxmaJnbIwCyZvZk2W578xNNFKx6CajESDL4DO8jXlyMDo0kyex5MfbWMtOq23kUqHJfMmMCKwKJDNGkXunVllY6vyRH+XXwmQfqgR71El54APuGjf8f6PfwKJtqxNFu7knPCSwAbkxd7NrXmSa9HKyzXnxQcE1iar4nqxLpjroeICa/Rk1KeQwH79J5gpMKkF9gT3enZrUcDf4GR9FthTaZPDvS11wbTuFqfNngnsrqxS55vw+xo8ydWk4KP3crOTcVtgWWA61q0yPNFaxerV0HhMelS+3wK+CEymxijwtmNQtVvE7wt4jG75FD/D43E6T9mgZV0zrsjC7UpFosH5VDqY3N+esDti9VWO9dKf+pNHinWFOX6hmfZ+G/QaOOHs7zbP7KZ34irzbHr7Ro6vPDUqdWh70va7Jq3Uo5SRM2nf7zABvwELBq8AybS4aAAAAABJRU5ErkJggg=='}"
                         :clickable="false"
                         :draggable="false">
            </gmap-marker>
        </gmap-map>
    </div>
</template>

<script>
    import { gmapApi } from 'vue2-google-maps';

    export default {
        computed: {
            google: gmapApi,
        },

        data() {
            return {
                location : {lat:41.876909, lng:-87.608699},
                searchAddress: null,
                options: {
                    disableDefaultUI:true,
                    zoomControl: true,
                }
            }
        },

        methods: {
            searchLocation: function() {
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({'address': this.searchAddress}, (results, status) => {
                    if (status === 'OK') {
                        this.location.lat = results[0].geometry.location.lat();
                        this.location.lng = results[0].geometry.location.lng();
                        this.$emit('changedCoordinates', this.location);
                    } else {
                        this.$store.dispatch('addNotification', {
                            type: 'error',
                            message: 'This address is not valid'
                        });
                    }
                });
            }
        },
    }
</script>

<style>
    .search {
        position: absolute;
        top: 1rem;
        left: 0.5rem;
        z-index: 99;
    }
</style>