import DataService from './data-service';

class EventDataService extends DataService {
  constructor() {
    super('/events');
  }
}

const eventDataService = new EventDataService();

export default eventDataService;
