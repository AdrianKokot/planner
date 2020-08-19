import DataService from './data-service';

class LogDataService extends DataService {
  constructor() {
    super('/api/log');
  }
}

const logDataService = new LogDataService();

export default logDataService;
