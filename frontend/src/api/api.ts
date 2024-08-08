import axios from 'axios';

axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;

const makeRequest = axios.create({
    baseURL: import.meta.env.VITE_API_URL,
    withCredentials: true
});

async function initializeCsrfToken() {
    await makeRequest.get('/sanctum/csrf-cookie');
}

export { makeRequest, initializeCsrfToken };
