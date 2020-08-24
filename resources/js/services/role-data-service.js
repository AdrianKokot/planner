import DataService from './data-service';

class RoleDataService extends DataService {
  constructor() {
    super('/api/role');
  }
}

const roleDataService = new RoleDataService();

export default roleDataService;
