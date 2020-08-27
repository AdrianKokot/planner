import http from "./http-common";
import { toastOptions } from "./toast-options";
import app from '../app';

function handle403() {
  app.$bvToast.toast(
    "You don\'t have access to perform this action.",
    toastOptions('danger')
  );
}

function handle401() {
  app.$bvToast.toast(
    "Your session has timed out. Please login again.",
    toastOptions('danger')
  );
  // app.$store.dispatch('logout');
}

function handleError(error) {
  if (error.response.status == 403) {
    handle403();
  } else if(error.response.status == 401) {
    handle401();
  } else {
    console.log(error.response);
    return error.response;
  }
}
class DataService {
  constructor(url) {
    this.url = url;
  }

  /**
   * Create string formated as query parameters for http from options object
   * @example renderParamsFromOptions({key: value}) => "?key=value"
   * @param {object} options object with key: value pairs
   * @return {string} http query parameters
   * @memberof DataService
   */
  renderParamsFromOptions(options) {
    let param = ''

    if (Object.keys(options).length > 0) {
      param += '?';

      for (let key in options) {
        param += key + '=' + options[key] + '&';
      }

      param = param.slice(0, -1);
    }

    return param;
  }

  /**
   * Get all records of resource from API
   *
   * @param {object} [options={}] query parameters formated as object with key: value pairs
   * @return {Promise<AxiosResponse<any>>} promise with http response
   * @memberof DataService
   */
  getAll(options = {}) {
    const params = this.renderParamsFromOptions(options);
    return http.get(this.url + params).catch(handleError);
  }

  /**
   * Get record of resource with given id
   *
   * @param {string|number} id resource id
   * @return {Promise<AxiosResponse<any>>} promise with http response
   * @memberof DataService
   */
  get(id) {
    return http.get(`${this.url}/${id}`).catch(handleError);
  }

  /**
   * Create new resource
   *
   * @param {object} data resource parameters
   * @return {Promise<AxiosResponse<any>>} promise with http response
   * @memberof DataService
   */
  create(data) {
    return http.post(this.url, data).catch(handleError);
  }

  /**
   * Update resource with given id and data
   *
   * @param {string|number} id resource id
   * @param {object} data resource parameters
   * @return {Promise<AxiosResponse<any>>} promise with http response
   * @memberof DataService
   */
  update(id, data) {
    return http.put(`${this.url}/${id}`, data).catch(handleError);
  }

  /**
   * Delete resource with given id
   *
   * @param {string|number} id resource id
   * @return {Promise<AxiosResponse<any>>} promise with http response
   * @memberof DataService
   */
  delete(id) {
    return http.delete(`${this.url}/${id}`).catch(handleError);
  }
}

export default DataService;
