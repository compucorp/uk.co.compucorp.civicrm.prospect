<?php

/**
 * Class for data manipulation and retrieval on Prospect custom groups.
 */
class CRM_Prospect_ProspectCustomGroups {
  /**
   * Target prospect custom group name.
   *
   * @var string
   */
  private $customGroupName;

  /**
   * Case Id.
   *
   * @var int
   */
  private $caseId;

  /**
   * Fields array.
   *
   * Custom fields with their machine names and values as
   * {name} => [ 'machine_name' => {machine_name}, 'value' => {value} ].
   *
   * @var array
   */
  private $fields = [];

  /**
   * Custom fields list as {name} => custom_{id}.
   *
   * @var array
   */
  private $fieldsList = [];

  /**
   * Class constructor.
   *
   * @param string $customGroupName
   *   Custom group name.
   * @param int $caseId
   *   Case ID.
   */
  public function __construct($customGroupName, $caseId = 0) {
    $this->customGroupName = $customGroupName;
    $this->caseId = $caseId;
  }

  /**
   * Updates values with specified params.
   *
   * @param array $params
   *   Parameters.
   */
  public function updateFieldsFromParams(array $params) {
    if (!$this->caseId) {
      return;
    }

    $updateParams = [
      'entity_id' => $this->caseId,
    ];

    $customValuesParams = [];
    foreach ($params as $key => $value) {
      if (substr($key, 0, 7) === 'custom_') {
        $customValuesParams[$key] = $value;
      }
    }

    if (!empty($customValuesParams)) {
      $updateParams = array_merge($updateParams, $customValuesParams);
      civicrm_api3('CustomValue', 'create', $updateParams);
    }

    $this->getFields(TRUE);
  }

  /**
   * Updates field values with request data.
   *
   * @param array $fields
   *   Custom Fields.
   */
  public function updateFieldsFromRequest(array $fields) {
    $updateParams = [];

    foreach ($fields as $field) {
      $value = $this->getRequestValueOf($field);

      if ($value !== NULL) {
        $updateParams[$this->getMachineNameOf($field)] = $value;
      }
    }

    if (!empty($updateParams)) {
      $this->updateFieldsFromParams($updateParams);
    }
  }

  /**
   * Gets the value of a custom field.
   *
   * @param string $field
   *   Custom Field.
   *
   * @return mixed
   *   Returns the field value.
   */
  public function getValueOf($field) {
    $fields = $this->getFields();

    return $fields[$field]['value'];
  }

  /**
   * Sets the value of a custom field.
   *
   * @param string $field
   *   Custom field.
   * @param mixed $value
   *   Custom field value.
   */
  public function setValueOf($field, $value) {
    $machineName = $this->getMachineNameOf($field);

    $this->updateFieldsFromParams([
      $machineName => $value,
    ]);
  }

  /**
   * Gets the data type of a custom field.
   *
   * @param string $field
   *   Custom Field.
   *
   * @return string|null
   *   Returns the data type.
   */
  private function getDataTypeOf($field) {
    $fields = $this->getFields();

    return !empty($fields[$field]['data_type']) ? $fields[$field]['data_type'] : NULL;
  }

  /**
   * Gets the Option Group ID of a custom field.
   *
   * @param string $field
   *   Custom field.
   *
   * @return int|null
   *   Returns the option group ID.
   */
  private function getOptionGroupIdOf($field) {
    $fields = $this->getFields();

    return !empty($fields[$field]['option_group_id']) ? $fields[$field]['option_group_id'] : NULL;
  }

  /**
   * Gets the machine name of a custom field.
   *
   * @param string $field
   *   Custom field.
   *
   * @return string
   *   Machine name.
   */
  public function getMachineNameOf($field) {
    $fields = $this->getFields();

    return $fields[$field]['machine_name'];
  }

  /**
   * Gets the id of a custom field.
   *
   * @param string $field
   *   Custom field.
   *
   * @return int
   *   Id for custom field.
   */
  public function getIdOf(string $field): int {
    $fields = $this->getFields();

    return (int) $fields[$field]['id'];
  }

  /**
   * Gets the label of a custom field.
   *
   * @param string $field
   *   Custom field.
   *
   * @return string
   *   Custom field label.
   */
  public function getLabelOf($field) {
    $fields = $this->getFields();

    return $fields[$field]['label'];
  }

  /**
   * Checks if the custom field required.
   *
   * @param string $field
   *   Custom field.
   *
   * @return bool
   *   Returns boolean.
   */
  public function isRequired($field) {
    $fields = $this->getFields();

    return $fields[$field]['is_required'];
  }

  /**
   * Gets Option Value's label of a custom field.
   *
   * @param string $field
   *   Custom field.
   *
   * @return string|null
   *   Option label.
   */
  public function getOptionLabelOf($field) {
    $optionGroupId = $this->getOptionGroupIdOf($field);

    try {
      $option = civicrm_api3('OptionValue', 'getsingle', [
        'option_group_id' => $optionGroupId,
        'value' => $this->getValueOf($field),
      ]);

      return $option['label'];
    }
    catch (CiviCRM_API3_Exception $e) {
      return NULL;
    }
  }

  /**
   * Gets a value of a custom field from request.
   *
   * Used to retrieve field's value of Custom Field input generated by CiviCRM.
   *
   * @param string $field
   *   Custom field.
   *
   * @return mixed
   *   Returns request value.
   */
  private function getRequestValueOf($field) {
    $fieldKey = $this->getMachineNameOf($field) . '_-1';
    $dataType = $this->getDataTypeOf($field);
    $value = CRM_Utils_Request::retrieve($fieldKey, $dataType);

    // CRM_Utils_Request::retrieve() expects date value in YYYYMMDD format.
    // So if the field's type is Date then we need to pick its value
    // from Request array and then convert it into CiviCRM date format.
    if ($dataType === 'Date') {
      if (method_exists('CRM_Utils_Request', 'retrieveValue')) {
        $dateArray = [
          'value' => CRM_Utils_Request::retrieveValue($fieldKey, 'String', NULL, FALSE, CRM_Utils_Request::exportValues()),
        ];
      }
      else {
        $dateArray = [
          'value' => CRM_Utils_Array::value($fieldKey, CRM_Utils_Request::exportValues()),
        ];
      }

      CRM_Utils_Date::convertToDefaultDate($dateArray, 1, 'value');

      $value = $dateArray['value'];
    }

    return $value;
  }

  /**
   * Getfields function.
   *
   * Returns an array of a custom fields as
   * name => [
   *   data_type => 'data_type',
   *   label => 'label',
   *   machine_name => 'machine_name',
   *   option_group_id => 'option_group_id',
   *   value => 'value',
   * ].
   *
   * It caches the result within class private variable but it may be
   * updated setting $force parameter value to TRUE.
   *
   * @param bool $force
   *   Whether to force retrieving custom data or not.
   *
   * @return array
   *   Returns formatted custom fields.
   */
  private function getFields($force = FALSE) {
    if (!empty($this->fields) && !$force) {
      return $this->fields;
    }

    $customFieldData = $this->getCustomFieldData();

    if ($this->caseId) {
      $customFieldMachineNameList = $this->getCustomFieldMachineNameList();
      $parameters = [
        'entity_id' => $this->caseId,
        'entity_type' => 'Case',
      ];
      foreach ($customFieldMachineNameList as $machineName) {
        $parameters['return.' . $machineName] = 1;
      }
      $customValues = civicrm_api3('CustomValue', 'get', $parameters);
      $caseProspectCustomValues = [];

      foreach ($customValues['values'] as $customValue) {
        $caseProspectCustomValues['custom_' . $customValue['id']] = $customValue['latest'];
      }
    }

    foreach ($customFieldData as $name => $value) {
      $this->fields[$name] = [
        'id' => $value['id'],
        'machine_name' => $value['key'],
        'label' => $value['label'],
        'data_type' => $value['data_type'],
        'option_group_id' => $value['option_group_id'],
        'is_required' => $value['is_required'],
        'value' => !empty($caseProspectCustomValues[$value['key']]) ? $caseProspectCustomValues[$value['key']] : NULL,
      ];
    }

    return $this->fields;
  }

  /**
   * Get Custom fields data.
   *
   * Gets an array of a custom fields as
   * name => [
   *   key => machine_name,
   *   data_type => data_type
   *   option_group_id => option_group_id
   * ].
   *
   * @return array
   *   Custom field data.
   */
  private function getCustomFieldData() {
    if (!empty($this->fieldsList)) {
      return $this->fieldsList;
    }

    $customFields = civicrm_api3('CustomField', 'get', [
      'custom_group_id' => $this->customGroupName,
      'return' => [
        'name',
        'label',
        'data_type',
        'option_group_id',
        'is_required',
      ],
    ]);

    foreach ($customFields['values'] as $customField) {
      $this->fieldsList[$customField['name']] = [
        'id' => $customField['id'],
        'key' => 'custom_' . $customField['id'],
        'label' => $customField['label'],
        'data_type' => $customField['data_type'],
        'option_group_id' => !empty($customField['option_group_id']) ? $customField['option_group_id'] : NULL,
        'is_required' => !empty($customField['is_required']) ? TRUE : FALSE,
      ];
    }

    return $this->fieldsList;
  }

  /**
   * Gets an array of a custom field machine names.
   *
   * @return array
   *   Returns machine name list.
   */
  private function getCustomFieldMachineNameList() {
    $fields = $this->getCustomFieldData();

    return CRM_Utils_Array::collect('key', $fields);
  }

}
