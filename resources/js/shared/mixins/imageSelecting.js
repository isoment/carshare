/*
    This mixin contains the logic for selecting and removing an image and previews 
    from the state.
*/
export default {
    data() {
        return {
            images: [],
            previews: [],
        }
    },

    methods: {
        pickImage() {
            this.$refs.fileInput.click();
        },

        // Once files are selected, verfiy the extensions are valid, calculated how many image slots
        // are yet unfilled out of the 12 maximum and add them to the images and previews state.
        imageSelected(event) {
            let files = event.target.files;
            let allowedExtensions = /(\jpg|\jpeg|\webp|\bmp|\png|\.gif)$/i;
            let imageCount = this.images.length;
            let maxAllowedCount = 12 - imageCount;

            if (files) {
                for (let i = 0; i < maxAllowedCount; i++) {
                    if (files[i]) {
                        if (allowedExtensions.exec(files[i].type)) {
                            // Create a unique id
                            let id = "id" + Math.random().toString(16).slice(2);

                            this.images.push({
                                id: id,
                                file: files[i]
                            });

                            let reader = new FileReader;

                            reader.readAsDataURL(files[i]);

                            reader.onload = event => {
                                this.previews.push({
                                    id: id,
                                    file: event.target.result
                                });
                            };
                        } else {
                            console.log('Invalid image');
                        }
                    }
                }
            }
        },

        removeImage(id) {
            // Remove the preview
            for (let i = 0; i < this.previews.length; i++) {
                if (this.previews[i].id === id) {
                    this.previews.splice([i], 1);
                }
            }

            // Remove the image
            for (let i = 0; i < this.images.length; i++) {
                // Set the featured image back to empty if it's the image we are removing
                if (this.images[i].id === id && this.featuredImage.id === id) {
                    this.featuredImage = '',
                    this.featuredId = '',
                    this.previews.splice([i], 1);
                }

                if (this.images[i].id === id) {
                    this.images.splice([i], 1);
                }
            }
        },
    }
}