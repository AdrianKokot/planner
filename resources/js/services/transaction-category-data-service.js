import DataService from './data-service';

class TransactionCategoryDataService extends DataService {
  constructor() {
    super('/api/transfer_category');
  }
}

const transactionCategoryDataService = new TransactionCategoryDataService();

export default transactionCategoryDataService;
