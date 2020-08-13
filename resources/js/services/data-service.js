import http from "./http-common";

class DataService {
  constructor(url) {
    this.url = url;
  }

  getAll() {
    return http.get(this.url);
  }

  get(id) {
    return http.get(`${this.url}/${id}`);
  }

  create(data) {
    return http.post(this.url, data);
  }

  update(id, data) {
    return http.put(`${this.url}/${id}`, data);
  }

  delete(id) {
    return http.delete(`${this.url}/${id}`);
  }
}

export default DataService;
