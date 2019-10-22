(() => {
  const module = angular.module('prospect.data');

  const prospectConvertedData = [
    {
      id: '1',
      prospect_case_id: '56',
      payment_entity_id: '4',
      payment_type_id: '2'
    },
    {
      id: '2',
      prospect_case_id: '24',
      payment_entity_id: '95',
      payment_type_id: '1'
    },
    {
      id: '3',
      prospect_case_id: '42',
      payment_entity_id: '5',
      payment_type_id: '2'
    }
  ];

  module.constant('ProspectConvertedData', {
    count: prospectConvertedData.length,
    values: prospectConvertedData
  });
})();
