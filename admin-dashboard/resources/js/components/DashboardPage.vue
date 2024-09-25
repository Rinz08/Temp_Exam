<template>
    <div class="flex flex-col h-screen bg-gray-100">
        <HeaderNav />

        <div class="flex-1 flex ">
            <div class="w-64 bg-[#343A40] text-[#BEC3CC] shadow-md ">
                <div class="p-4 text-lg font-semibold">Sample Company</div>
                <hr>
                <div class="p-4 text-base font-semibold">
                    <p>Admin User</p>
                </div>
                <hr>
                <nav class="mt-4">
                    <ul>
                        <li class="py-2 px-4 hover:bg-gray-200"><a href="#">Dashboard</a></li>
                        <li class="py-2 px-4 hover:bg-gray-200"><a href="#">Widgets</a></li>
                        <li class="py-2 px-4 hover:bg-gray-200"><a href="#">Layout Options</a></li>
                        <li class="py-2 px-4 hover:bg-gray-200"><a href="#">Charts</a></li>
                    </ul>
                </nav>
            </div>

            <main class="flex-1 p-6">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold">Product List</h2>
                    <!-- Create Product Button -->
                    <button @click="createProduct" class="create-button">Create Product</button>
                </div>

                <!-- Search bar -->
                <div class="mt-4">
                    <input v-model="searchKeyword" @input="searchProducts" class="search-input"
                        placeholder="Search by name or description" />
                </div>

                <!-- Product list with pagination -->
                <div class="content">
                    <div class="product-list">
                        <div v-if="loading" class="loading">Loading products...</div>
                        <div v-else>
                            <div v-for="product in products.data" :key="product.id" class="product-item">
                                <div class="product-info">
                                    <h3>{{ product.name }}</h3>
                                    <p>{{ product.description }}</p>
                                </div>
                                <button @click="deleteProduct(product.id)" class="delete-button">Delete</button>
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
            products: [],
            searchKeyword: '',
            loading: false,
        };
    },
    methods: {
        async fetchProducts(url = '/api/products') {
            this.loading = true;
            const response = await fetch(`${url}?search=${this.searchKeyword}`);
            this.products = await response.json();
            this.loading = false;
        },
        searchProducts() {
            this.fetchProducts();
        },
        async deleteProduct(productId) {
            if (confirm('Are you sure you want to delete this product?')) {
                await fetch(`/api/products/${productId}`, { method: 'DELETE' });
                this.fetchProducts(); // Refresh product list after deletion
            }
        },
        createProduct() {
            // Handle the create product action, for example:
            alert("Redirecting to create product form...");
        }
    },
    mounted() {
        this.fetchProducts(); // Fetch initial products when the component is mounted
    }
};
</script>

<style scoped>
/* You can add additional styles here */

.category-list li.active,
.category-list li:hover {
    background-color: #1abc9c;
}

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

/* Loader */
.loading {
    text-align: center;
    margin-top: 20px;
}
</style>
