import axios from "axios";
import store from "../store/store";

export const apiClient = axios.create({
  baseURL: "http://localhost",
  withCredentials: true
});

apiClient.interceptors.request.use(
  function(config) {
    const token = window.localStorage.getItem("token");
    if (token != null) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  function(error) {
    return Promise.reject(error.response);
  }
);

apiClient.interceptors.response.use(
  response => {
    return response;
  },
  function(error) {
    if (error.response.status === 401) {
      store.dispatch("logout");
    }
    return Promise.reject(error.response);
  }
);
