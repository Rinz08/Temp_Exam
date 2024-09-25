<template>
    <div class="flex flex-col h-screen bg-gray-100">
        <HeaderNav />

        <div class="flex-1 flex">
            <div class="w-64 bg-[#343A40] text-[#BEC3CC] shadow-md">
                <div class="p-4 text-lg font-semibold">AdminLT3</div>
                <hr>
                <nav class="mt-4">
                    <ul>
                        <li class="py-2 px-4 hover:bg-gray-200" @click="setCurrentView('list')"><a href="#">List of Products</a></li>
                        <li class="py-2 px-4 hover:bg-gray-200" @click="setCurrentView('create')"><a href="#">Add a Product</a></li>
                    </ul>
                </nav>
            </div>

            <main class="flex-1 p-6">
                <h2 class="text-2xl font-bold">{{ currentHeading }}</h2>

                <div v-if="currentView === 'list'">
                    <button @click="setCurrentView('create')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4">
                        Create Product
                    </button>
                    <!-- Product List -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div v-for="product in products" :key="product.id" class="bg-white p-4 rounded shadow relative">
                            <h3 class="font-semibold">{{ product.name }}</h3>
                            <p>{{ product.description }}</p>
                            <button @click="deleteProduct(product.id)" class="absolute bottom-2 right-2 bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-sm">Delete</button>
                        </div>
                    </div>
                </div>

                <div v-if="currentView === 'create'">
                    <CreateProduct @changeView="setCurrentView" />
                </div>
            </main>
        </div>

        <FooterNav />
    </div>
</template>

<script>
import HeaderNav from './HeaderNav.vue';
import FooterNav from './FooterNav.vue';
import CreateProduct from './CreateProduct.vue';

export default {
    components: {
        HeaderNav,
        FooterNav,
        CreateProduct,
    },
    data() {
        return {
            products: [], // Your list of products from the API
            currentView: 'list',
            currentHeading: 'Dashboard', // Default heading
        };
    },
    methods: {
        setCurrentView(view) {
            this.currentView = view;
            this.currentHeading = view === 'create' ? 'Product Details' : 'Dashboard';
        },
        fetchProducts() {
            // Fetch the list of products from the API
        },
        deleteProduct(id) {
            // Implement product deletion logic here
        }
    },
    created() {
        this.fetchProducts(); // Load products when the component is created
    }
};
</script>
