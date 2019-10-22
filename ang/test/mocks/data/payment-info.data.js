(() => {
  const module = angular.module('prospect.data');

  const paymentInfo = {
    payment_completed: null,
    pledge_balance: null,
    payment_entity: null,
    payment_entity_id: '95',
    payment_url: null,
    is_error: 0
  };

  module.constant('PaymentInfo', paymentInfo);
})();
