import axios from 'axios';

axios.defaults.withCredentials = true;

const makeRequest = axios.create({
    baseURL: import.meta.env.VITE_API_URL,
    withCredentials: true
});

// Add a request interceptor to include the token in every request
makeRequest.interceptors.request.use(config => {
    const token = localStorage.getItem('token'); // Retrieve the token from local storage
    if (token) {
        config.headers['Authorization'] = `Bearer ${token}`;
    }
    return config;
}, error => {
    return Promise.reject(error);
});

// Add a response interceptor to handle token renewal
makeRequest.interceptors.response.use(response => {
    // Check if the response contains a new token
    const newToken = response.headers['authorization'];
    if (newToken) {
        // Store the new token in local storage
        localStorage.setItem('token', newToken);
        // Update Axios default headers with the new token
        makeRequest.defaults.headers.common['Authorization'] = newToken;
    }
    return response;
}, error => {
    if (error.response.status === 401) {
        // Handle token expiration (e.g., redirect to login)
        alert('Session expired. Please log in again.');
        localStorage.removeItem('token'); // Remove the expired token
        window.location.href = '/login'; // Redirect to login page
    }
    return Promise.reject(error);
});

export { makeRequest };
