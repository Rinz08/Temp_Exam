<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-6 bg-gray-800 p-8 rounded-lg shadow-md">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-white">
          Sign in to your account
        </h2>
      </div>
      <form class="mt-8 space-y-6" @submit.prevent="login">
        <input type="hidden" name="remember" v-model="form.remember">
        <div class="rounded-md shadow-sm -space-y-px">
          <div>
            <label for="email-address" class="sr-only">Email address</label>
            <input id="email-address" v-model="form.email" type="email" autocomplete="email" required
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-transparent bg-gray-700 text-white placeholder-gray-500 rounded-t-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              placeholder="Email address">
          </div>
          <div>
            <label for="password" class="sr-only">Password</label>
            <input id="password" v-model="form.password" type="password" autocomplete="current-password" required
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-transparent bg-gray-700 text-white placeholder-gray-500 rounded-b-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              placeholder="Password">
          </div>
        </div>

        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <input id="remember-me" v-model="form.remember" type="checkbox"
              class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
            <label for="remember-me" class="ml-2 block text-sm text-gray-300">
              Remember me
            </label>
          </div>

          <div class="text-sm">
            <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">
              Forgot your password?
            </a>
          </div>
        </div>

        <div>
          <button type="submit"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Sign in
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios'; 

export default {
  data() {
    return {
      form: {
        email: '',
        password: '',
        remember: false
      },
      errorMessage: '' 
    };
  },
  methods: {
    async login() {
      console.log("Login attempt with:", this.form);
      try {
        const response = await axios.post('http://127.0.0.1:8000/api/login', {
          email: this.form.email,
          password: this.form.password,
          remember: this.form.remember
        });

        // Redirect to dashboard on successful login
        if (response.data.success) {
          this.$router.push('/dashboard');
        } else {
          this.errorMessage = response.data.message || 'Login failed';
        }
      } catch (error) {
        console.error("Login error:", error);
        
        if (error.response) {
          this.errorMessage = error.response.data.message || 'An error occurred';
        } else {
          this.errorMessage = 'Network error. Please try again later.';
        }
      }
    }
  }
};
</script>


<style scoped>

</style>
