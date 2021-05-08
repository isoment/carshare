export default {
    data() {
        return {
            validationErrors: null,
        }
    },
    
    methods: {
        errorFor(field) {
            return this.validationErrors !== null && this.validationErrors[field] 
                ? this.validationErrors[field] 
                : null;
        },
    }
};