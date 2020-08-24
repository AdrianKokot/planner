import DataService from './data-service';

class PermissionDataService extends DataService {
  constructor() {
    super('/api/permission');
  }
}

const permissionDataService = new PermissionDataService();

export default permissionDataService;
