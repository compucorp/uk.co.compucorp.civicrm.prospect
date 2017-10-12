# Create table for storing converted Case (Prospect) relationships
# with Contribution / Pledge.
CREATE TABLE IF NOT EXISTS `civicrm_prospect_converted` (
  `id` int unsigned NOT NULL AUTO_INCREMENT  COMMENT 'Unique ID',
  `prospect_case_id` int unsigned NOT NULL   COMMENT 'FK to Case (Prospect)',
  `payment_entity_id` int unsigned NOT NULL   COMMENT 'ID of Contribution / Pledge payment entity',
  `payment_type_id` int unsigned NOT NULL   COMMENT 'Payment type (1 - Contribution, 2 - Pledge)',
  PRIMARY KEY ( `id` ),
  UNIQUE INDEX `unique_prospect_converted` (prospect_case_id),
  CONSTRAINT FK_civicrm_prospect_converted_prospect_case_id FOREIGN KEY (`prospect_case_id`) REFERENCES `civicrm_case`(`id`) ON DELETE CASCADE
)  ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
