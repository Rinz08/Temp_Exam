<template>
    <div class="flex flex-col h-screen bg-gray-100">
        <HeaderNav />

        <div class="flex-1 flex ">
            <div class="w-64 bg-[#343A40] text-[#BEC3CC] shadow-md ">
                <div class="p-4 text-lg font-semibold">AdminLT3</div>
                <hr>
                <div class="p-4 text-base font-semibold">
                    <p>Alexander Pierce</p>
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
                <h2 class="text-2xl font-bold">Dashboard</h2>
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="bg-white p-4 rounded shadow">
                        <h3 class="font-semibold">Card 1</h3>
                        <p>Some content goes here.</p>
                    </div>
                    <div class="bg-white p-4 rounded shadow">
                        <h3 class="font-semibold">Card 2</h3>
                        <p>Some content goes here.</p>
                    </div>
                    <div class="bg-white p-4 rounded shadow">
                        <h3 class="font-semibold">Card 3</h3>
                        <p>Some content goes here.</p>
                    </div>
                </div>
            </main>
        </div>

        <FooterNav />
    </div>
</template>

<script>
// import HeaderNav from './HeaderNav.vue'
// import FooterNav from './FooterNav.vue'

export default {
    components: {
        // HeaderNav,
        // FooterNav,
    },
};
</script>

<style scoped>
/* You can add additional styles here */
</style>