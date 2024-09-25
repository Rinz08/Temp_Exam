<template>
    <div class="p-6">
        <h2 class="text-2xl font-bold">Create Product</h2>
        <form @submit.prevent="submitForm">
            <!-- Step 1: Basic Details -->
            <div v-if="step === 1">
                <div class="mb-4">
                    <label for="name">Name:</label>
                    <input type="text" id="name" v-model="product.name" required class="border p-2 rounded">
                </div>
                <div class="mb-4">
                    <label for="category">Category:</label>
                    <select id="category" v-model="product.category" required class="border p-2 rounded">
                        <option disabled value="">Please select one</option>
                        <option value="Electronics">Electronics</option>
                        <option value="Apparel">Apparel</option>
                        <option value="Kitchen">Kitchen</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="description">Description:</label>
                    <textarea id="description" v-model="product.description" required class="border p-2 rounded"></textarea>
                </div>
                <button type="button" @click="step = 2" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Next</button>
            </div>
            
            <!-- Step 2: Images -->
            <div v-if="step === 2">
                <button type="button" @click="step = 1" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back</button>
                <div class="mb-4">
                    <label for="images">Images:</label>
                    <input type="file" id="images" ref="images" multiple @change="handleFiles" class="border p-2 rounded">
                </div>
                <button type="button" @click="step = 3" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Next</button>
            </div>
            
            <!-- Step 3: Date and Time -->
            <div v-if="step === 3">
                <button type="button" @click="step = 2" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back</button>
                <div class="mb-4">
                    <label for="dateTime">Date and Time:</label>
                    <input type="datetime-local" id="dateTime" v-model="product.dateTime" required class="border p-2 rounded">
                </div>
                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Create Product</button>
            </div>

            <!-- Error feedback section -->
            <div v-if="error">
                <p class="text-red-500">{{ error }}</p>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            product: {
                name: '',
                category: '',
                description: '',
                images: [],
                dateTime: ''
            },
            step: 1,
            error: 'Something went wrong.' // Error message
        };
    },
    methods: {
        handleFiles() {
            const files = this.$refs.images.files;
            if (files.length > 0) {
                this.product.images = Array.from(files);
            }
        },
        submitForm() {
            const formData = new FormData();
            formData.append('name', this.product.name);
            formData.append('category', this.product.category);
            formData.append('description', this.product.description);
            formData.append('dateTime', this.product.dateTime);
            this.product.images.forEach((file, index) => {
                formData.append(`images[${index}]`, file);
            });

            axios.post('/api/products', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(response => {
                this.$emit('changeView', 'list'); 
            })
            .catch(error => {
                this.error = 'Error creating product. Please check the data and try again.';
            });
        }
    }
};
</script>

<style scoped>
/* Add any custom styles here if needed */
</style>
