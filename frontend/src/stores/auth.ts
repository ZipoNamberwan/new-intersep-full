import { defineStore } from 'pinia';
import { router } from '@/router';
import { fetchWrapper } from '@/utils/helpers/fetch-wrapper';
import { makeRequest } from '@/api/api';
import axios from 'axios';

const baseUrl = `${import.meta.env.VITE_API_URL}/users`;

export const useAuthStore = defineStore({
  id: 'auth',
  state: () => ({
    // initialize state from local storage to enable user to stay logged in
    /* eslint-disable-next-line @typescript-eslint/ban-ts-comment */
    // @ts-ignore
    user: JSON.parse(localStorage.getItem('user')),
    returnUrl: null
  }),
  actions: {
    async login(username: string, password: string) {
      // const user = await fetchWrapper.post(`${baseUrl}/authenticate`, { username, password });

      // // update pinia state
      // this.user = user;
      // // store user details and jwt in local storage to keep user logged in between page refreshes
      // localStorage.setItem('user', JSON.stringify(user));
      // // redirect to previous url or default to home page
      // router.push(this.returnUrl || '/dashboard'); 

      let data = new FormData()
      data.append('email', username)
      data.append('password', password)

      await axios.create({
        baseURL: import.meta.env.VITE_API_URL,
        withCredentials: true
      }).post('/api/login', data).then(async response => {
        this.user = response.data;
        localStorage.setItem('user', JSON.stringify(response.data));

        router.push(this.returnUrl || '/dashboard');
      }).catch((error) => {
        throw error.response.data.errors.message[0]
      });
    },
    logout() {
      this.user = null;
      localStorage.removeItem('user');
      router.push('/auth/login');
    }
  }
});
