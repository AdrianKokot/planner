import DataService from './data-service';

class EventDataService extends DataService {
  constructor() {
    super('/api/events');
  }
}

const eventDataService = new EventDataService();

export default eventDataService;
