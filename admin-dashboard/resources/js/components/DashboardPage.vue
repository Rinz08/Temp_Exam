<template>
    <div class="flex flex-col h-screen bg-gray-100">
        <HeaderNav />

        <div class="flex-1 flex ">
            <!-- Sidebar -->
            <div class="w-64 bg-[#343A40] text-[#BEC3CC] shadow-md flex flex-col justify-between">
                <div>
                    <div class="p-4 text-lg font-semibold">Sample Company</div>
                    <hr>
                    <div class="p-4 text-base font-semibold">
                        <p>Admin User</p>
                    </div>
                    <hr>
                    <nav class="mt-4">
                        <ul>
                            <li class="py-2 px-4 hover:bg-gray-200 cursor-pointer" @click="openCreateModal">Add a Product</li> <!-- Add a Product Button -->
                            <li class="py-2 px-4 hover:bg-gray-200"><a href="#">Dashboard</a></li>
                            <li class="py-2 px-4 hover:bg-gray-200"><a href="#">Layout Options</a></li>
                            <li class="py-2 px-4 hover:bg-gray-200"><a href="#">Charts</a></li>
                        </ul>
                    </nav>
                </div>

                <!-- Logout Button at the Bottom -->
                <div class="p-4">
                    <button @click="logout" class="logout-button">Logout</button>
                </div>
            </div>

            <main class="flex-1 p-6">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold">Product List</h2>
                    <!-- Create Product Button -->
                    <button @click="openCreateModal" class="create-button">Create Product</button>
                </div>

                <!-- Product list with pagination intact -->
                <div class="product-list mt-4">
                    <div v-if="loading" class="loading">Loading products...</div>
                    <div v-else>
                        <div v-for="product in products.data" :key="product.id" class="product-item">
                            <div class="product-info">
                                <h3>{{ product.name }}</h3>
                                <p>{{ product.description }}</p>
                            </div>
                            <div>
                                <button @click="editProduct(product.id)" class="edit-button">Edit</button>
                                <button @click="deleteProduct(product.id)" class="delete-button">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination controls -->
                <div class="pagination-controls" v-if="products.data && products.data.length">
                    <button @click="fetchProducts(products.prev_page_url)" :disabled="!products.prev_page_url"
                        class="pagination-button">Previous</button>
                    <button @click="fetchProducts(products.next_page_url)" :disabled="!products.next_page_url"
                        class="pagination-button">Next</button>
                </div>

                <!-- Create/Edit Product Modal -->
                <div v-if="showCreateModal || showEditModal" class="modal">
                    <div class="modal-content">
                        <span class="close" @click="closeModal">&times;</span>

                        <!-- Step 1: Basic Information -->
                        <div v-if="currentStep === 1" class="modal-form-section">
                            <h2>{{ isEdit ? 'Edit Product' : 'Create Product' }}</h2>
                            <form @submit.prevent="validateStep1">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input v-model="formData.name" type="text" id="name" required class="form-input"/>
                                </div>
                                <div class="form-group">
                                    <label for="category">Category</label>
                                    <select v-model="formData.category" id="category" required class="form-input">
                                        <option value="" disabled>Select Category</option>
                                        <option v-for="category in categories" :key="category" :value="category">{{ category }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea v-model="formData.description" id="description" required class="form-textarea"></textarea>
                                </div>
                                <div class="modal-actions">
                                    <button type="submit" class="next-button">Next</button>
                                </div>
                            </form>
                        </div>

                        <!-- Step 2: Image Upload -->
                        <div v-if="currentStep === 2" class="modal-form-section">
                            <h2>Step 2: Upload Images</h2>
                            <form @submit.prevent="validateStep2">
                                <input type="file" @change="handleImageUpload" multiple accept="image/*" class="file-input" />
                                <p>Selected images: {{ formData.images.length }}</p>
                                <div class="modal-actions">
                                    <button type="submit" class="next-button">Next</button>
                                </div>
                            </form>
                        </div>

                        <!-- Step 3: Set Date -->
                        <div v-if="currentStep === 3" class="modal-form-section">
                            <h2>Step 3: Set Date</h2>
                            <input type="datetime-local" v-model="formData.date_time" required class="form-input"/>
                            <div class="modal-actions">
                                <button @click="setToday" class="today-button">Today</button>
                                <button @click="submitProduct" class="submit-button">{{ isEdit ? 'Update' : 'Submit' }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <FooterNav />
    </div>
</template>

<script>
export default {
    data() {
        return {
            showCreateModal: false,
            showEditModal: false,
            isEdit: false, // To differentiate between edit and create mode
            currentStep: 1,
            categories: ['Category 1', 'Category 2', 'Category 3'], // Example categories
            formData: {
                id: null, // Holds the product id during edit
                name: '',
                category: '',
                description: '',
                images: [],
                date_time: '', // Changed from `date` to `date_time`
            },
            errors: [],
            loading: false,
            products: [], // Data for the product list
        };
    },
    methods: {
        openCreateModal() {
            this.isEdit = false; // Set to create mode
            this.showCreateModal = true;
        },
        async editProduct(productId) {
            this.isEdit = true;
            try {
                // Fetch the product data for editing
                const response = await fetch(`/api/products/${productId}`);
                const product = await response.json();
                
                // Pre-fill the form with the fetched product data
                this.formData.id = product.id;
                this.formData.name = product.name;
                this.formData.category = product.category;
                this.formData.description = product.description;
                this.formData.date_time = product.date_time; // Changed from `date` to `date_time`
                
                this.showEditModal = true;
            } catch (error) {
                console.error('Error fetching product data:', error);
            }
        },
        closeModal() {
            this.showCreateModal = false;
            this.showEditModal = false;
            this.resetForm();
        },
        resetForm() {
            this.currentStep = 1;
            this.formData = {
                id: null,
                name: '',
                category: '',
                description: '',
                images: [],
                date_time: '', // Changed from `date` to `date_time`
            };
        },
        validateStep1() {
            // Step 1: Basic validation (works for both Create and Edit)
            if (this.formData.name && this.formData.category && this.formData.description) {
                this.currentStep = 2; // Move to Step 2
            } else {
                alert('Please fill all fields.');
            }
        },
        validateStep2() {
            // Step 2: Image validation (works for both Create and Edit)
            if (this.formData.images.length < 1 || this.formData.images.length > 4) {
                alert('You must upload between 1 and 4 images.');
            } else {
                this.currentStep = 3; // Move to Step 3
            }
        },
        handleImageUpload(event) {
            const files = event.target.files;
            if (files.length > 4) {
                alert('You can upload a maximum of 4 images.');
                return;
            }
            this.formData.images = [...files];
        },
        setToday() {
            const now = new Date().toISOString().slice(0, 16); // Format for datetime-local input
            this.formData.date_time = now; // Changed from `date` to `date_time`
        },
        async submitProduct() {
            // Submit or Update the product
            if (!this.formData.date_time) { // Changed from `date` to `date_time`
                alert('Please set a valid date.');
                return;
            }

            if (this.isEdit) {
                // Update existing product
                try {
                    const response = await fetch(`/api/products/${this.formData.id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            name: this.formData.name,
                            category: this.formData.category,
                            description: this.formData.description,
                            date_time: this.formData.date_time, // Changed from `date` to `date_time`
                        }),
                    });
                    if (response.ok) {
                        alert('Product updated successfully!');
                        this.closeModal();
                        this.fetchProducts(); // Refresh product list
                    } else {
                        alert('Failed to update product.');
                    }
                } catch (error) {
                    console.error('Error updating product:', error);
                }
            } else {
                // Create new product
                try {
                    const response = await fetch('/api/products', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            name: this.formData.name,
                            category: this.formData.category,
                            description: this.formData.description,
                            date_time: this.formData.date_time, // Changed from `date` to `date_time`
                        }),
                    });
                    if (response.ok) {
                        alert('Product created successfully!');
                        this.closeModal();
                        this.fetchProducts(); // Refresh product list
                    } else {
                        alert('Failed to create product.');
                    }
                } catch (error) {
                    console.error('Error creating product:', error);
                }
            }
        },
        async fetchProducts(url = '/api/products') {
            this.loading = true;
            const response = await fetch(`${url}`);
            this.products = await response.json();
            this.loading = false;
        },
        async deleteProduct(productId) {
            if (confirm('Are you sure you want to delete this product?')) {
                await fetch(`/api/products/${productId}`, { method: 'DELETE' });
                this.fetchProducts(); // Refresh product list after deletion
            }
        },
        // Logout functionality
        logout() {
            // Clear any session data if needed
            // Redirect to login page
            this.$router.push('/login'); // Assuming you have a Vue Router setup and /login is the route for the login page
        }
    },
    mounted() {
        this.fetchProducts(); // Fetch initial products when the component is mounted
    }
};
</script>

<style scoped>
/* Create Product button styling */
.create-button {
    background-color: #3498db;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.create-button:hover {
    background-color: #2980b9;
}

/* Edit button styling */
.edit-button {
    background-color: #f39c12;
    color: white;
    padding: 8px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.edit-button:hover {
    background-color: #e67e22;
}

/* Sidebar item styling */
.nav a, .nav li {
    cursor: pointer;
}

/* Product list styling */
.product-list {
    margin-top: 20px;
}

.product-item {
    display: flex;
    justify-content: space-between;
    padding: 15px;
    margin-bottom: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background-color: white;
}

.product-info h3 {
    margin: 0;
    font-size: 18px;
}

.delete-button {
    padding: 8px 12px;
    background-color: #e74c3c;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.delete-button:hover {
    background-color: #c0392b;
}

/* Pagination styling */
.pagination-controls {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
}

.pagination-button {
    padding: 10px 20px;
    background-color: #3498db;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.pagination-button:disabled {
    background-color: #bdc3c7;
    cursor: not-allowed;
}

/* Modal Styling */
.modal {
    display: block;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    background-color: white;
    margin: 10% auto;
    padding: 20px;
    width: 40%;
    border-radius: 8px;
    position: relative;
}

/* Modal close button */
.close {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 20px;
    color: #aaa;
    cursor: pointer;
}

.close:hover {
    color: black;
}

/* Form input styling */
.form-group {
    margin-bottom: 15px;
}

.form-input,
.form-textarea {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
}

.form-textarea {
    height: 100px;
}

.file-input {
    padding: 8px;
    font-size: 16px;
}

/* Modal actions: buttons for navigation and submission */
.modal-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 20px;
}

.next-button,
.submit-button,
.today-button {
    background-color: #3498db;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-left: 10px;
}

.next-button:hover,
.submit-button:hover,
.today-button:hover {
    background-color: #2980b9;
}

/* Logout button styling */
.logout-button {
    background-color: #000000;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    display: block;
    width: 100%;
    text-align: center;
}

.logout-button:hover {
    background-color: #333333;
}

/* Loader */
.loading {
    text-align: center;
    margin-top: 20px;
}
</style>
