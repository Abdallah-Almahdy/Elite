import axios from "axios";

const api = axios.create({
    baseURL: "https://sicilia-test.srv599157.hstgr.cloud/api",
});

export default api;