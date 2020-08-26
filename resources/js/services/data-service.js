import http from "./http-common";
import toastOptions from "./toast-options";
import app from '../app';

function show403Toast() {
  app.$bvToast.toast(
    "You don\'t have access to perform this action.",
    toastOptions('danger')
  );
}
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
      if (error.response.status == 403) {
        show403Toast();
      } else {
        console.log(error, error.response);
      }
    });
  }

  get(id) {
    return http.get(`${this.url}/${id}`).catch(error => {
      if (error.response.status == 403) {
        show403Toast();
      } else {
        console.log(error, error.response);
      }
    });
  }

  create(data) {
    return http.post(this.url, data).catch(error => {
      if (error.response.status == 403) {
        show403Toast();
      } else {
        console.log(error, error.response);
      }
    });
  }

  update(id, data) {
    return http.put(`${this.url}/${id}`, data).catch(error => {
      if (error.response.status == 403) {
        show403Toast();
      } else {
        console.log(error, error.response);
      }
    });
  }

  delete(id) {
    return http.delete(`${this.url}/${id}`).catch(error => {
      if (error.response.status == 403) {
        show403Toast();
      } else {
        console.log(error, error.response);
      }
    });
  }
}

export default DataService;
