<?php

require_once 'sexualityselfidentify.civix.php';
use CRM_sexualityselfidentify_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function sexualityselfidentify_civicrm_config(&$config) {
  _sexualityselfidentify_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function sexualityselfidentify_civicrm_xmlMenu(&$files) {
  _sexualityselfidentify_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function sexualityselfidentify_civicrm_install() {
  _sexualityselfidentify_civix_civicrm_install();
  _sexualityselfidentify_add_other_option();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function sexualityselfidentify_civicrm_postInstall() {
  _sexualityselfidentify_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function sexualityselfidentify_civicrm_uninstall() {
  _sexualityselfidentify_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function sexualityselfidentify_civicrm_enable() {
  _sexualityselfidentify_civix_civicrm_enable();
  _sexualityselfidentify_add_other_option();

  $group = civicrm_api3('CustomGroup', 'get', array(
    'name' => 'SexualitySelfIdentify',
  ));
  if (!empty($group['id'])) {
    // CustomGroup 'create' is broken for update
    civicrm_api3('CustomGroup', 'update', array(
      'id' => $group['id'],
      'is_reserved' => 1,
      'is_active' => 1,
    ));
  }
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function sexualityselfidentify_civicrm_disable() {
  _sexualityselfidentify_civix_civicrm_disable();

  try {
    $group = civicrm_api3('CustomGroup', 'get', array(
      'name' => 'SexualitySelfIdentify',
    ));
    if (!empty($group['id'])) {
      // CustomGroup 'create' is broken for update
      civicrm_api3('CustomGroup', 'update', array(
        'id' => $group['id'],
        'is_reserved' => 0,
        'is_active' => 0,
      ));
    }
    civicrm_api3('OptionValue', 'create', array(
      'id' => CRM_sexualityselfidentify_BAO_Sexuality::otherOption('id'),
      'is_reserved' => 0,
    ));
  }
  // If custom data doesn't exist, ignore
  catch (API_Exception $e) {}
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function sexualityselfidentify_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _sexualityselfidentify_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function sexualityselfidentify_civicrm_managed(&$entities) {
  _sexualityselfidentify_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function sexualityselfidentify_civicrm_caseTypes(&$caseTypes) {
  _sexualityselfidentify_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function sexualityselfidentify_civicrm_angularModules(&$angularModules) {
  _sexualityselfidentify_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function sexualityselfidentify_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _sexualityselfidentify_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_apiWrappers().
 * 
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_apiWrappers/
 */
function sexualityselfidentify_civicrm_apiWrappers(&$wrappers, $apiRequest) {
  if (strtolower($apiRequest['entity']) == 'contact') {
    $wrappers[] = new CRM_sexualityselfidentify_ContactAPIWrapper();
  }
}

/**
 * Implements hook_civicrm_buildForm().
 * 
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_buildForm/
 */
function sexualityselfidentify_civicrm_buildForm($formName, &$form) {
  if (in_array($formName, array('CRM_Contact_Form_Contact', 'CRM_Contact_Form_Inline_Demographics', 'CRM_Profile_Form_Edit'))
  && $form->elementExists('sexuality_id')) {
    $form->removeElement('sexuality_id');
    $form->addElement('text', 'sexuality_id', ts('Sexuality'));
    if (!empty($form->_contactId)) {
      $form->setDefaults(array(
        'sexuality_id' => CRM_sexualityselfidentify_BAO_Sexuality::get($form->_contactId),
      ));
    }
    // Hide custom field from contact edit screen since it is not editable
    if ($formName == 'CRM_Contact_Form_Contact') {
      CRM_Core_Resources::singleton()
        ->addStyle('#sexualityselfidentify.crm-custom-accordion {display: none;}', 99, 'html-header');
    }
  }
}

/**
 * Implements hook_civicrm_pre().
 * 
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_pre/
 */
function sexualityselfidentify_civicrm_pre($op, $objectName, $id, &$params) {
  if ($objectName == 'Individual' && in_array($op, array('create', 'edit'))) {
    // $params['version'] indicates this is an api request, which we've already handled with api_v3_sexualityselfidentifyAPIWrapper
    if (isset($params['sexuality_id']) && empty($params['version'])) {
      $input = trim($params['sexuality_id']);
      $params['sexuality_id'] = CRM_sexualityselfidentify_BAO_Sexuality::match($input);

      // Can't just set `$params['custom_x'] = $input` because that would be too easy
      // For contact create
      $params['custom_' . CRM_sexualityselfidentify_BAO_Sexuality::getCustomFieldId() . '_-1'] = $input;
      // For contact inline-edit
      $params += array('custom' => array());
      CRM_Core_BAO_CustomField::formatCustomField(CRM_sexualityselfidentify_BAO_Sexuality::getCustomFieldId(), $params['custom'],
        $input, 'Individual', NULL, $id, FALSE, FALSE, TRUE
      );
    }
  }
}

/**
 * Implements hook_civicrm_pageRun().
 * 
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_pageRun/
 */
function sexualityselfidentify_civicrm_pageRun(&$page) {
  $pageClass = get_class($page);

  // For contact summary view
  if (in_array($pageClass, array('CRM_Contact_Page_View_Summary', 'CRM_Contact_Page_Inline_Demographics'))) {
    $cid = $page->get_template_vars('id');
    if ($cid) {
      $page->assign('sexuality_display', htmlspecialchars(CRM_sexualityselfidentify_BAO_Sexuality::get($cid)));
    }
    // Hide custom field from contact summary since its value is incorporated in the demographics pane
    CRM_Core_Resources::singleton()
      ->addStyle('.customFieldGroup.sexualityselfidentify {display: none;}', 99, 'html-header');
  }

  // For profile listings
  elseif ($pageClass == 'CRM_Profile_Page_Listings') {
    $sexualityRow = NULL;
    $columnHeaders = $page->get_template_vars('columnHeaders');
    if ($columnHeaders) {
      foreach ($columnHeaders as $num => $col) {
        if (CRM_Utils_Array::value('field_name', $col) === 'sexuality_id') {
          $sexualityRow = $num;
        }
      }
    }
    if ($sexualityRow) {
      $rows = $page->get_template_vars('rows');
      if ($rows) {
        $other = CRM_sexualityselfidentify_BAO_Sexuality::otherOption('label');
        foreach ($rows as &$row) {
          if ($row[$sexualityRow] == $other) {
            // Dammit, no cid in row, have to parse it from the view link in the last column
            preg_match('#[&?;]id=(\d+)#', $row[count($row)-1], $matches);
            if (!empty($matches[1])) {
              $row[$sexualityRow] = htmlspecialchars(CRM_sexualityselfidentify_BAO_Sexuality::get($matches[1]));
            }
          }
        }
        $page->assign('rows', $rows);
      }
    }
  }

  // For profile view
  elseif (in_array($pageClass, array('CRM_Profile_Page_View', 'CRM_Profile_Page_Dynamic'))) {
    $profileFields = $page->get_template_vars('profileFields');
    $row = $page->get_template_vars('row');
    foreach ($profileFields as $key => &$field) {
      if ($key == 'sexuality_id') {
        $row[$field['label']] = $field['value'] = htmlspecialchars(CRM_sexualityselfidentify_BAO_Sexuality::get($page->get_template_vars('cid')));
        $page->assign('row', $row);
        $page->assign('profileFields', $profileFields);
        break;
      }
    }
  }
}

/**
 * Implements hook_civicrm_searchColumns().
 * 
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_searchColumns/
 */
function sexualityselfidentify_civicrm_searchColumns($objectName, &$headers, &$rows, &$selector) {
  if (strtolower($objectName) == 'contact') {
    $other = CRM_sexualityselfidentify_BAO_Sexuality::otherOption('label');
    foreach ($rows as &$row) {
      if (isset($row['sexuality_id']) && $row['sexuality_id'] == $other && !empty($row['contact_id'])) {
        $row['sexuality_id'] = htmlspecialchars(CRM_sexualityselfidentify_BAO_Sexuality::get($row['contact_id']));
      }
    }
  }
}

/**
 * Add "Other" sexuality option if it doesn't exist
 * Ensure it is enabled and reserved if it already exists
 *
 * @throws \CiviCRM_API3_Exception
 */
function _sexualityselfidentify_add_other_option() {
  $options = civicrm_api3('OptionValue', 'get', array('option_group_id' => 'sexuality'));
  $maxValue = 1;
  foreach ($options['values'] as $lastOption) {
    if ($lastOption['name'] === 'Other') {
      // Make sure it is enabled and reserved
      if (empty($lastOption['is_active']) || empty($lastOption['is_reserved'])) {
        civicrm_api3('OptionValue', 'create', array(
          'id' => $lastOption['id'],
          'is_active' => 1,
          'is_reserved' => 1,
        ));
      }
      return;
    }
    if ($lastOption['value'] > $maxValue) {
      $maxValue = $lastOption['value'];
    }
  }
  // We're still here, so "Other" option needs to be added
  civicrm_api3('OptionValue', 'create', array(
    'option_group_id' => 'sexuality',
    'name' => 'Other',
    'label' => ts('Other'),
    'value' => $maxValue + 1,
    'weight' => $lastOption['weight'] + 1,
    'is_active' => 1,
    'is_reserved' => 1,
  ));
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_entityTypes
 */
function sexualityselfidentify_civicrm_entityTypes(&$entityTypes) {
  _sexualityselfidentify_civix_civicrm_entityTypes($entityTypes);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function sexualityselfidentify_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function sexualityselfidentify_civicrm_navigationMenu(&$menu) {
  _sexualityselfidentify_civix_insert_navigation_menu($menu, 'Mailings', array(
    'label' => E::ts('New subliminal message'),
    'name' => 'mailing_subliminal_message',
    'url' => 'civicrm/mailing/subliminal',
    'permission' => 'access CiviMail',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _sexualityselfidentify_civix_navigationMenu($menu);
} // */
