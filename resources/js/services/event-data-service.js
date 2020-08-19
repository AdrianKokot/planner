import DataService from './data-service';

class EventDataService extends DataService {
  constructor() {
    super('/api/event');
  }
}

const eventDataService = new EventDataService();

export default eventDataService;
