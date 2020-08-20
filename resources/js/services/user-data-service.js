import DataService from './data-service';

class UserDataService extends DataService {
  constructor() {
    super('/api/user');
  }
}

const userDataService = new UserDataService();

export default userDataService;
