import axios from 'axios';
import {Message} from 'element-ui';
import { Notification } from 'element-ui';
import {getToken, setToken} from '@/utils/auth';

// Create axios instance
const service = axios.create({
  baseURL: process.env.MIX_BASE_API,
  timeout: 10000, // Request timeout
});

// Request intercepter
service.interceptors.request.use(
  config => {
    const token = getToken();
    if (token) {
      config.headers['Authorization'] = 'Bearer ' + getToken(); // Set JWT token
    }

    return config;
  },
  error => {
    // Do something with request error
    Promise.reject(error);
  }
);

// response pre-processing
service.interceptors.response.use(
  response => {
    if (response.headers.authorization) {
      setToken(response.headers.authorization);
      response.data.token = response.headers.authorization;
    }

    return response.data;
  },
  error => {
    let message = error.message;
    let statusCode = 404;
    let title = "Error";

    if (error.response.data && error.response.data.error) {
      message = error.response.data.error.message;
      statusCode = error.response.status;
      title = error.response.statusText;
    }

    if(statusCode !== 422 && statusCode !== 429){
      Notification({
        title: title,
        message: message,
        type: 'error',
        duration: 4000,
      });
    }
    return Promise.reject(error);
  },
);

export default service;
