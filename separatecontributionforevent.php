<?php

require_once 'separatecontributionforevent.civix.php';
// phpcs:disable
use CRM_Separatecontributionforevent_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function separatecontributionforevent_civicrm_config(&$config) {
  _separatecontributionforevent_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_xmlMenu
 */
function separatecontributionforevent_civicrm_xmlMenu(&$files) {
  _separatecontributionforevent_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function separatecontributionforevent_civicrm_install() {
  _separatecontributionforevent_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function separatecontributionforevent_civicrm_postInstall() {
  _separatecontributionforevent_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function separatecontributionforevent_civicrm_uninstall() {
  _separatecontributionforevent_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function separatecontributionforevent_civicrm_enable() {
  _separatecontributionforevent_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function separatecontributionforevent_civicrm_disable() {
  _separatecontributionforevent_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function separatecontributionforevent_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _separatecontributionforevent_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function separatecontributionforevent_civicrm_managed(&$entities) {
  _separatecontributionforevent_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_caseTypes
 */
function separatecontributionforevent_civicrm_caseTypes(&$caseTypes) {
  _separatecontributionforevent_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_angularModules
 */
function separatecontributionforevent_civicrm_angularModules(&$angularModules) {
  _separatecontributionforevent_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
 */
function separatecontributionforevent_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _separatecontributionforevent_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function separatecontributionforevent_civicrm_entityTypes(&$entityTypes) {
  _separatecontributionforevent_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_thems().
 */
function separatecontributionforevent_civicrm_themes(&$themes) {
  _separatecontributionforevent_civix_civicrm_themes($themes);
}

function separatecontributionforevent_civicrm_validateForm($formName, &$fields, &$files, &$form, &$errors) {
  if ($formName == 'CRM_Event_Form_ManageEvent_Fee') {
    if (!empty($form->_id) && !empty($fields['price_set_id'])) {
      $priceSetID = CRM_Core_DAO::singleValueQuery("
      SELECT price_set_id
      FROM civicrm_price_set_entity
      WHERE entity_table = 'civicrm_event' AND entity_id = " . $form->_id);

      if (!empty($fields['separate_contribution_event_price_fields']) && $fields['price_set_id'] != $priceSetID) {
        $errors['separate_contribution_event_price_fields'] = ts('Please remove the selected price field(s) before changing the price-set.');
      }
    }
  }
}


function separatecontributionforevent_civicrm_buildForm($formName, &$form) {
  if ($formName == 'CRM_Event_Form_ManageEvent_Fee') {
    if (!empty($form->_id)) {
      $priceSetID = CRM_Core_DAO::singleValueQuery("
      SELECT price_set_id
      FROM civicrm_price_set_entity
      WHERE entity_table = 'civicrm_event' AND entity_id = " . $form->_id);

      $result = civicrm_api3('PriceSet', 'get', [
        'debug' => 1,
        'sequential' => 1,
        'id' => $priceSetID,
        'api.PriceField.get' => ['price_set_id' => "\$value.id", 'return' => ["id", "label"]],
      ]);
      $isQuickConfig = $result['values'][0]['is_quick_config'];

      if (!$isQuickConfig) {
        $options = [];
        foreach ($result['values'][0]['api.PriceField.get']['values'] as $priceField) {
          $options[$priceField['id']] = $priceField['label'];
        }
        $form->add('select', 'separate_contribution_event_price_fields',
          ts('Price field(s) to be used for separate contribution'),
          $options,
          FALSE,
          array('class' => 'crm-select2', 'multiple' => TRUE)
        );
        CRM_Core_Region::instance('page-body')->add(array(
          'template' => "CRM/SeparateContributionEventPriceFields.tpl",
        ));

        $priceFieldEventMapping = (array) Civi::settings()->get('separate_contribution_event_price_fields');
        if (!empty($priceFieldEventMapping[$form->_id]['separate_contribution_event_price_fields_ids'])) {
          $form->setDefaults(['separate_contribution_event_price_fields' => $priceFieldEventMapping[$form->_id]['separate_contribution_event_price_fields_ids']]);
        }
      }
    }
  }
}

function separatecontributionforevent_civicrm_postProcess($formName, &$form) {
  if ($formName == 'CRM_Event_Form_ManageEvent_Fee') {
    $submitValues = $form->exportValues();
    $priceFieldEventMapping = (array) Civi::settings()->get('separate_contribution_event_price_fields');
    if (!empty($submitValues['separate_contribution_event_price_fields'])) {
      $priceFieldEventMapping[$form->_id] = ['separate_contribution_event_price_fields_ids' => $submitValues['separate_contribution_event_price_fields']];
    }
    elseif (!empty($priceFieldEventMapping[$form->_id])) {
      unset($priceFieldEventMapping[$form->_id]);
    }
    Civi::settings()->set('separate_contribution_event_price_fields', $priceFieldEventMapping);
  }
  if ($formName == 'CRM_Event_Form_Registration_Confirm') {
    // get list of price field ids that are meant for recording separate contribtuion
    $priceFieldEventMapping = (array) Civi::settings()->get('separate_contribution_event_price_fields');
    // if found then check that current event is configured for collecting separate donation
    if (!empty($priceFieldEventMapping)) {
      $separatePriceFieldIDS  = $priceFieldEventMapping[$form->_eventId]['separate_contribution_event_price_fields_ids'] ?? [];
      if (!empty($separatePriceFieldIDS)) {
        // if found then collect the participant IDs, contribtuion which will be later used for processing separate donation
        $participantIDs = $form->getVar('_participantIDS');
        $primaryParticipantID = $form->getVar('_participantId');
        $contributionID = $form->_values['contributionId'] ?? CRM_Core_DAO::singleValueQuery('SELECT contribution_id FROM civicrm_participant_payment WHERE participant_id = ' . $primaryParticipantID);
        if (!empty($contributionID)) {
          $contribution = civicrm_api3('contribution', 'getsingle', ['id' => $contributionID]);
          // fetch the line-items associated withe particpant record which is used to calculate the separate donation total amount and tax amount(if any)
          $lineItems = civicrm_api3('LineItem', 'get', ['entity_table' => 'civicrm_participant', 'entity_id' => ['IN' => $participantIDs], 'return' => ['label', 'entity_id', 'price_field_id', 'line_total', 'tax_amount', 'financial_type_id', 'qty']])['values'];
          $separateContributionAmount = $separateTaxAmount = 0;
          $financialTypeID = NULL;
          foreach ($lineItems as $lineItem) {
            if (in_array($lineItem['price_field_id'], $separatePriceFieldIDS)) {
              $separateContributionAmount += $lineItem['line_total'];
              $separateTaxAmount += $lineItem['tax_amount'] ?? 0;
              $financialTypeID = $lineItem['financial_type_id'];
            }
          }
          $newContribution = $contribution;
          $updatedContributionAmount = round($newContribution['total_amount'] - $separateContributionAmount, 2);
          CRM_Core_DAO::executeQuery(sprintf("UPDATE civicrm_contribution SET total_amount = %s WHERE id = %d", $updatedContributionAmount, $contribution['id']));

          CRM_Core_DAO::executeQuery(sprintf("
            UPDATE civicrm_entity_financial_trxn SET amount = %s WHERE entity_table = 'civicrm_contribution' AND entity_id = %s",
            $updatedContributionAmount, $contribution['id']));
          CRM_Core_DAO::executeQuery(sprintf(" UPDATE civicrm_financial_trxn ft
            INNER JOIN civicrm_entity_financial_trxn eft ON eft.financial_trxn_id = ft.id AND eft.entity_table = 'civicrm_contribution' AND eft.entity_id = %s
            SET ft.total_amount = %s, ft.fee_amount = %s", $contribution['id'], $updatedContributionAmount, $updatedContributionAmount));
          $newContribution['total_amount'] = $separateContributionAmount;
          $newContribution['tax_amount'] = $separateTaxAmount;
          $newContribution['financial_type_id'] = $financialTypeID;
          $newContribution['trxn_id'] = $newContribution['invoice_id'] = '';
          $newContribution['skipLineItem'] = 1;
          unset($newContribution['id'], $newContribution['contribution_id']);
          $newContributionID = civicrm_api3('Contribution', 'create', $newContribution)['id'];
          CRM_Core_DAO::executeQuery("UPDATE civicrm_contribution SET net_amount = total_amount - fee_amount WHERE id IN ($newContributionID, " . $contribution['id'] . ");");
          CRM_Core_DAO::executeQuery(sprintf("UPDATE civicrm_entity_financial_trxn
          SET amount = %s WHERE entity_table = 'civicrm_contribution' AND entity_id = %s ", $contribution['total_amount'], $contribution['id']));

          $financialTrxnID = CRM_Core_DAO::singleValueQuery(sprintf('SELECT financial_trxn_id FROM civicrm_entity_financial_trxn WHERE entity_table = \'civicrm_contribution\' AND entity_id = %s ', $newContributionID));
          civicrm_api3('FinancialTrxn', 'create', ['id' => $financialTrxnID, 'trxn_id' => $contribution['trxn_id']]);

          $entityProcessed = FALSE;
          $feeLevel = $participant = $feeAmount = NULL;
          foreach ($lineItems as $lineItem) {
            if (in_array($lineItem['price_field_id'], $separatePriceFieldIDS)) {
              $participant = $participant ?: civicrm_api3('Participant', 'getsingle', ['id' => $lineItem['entity_id']]);
              $feeLevel = $feeLevel ?: $participant['participant_fee_level'];
              $feeAmount = $feeAmount ?: $participant['participant_fee_amount'];
              $selectedFeeLevel = $lineItem['label'] . ' - ' . (int) $lineItem['qty'];
              if (in_array($selectedFeeLevel, $feeLevel)) {
                unset($feeLevel[array_search($selectedFeeLevel, $feeLevel)]);
              }
              $feeAmount -= $lineItem['line_total'];
              $result = civicrm_api3('LineItem', 'create', ['id' => $lineItem['id'], 'entity_table' => 'civicrm_contribution', 'entity_id' => $newContributionID, 'contribution_id' => $newContributionID]);
              // TODO: there is a bug in lineitem api where entity_table is not updated thus we are using UPDATE sql to update the line-item entity_table
              CRM_Core_DAO::executeQuery("UPDATE civicrm_line_item SET entity_table = 'civicrm_contribution' WHERE id = " . $lineItem['id']);
              CRM_Core_DAO::executeQuery(sprintf("UPDATE civicrm_entity_financial_trxn eft
                INNER JOIN civicrm_financial_item fi ON fi.id = eft.entity_id AND eft.entity_table = 'civicrm_financial_item'
                SET financial_trxn_id = %s WHERE fi.entity_table = 'civicrm_line_item' AND fi.entity_id = %s ", $financialTrxnID,  $lineItem['id']));
            }
          }
          civicrm_api3('Participant', 'create', array_merge($participant, ['participant_fee_amount' => $feeAmount, 'fee_level' => $feeLevel]));
        }

      }

    }
  }
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 */
//function separatecontributionforevent_civicrm_preProcess($formName, &$form) {
//
//}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
//function separatecontributionforevent_civicrm_navigationMenu(&$menu) {
//  _separatecontributionforevent_civix_insert_navigation_menu($menu, 'Mailings', array(
//    'label' => E::ts('New subliminal message'),
//    'name' => 'mailing_subliminal_message',
//    'url' => 'civicrm/mailing/subliminal',
//    'permission' => 'access CiviMail',
//    'operator' => 'OR',
//    'separator' => 0,
//  ));
//  _separatecontributionforevent_civix_navigationMenu($menu);
//}
