<template>
    <div class="mx-4 my-2">
        <div class="pb-2 mb-4 border-b border-gray-200">
            <h5 class="font-semibold font-boldnosans">Reviews of renters.</h5>
            <h6 class="text-gray-600 text-xs font-light">
                These are the reviews you have left for users who booked your vehicles.
            </h6>
        </div>

        <div>

        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            async fetchReviews() {
                let response = await axios.get(`/api/dashboard/renter-users-reviews-complete`);

                console.log(response);
            }
        },

        created() {
            this.fetchReviews();
        }
    }
</script>