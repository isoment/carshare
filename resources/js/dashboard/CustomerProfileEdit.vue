<template>
    <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="relative md:mr-20">
            <div class="absolute -top-14 mb-12">
                <img :src="user.profile.image" 
                    alt="avatar"
                    class="rounded-full h-28 w-28 border-8 border-white">
            </div>
            <div class="mt-20">
                <!-- Change photo -->
                <div>
                    <form>
                        <div>
                            <div class="bg-purple-500 hover:bg-purple-400 transition-all duration-200 
                                    px-4 py-2 text-white font-bold w-2/3 md:w-1/2 cursor-pointer"
                                 @click="pickImage">
                                Change profile photo
                            </div>
                            <input type="file" 
                                   accept="image/*" 
                                   class="hidden"
                                   ref="fileInput"
                                   @change="changeAvatar">
                        </div>
                        <p class="text-xs text-gray-500 mt-3">
                            Add a face to your account. This makes it easier for hosts and renters to recognize
                            each other.
                        </p>
                    </form>
                </div>

                <!-- Name -->
                <div class="mt-6">
                    <h3 class="text-3xl font-extrabold">{{user.name}}</h3>
                </div>

                <!-- Edit profile -->
                <div class="mt-6">
                    <div class="flex flex-col">
                        <label for="lives" 
                               class="text-gray-400 text-xs font-bold uppercase 
                                       tracking-wider mb-2">Lives</label>
                        <input type="text" name="lives" placeholder="Chicago, IL / Reno, NV"
                               class="px-2 py-1 border border-gray-300 text-sm"
                               v-model="profile.location">
                        <h5 class="text-xs text-gray-500 my-2">Joined {{ dateMonthYear(user.created_at) }}</h5>
                    </div>
                    <h4 class="text-lg font-bold text-gray-700 mt-6 mb-2">Tell us more...</h4>
                    <div class="flex flex-col mt-4 mb-3">
                        <label for="languages" 
                               class="text-gray-400 text-xs font-bold uppercase 
                                       tracking-wider mb-2">Languages</label>
                        <input type="text" name="languages"
                               class="px-2 py-1 border border-gray-300 text-sm"
                               v-model="profile.languages">
                    </div>
                    <div class="flex flex-col mb-3">
                        <label for="work" 
                               class="text-gray-400 text-xs font-bold uppercase 
                                       tracking-wider mb-2">Work</label>
                        <input type="text" name="work"
                               class="px-2 py-1 border border-gray-300 text-sm"
                               v-model="profile.work">
                    </div>
                    <div class="flex flex-col mb-3">
                        <label for="school" 
                               class="text-gray-400 text-xs font-bold uppercase 
                                       tracking-wider mb-2">School</label>
                        <input type="text" name="school"
                               class="px-2 py-1 border border-gray-300 text-sm"
                               v-model="profile.school">
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="flex flex-col mb-2 mt-3 md:mt-20">
                <label for="about"
                        class="text-gray-400 text-xs font-bold uppercase 
                                tracking-wider mb-2">About {{user.name}}</label>
                <textarea name="about" rows="10"
                          class="px-2 py-1 border border-gray-300 text-sm"
                          v-model="profile.about"></textarea>
            </div>
            <div class="mt-3">
                <p class="text-xs text-gray-500">
                    Tell other users about yourself and why you’re a responsible, trustworthy person. Share your favorite travel experiences, 
                    your hobbies, your dream car, or your driving experience. Feel free to include links to your LinkedIn, 
                    Twitter, or Facebook profiles so they get to know you even better.
                </p>
            </div>
            <div class="md:text-right mt-3">
                <button class="md:w-1/2 text-center bg-purple-500 hover:bg-purple-400 transition-all 
                               duration-200 px-4 py-2 text-white font-bold"
                        @click="profileUpdate">Save changes</button>
            </div>
        </div>
    </div>
</template>

<script>
    import { dateFormatMonthYear } from './../shared/utils/dateFormats';

    export default {
        props: {
            user: Object
        },

        data() {
            return {
                profile: {
                    location: null,
                    languages: null,
                    work: null,
                    school: null,
                    about: null
                }
            }
        },

        methods: {
            setProfileState() {
                this.profile.location = null ?? this.user.profile.location;
                this.profile.languages = null ?? this.user.profile.languages;
                this.profile.work = null ?? this.user.profile.work;
                this.profile.school = null ?? this.user.profile.school;
                this.profile.about = null ?? this.user.profile.about;
            },

            dateMonthYear(date) {
                return dateFormatMonthYear(date);
            },

            pickImage() {
                this.$refs.fileInput.click();
            },

            async changeAvatar(event) {
                let image = event.target.files[0];

                const formData = new FormData;

                formData.set('image', image);

                try {
                    let result = (await axios.post('/api/dashboard/update-avatar', formData));

                    // Emit event to parent to refresh avatar
                    this.$emit('refreshAvatar');

                    this.$store.dispatch('setUserAvatar', result.data);
                } catch(error) {
                    this.$store.dispatch('addNotification', {
                        type: 'error',
                        message: error.response.data.errors.image[0]
                    })
                }
            },

            async profileUpdate() {
                try {
                    await axios.put('/api/dashboard/update-profile', this.profile);

                    this.$emit('profileWasEdited');

                    this.$store.dispatch('addNotification', {
                        type: 'success',
                        message: 'Profile updated'
                    });
                } catch(error) {
                    console.log(error);
                }
            }
        },

        created() {
            this.setProfileState();
        }
    }
</script>