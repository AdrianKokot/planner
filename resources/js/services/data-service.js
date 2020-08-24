import http from "./http-common";

class DataService {
  constructor(url) {
    this.url = url;
  }

  renderParamsFromOptions(options) {
    let param = ''

    if(Object.keys(options).length > 0) {
      param += '?';

      for(let key in options) {
        param += key + '=' + options[key] + '&';
      }

      param = param.slice(0, -1);
    }

    return param;
  }

  getAll(options = {}) {
    const params = this.renderParamsFromOptions(options);
    return http.get(this.url + params).catch(error => {
      console.log(error, error.response);
    });
  }

  get(id) {
    return http.get(`${this.url}/${id}`).catch(error => {
      console.log(error, error.response);
    });
  }

  create(data) {
    return http.post(this.url, data).catch(error => {
      console.log(error, error.response);
    });
  }

  update(id, data) {
    return http.put(`${this.url}/${id}`, data).catch(error => {
      console.log(error, error.response);
    });
  }

  delete(id) {
    return http.delete(`${this.url}/${id}`).catch(error => {
      console.log(error, error.response);
    });
  }
}

export default DataService;
