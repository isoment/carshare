<template>
    <div>
        <gmap-map :center="formattedCoord"
                  :zoom="14"
                  style="width: 100%; height: 100%"
                  :options="googleMapOptions()">
            <gmap-marker :position="formattedCoord"
                         :icon="{url:'data:image/png,%89PNG%0D%0A%1A%0A%00%00%00%0DIHDR%00%00%00%1D%00%00%00%1D%08%06%00%00%00V%93g%0F%00%00%00%09pHYs%00%00s%F8%00%00s%F8%01D%F6%FA%DA%00%00%00%19tEXtSoftware%00www.inkscape.org%9B%EE%3C%1A%00%00%00%25tEXtdate%3Acreate%002012-07-26T13%3A46%3A41-07%3A00m%0B%B4%B0%00%00%00%25tEXtdate%3Amodify%002012-07-26T13%3A46%3A41-07%3A00%1CV%0C%0C%00%00%02%0EIDATH%89%CD%D7-O%5CA%14%C6%F1%DF%CCv%C3%22H%20%A9jP8%14%21%D8.%9F%03%81%AF%C0%90%D445%1B%0C%A9%A8%C0%AC%C0%23%F8%1Ad%B1%84%A0p%28%12%0C%09%24%88B%C9%EE%A9%B8%B3%CD%0D%2F%5D%D2%EEn%FA%C8%993%F3%9Fs%CF%DC%7B%9E%9B%8C%D0%B6%98%9Fc%05kh%27%96%B1%18%B4%12%F7%B8%0C%CE%D1%C3%C9%1Dg%7B%D2%ED%9F%F6L%AFMtD%EB%91v%83%8D%C4z%E2C0%9B%5EX%13D%E2Gp%15%1C%F59l%D2%DB%91%EE%DF%04%ED%88%8C%25la3X%40.%C1%AF%1Er%08%C7%20q%83%03tq%B1%23%0D%EAq%F9%E9%C2%07V%07t%83Ox%9Fh%24%D2%28%E0%F0P%89%06%DE%07%9F%06t%1FX%7D5%D3%8E%C8%0F%AC6%F9%9E%AA%DA5FAF%29%E8%E3%F8%27%9Fg8%1Df%5C%CFt%A9%C9%EE%B8%80P%F6%F9%D8dWU2%BF%A1%1D%D1%C2%D68%81up%A2%8D%AD%C2%A9%A0%8F%D5%E0fbv%9C%C0%1Ax%16%9B%85%23o%8B%F9%06%1B%E5%96NL%C1B%83%8Dm1%9F%E7XI%AC%7B%E1%26%8FY9%B1%3E%C7J%C6Z%E2%C3%84%81%A0p%D62%DA%AF%7Di%C6%0CLQ%D5%B6%FD.%B1%3Ci%60%1D%8C%E5%8C%C5i%00kZ%CCAk%9A%C4%A0%95K%7B%9A%9A%12%F7%19%97%D3%84%E22%07%E7%A5%25M%5CA%04%E7%19%BD%C4%8Fi%40%0B%A7%97qR%3A%FED%B3-Y%5E%E1%24%DFq%16%1Ca0j%E1%3Fj%10%1C%DDq%96%F7%A4%DB%3E%87%C5bLL%89%9B%3E%87%7B%D2m%86f%E5%E4%0EbB%B5-%FB%1E%14N%D5Y%8Ak%EB%06%BDb1%C6%09%ECG%05%EB%0E%DDa%BD%9D%5D%3C%F2%15%C7%E3%02%0F%3DR%D9%F7b8%FE%ECC%FFE%AC%D5%BC%D2_%3B%89%A8%7Cp%EF%91%AF%DF%A4%93%FA%DC%B3%C6%3D%C3i%AE%FC%D2%3E%AE%CB%E3%89%B7%BCR%25%AE%8F%EB%C4~fk%86%D3%A7q%FF%87%C3%7F%AAI%FC%CB%FC%02%EB%2C%C87u%19%C5I%00%00%00%00IEND%AEB%60%82'}"
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