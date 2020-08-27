import DataService from './data-service';

class BalanceDataService extends DataService {
  constructor() {
    super('/api/transfer');
  }
}

const balanceDataService = new BalanceDataService();

export default balanceDataService;
