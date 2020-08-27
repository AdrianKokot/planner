import DataService from './data-service';

class TransactionTypeDataService extends DataService {
  constructor() {
    super('/api/transfer_type');
  }
}

const transactionTypeDataService = new TransactionTypeDataService();

export default transactionTypeDataService;
