import axios from 'axios';
import { API_URL } from './config';

const api = axios.create({
    baseURL: API_URL,
});

const getWithCache = async (url, options = {}) => {
    const cacheKey = `${url}-${JSON.stringify(options)}`;
    const cachedResponse = localStorage.getItem(cacheKey);

    if (cachedResponse && options.method === 'get') {
        const cachedData = JSON.parse(cachedResponse);
        const expirationTime = cachedData.expirationTime;

        if (new Date() < new Date(expirationTime)) {
            return cachedData.response;
        } else {
            localStorage.removeItem(cacheKey);
        }
    }

    try {
        const response = await api({
            method: options.method || 'get',
            url,
            ...options,
        });

        if (options.method === 'get') {
            const expirationTime = new Date(new Date().getTime() + 2 * 100); // Durée de cache (5 minutes)
            localStorage.setItem(cacheKey, JSON.stringify({ response, expirationTime }));
        }

        return response;
    } catch (error) {
        console.error("Error fetching data:", error);
        throw error;
    }
};

export { getWithCache };
