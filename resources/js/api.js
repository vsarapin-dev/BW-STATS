import router from "./router";
import axios from "axios";

const api = axios.create();
api.interceptors.request.use(config => {
    if (localStorage.getItem('access_token'))
    {
        config.headers.authorization = `Bearer ${localStorage.getItem('access_token')}`;
    }
    return config;
}, err => {});

api.interceptors.response.use(config => {
    if (localStorage.getItem('access_token'))
    {
        config.headers.authorization = `Bearer ${localStorage.getItem('access_token')}`;
    }
    return config;
}, err => {
    if (err.response.data.message === 'Token has expired') {
        return axios.post('/api/auth/refresh', {}, {
            headers: {
                'authorization': `Bearer ${localStorage.getItem('access_token')}`
            }
        })
            .then(res => {
                localStorage.setItem('access_token', res.data.access_token);

                err.config.headers.authorization = `Bearer ${res.data.access_token}`;

                return api.request(error.config);
            })
    }

    if (err.response.status === 401 || err.response.status === 419) {
        const token = localStorage.getItem('access_token');
        if (token) {
            localStorage.removeItem('access_token');
        }
        window.location.href = '/sign-in';
    }
    return Promise.reject(err);
})

export default api;
