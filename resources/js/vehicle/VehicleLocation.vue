<template>
    <div>
        <gmap-map :center="formattedCoord"
                  :zoom="14"
                  style="width: 100%; height: 100%"
                  :options="googleMapOptions()">
            <gmap-marker :position="formattedCoord"
                         :icon="{url:'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEQAAABECAYAAAA4E5OyAAADuklEQVR4nO2cbUsUURTHf3t9yEoFBXuQjECTyMBSSQmCetWbvlNfqC/Qq14EkZVWkBGlGBVpGUVpWT7Gcf8jW3d3HbdZd55+cGGZucyc85+792HuOVO4xTZ1pgloBTqAHqBLv9uBNqBZdYxNYAP4BawAy8BXYEm/11SnbjTX6cIFoBM4DpwGTkqI/8GEWQDeAh+B7xD904xakBa1grMqR7watdOlch74CbxWsdazHtVNohLEWsQZGdvvnY0eE3pYZQ54AbyJosVEIYj9HS6qRTSCfhVrLU/1t2qYIFeAC+ocG409kD7gOXD/oAWxzvIq0OudaSz2YMZk1z11vvvC1WD+EHAzhmKU0isbh7wze7CfFlKQ+pdL5g1x5ihwTR3w47AdblhBTIAJYETCJIXAbpsYPggzqQvzlynooqMJEyOgINsnwtgfRpAxXTDpjMqXquwlyDlg3DuaXMblU0WqCdINXN+jTtJw8qm7kt2VnD0E3NDaJG20yLdD5fyqJMiwFmlppUc+epQT5ISG17QzIl+rthAbty9p3E47rfK1qZogfQe0fI8L/fK5rCA2ax0qI1KacfK5uZwgtoLNUusI6JfvOwSC2JR2wKuaHQaCaX0giL0FH8ywIIPSYFeQY8Bhr1p2OCwNdgRxekGcdUwD5zQen8q6GtKg1WlDqdM7nT12dHApX7Pslx4XwRZjmuhywXCTs0OH0y58TpF2F5Ndt7jQ5uoYEpFEml1CNp0OiqYsLfVD4eodopQwNp1iunKKbDgFuOUU+eUU7ZdTZMUp3DGnyLJTuGNOka9OYY05RZacAmC/54IUdXAKl37vnc4epsGaCbKloNesYxpsBVP3T8BqhhVZlQa72xA29L7yqmWHV8H0IxDEQhZnMyzIbBC2WbratajfOa9q+pkrjXguFcQWeTPqZLPClnzeKCeI8S5jrWROPu/yryD2buSJ5iZpZ02+blYTxFgEpr2j6WNavv5FOUGMZylf4yzJR49KgvwG7kSZyxYj1uXb73ImVRLE+ALcTdmosyWfvnhnRDVBjJfApHc0uUzKp4qE2Yaw5Jsp72jymJIvVQmza7et5BsSmECE7J+WD3tmVYXdxtxUpuNaglLMkN0P65Fihi74SFnVE8ppizM/1Cpm9mNjLRvddoPPMU1TDfhQa5pqrTv/dqPbMUtkRptuDUlkDrAbzzc41T0gFqnuyIBFje8H9TGEUmL3MQRkyLzeXNfrcxmlxP5zGQHr6tAW1Hwz/0GVADP0m4o16WR8cgf4A5qKxdm2aRLIAAAAAElFTkSuQmCC'}"
                         :clickable="false"
                         :draggable="false">
            </gmap-marker>
        </gmap-map>
    </div>
</template>

<script>
    export default {
        props: {
            coordinates: Object
        },

        computed: {
            formattedCoord() {
                return {
                    lat: this.formatCoord(this.coordinates.lat),
                    lng: this.formatCoord(this.coordinates.lng)
                }
            }
        },

        methods: {
            googleMapOptions() {
                return {
                    disableDefaultUI: true,
                    draggable: false,
                    styles: [
                        {
                            featureType: 'poi',
                            stylers: [
                                {visibility: 'off'}
                            ]   
                        }
                    ],
                    scrollwheel: false
                }
            },

            formatCoord(coordinate) {
                return parseFloat(coordinate);
            }
        }
    }
</script>