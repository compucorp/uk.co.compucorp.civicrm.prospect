<?php

/**
 * Create Prospect Owner relationship.
 */
class CRM_Prospect_Setup_CreateProspectOwnerRelationship {

  /**
   * Creates prospect owner relationship for the prospect workflow case type.
   */
  public function apply() {
    $abName = 'Prospect Owner is';
    $baName = 'Prospect Owner';
    $result = civicrm_api3('RelationshipType', 'get', [
      'name_a_b' => $abName,
      'name_b_a' => $baName,
    ]);

    if ($result['count'] > 0) {
      return;
    }

    civicrm_api3('RelationshipType', 'create', [
      'name_a_b' => $abName,
      'label_a_b' => $abName,
      'name_b_a' => $baName,
      'label_b_a' => $baName,
      'contact_type_a' => 'Individual',
      'contact_type_b' => 'Individual',
    ]);
  }

}
