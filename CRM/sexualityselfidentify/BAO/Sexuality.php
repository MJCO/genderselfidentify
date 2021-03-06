<?php

class CRM_sexualityselfidentify_BAO_Sexuality {

  /**
   * @return int
   * @throws \CiviCRM_API3_Exception
   */
  public static function getCustomFieldId($group, $name) {
    static $id;
    if (!$id) {
      $result = civicrm_api3('CustomField', 'getsingle', array(
        'return' => 'id',
        'custom_group_id' => $group,
        'name' => $name,
      ));
      $id = $result['id'];
    }
    return $id;
  }

  /**
   * @param string $ret
   * @return int
   * @throws \CiviCRM_API3_Exception
   */
  public static function otherOption($ret = 'value') {
    static $option;
    if (!$option) {
      $option = civicrm_api3('OptionValue', 'getsingle', array(
        'option_group_id' => 'sexuality',
        'name' => 'Other',
        'return' => $ret,
      ));
    }
    return $option[$ret];
  }

  /**
   * Returns string representation of contact's sexuality
   *
   * @param int $contactId
   * @return string
   * @throws \CiviCRM_API3_Exception
   */
  public static function get($contactId) {
    if (!$contactId) {
      return '';
    }
    $sexualityfieldid = CRM_sexualityselfidentify_BAO_Sexuality::getCustomFieldId('Demographics', 'Sexuality');
    $contact = civicrm_api3('Contact', 'getsingle', array(
      'return' => array('custom_' . $sexualityfieldid),
      'id' => $contactId,
    ));
    // Our api wrapper will have done all the work, just return it
    return $contact['sexuality'];
  }

  /**
   * @param string $input
   * @return int
   * @throws \CiviCRM_API3_Exception
   */
  public static function match($input) {
    $input = trim($input);
    if ($input) {
      $sexualityfieldid = CRM_sexualityselfidentify_BAO_Sexuality::getCustomFieldId('Demographics', 'Sexuality');
      $sexualityOptions = civicrm_api3('contact', 'getoptions', array('field' => 'custom_' . $sexualityfieldid));
      $sexualityOptions = $sexualityOptions['values'];
      if (is_numeric($input) && isset($sexualityOptions[$input])) {
        return $input;
      }
      foreach ($sexualityOptions as $key => $label) {
        if (strtolower($input) == substr(strtolower($label), 0, strlen($input))) {
          return $key;
        }
      }
      return CRM_sexualityselfidentify_BAO_Sexuality::otherOption();
    }
    return '';
  }
}