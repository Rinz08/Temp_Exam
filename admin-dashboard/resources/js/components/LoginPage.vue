<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-6 bg-gray-800 p-8 rounded-lg shadow-md">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-white">
          Sign in to your account
        </h2>
      </div>
      <!-- Added console.log to confirm form submission -->
      <form class="mt-8 space-y-6" @submit.prevent="login">
        <input type="hidden" name="remember" v-model="form.remember">
        <div class="rounded-md shadow-sm -space-y-px">
          <div>
            <label for="login" class="sr-only">Email or Username</label>
            <input id="login" v-model="form.login" type="text" autocomplete="username" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-transparent bg-gray-700 text-white placeholder-gray-500 rounded-t-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Email or Username">
          </div>
          <div>
            <label for="password" class="sr-only">Password</label>
            <input id="password" v-model="form.password" type="password" autocomplete="current-password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-transparent bg-gray-700 text-white placeholder-gray-500 rounded-b-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Password">
          </div>
        </div>

        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <input id="remember-me" v-model="form.remember" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
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
          <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
        login: '', // Updated v-model to match this
        password: '',
        remember: false
      },
      error: ''
    };
  },
  methods: {
    login() {
      // Added console log to check if login() is triggered
      console.log('Login button clicked, attempting login with data:', this.form);

      // If login() isn't being triggered, the issue is with form submission
      axios.post('/login', {
        login: this.form.login, 
        password: this.form.password,
        remember: this.form.remember
      }, {
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      })
      .then(response => {
        if (response.data.success) {
          localStorage.setItem('user', JSON.stringify(response.data.user)); 
          this.$router.push({ name: 'dashboard' });
        } else {
          this.error = response.data.message || 'Login failed';
        }
      })
      .catch(error => {
        console.error('Login request failed', error);
        this.error = 'Login error, please try again later.';
      });
    }
  }
};
</script>

<style scoped>
/* Tailwind CSS is handling all styling */
</style>
