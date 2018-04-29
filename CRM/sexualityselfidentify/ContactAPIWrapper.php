<?php
class CRM_sexualityselfidentify_ContactAPIWrapper implements API_Wrapper {
  /**
   * @inheritdoc
   */
  public function fromApiInput($apiRequest) {
    $params =& $apiRequest['params'];
    if ($apiRequest['action'] == 'create') {
      if (isset($params['sexuality_id'])) {
        $customSexualityField = 'custom_' . CRM_sexualityselfidentify_BAO_Sexuality::getCustomFieldId();
        if (!trim($params['sexuality_id'])) {
          $params[$customSexualityField] = 'null';
        }
        else {
          $params[$customSexualityField] = trim($params['sexuality_id']);
          $params['sexuality_id'] = CRM_sexualityselfidentify_BAO_Sexuality::match($params['sexuality_id']);
          // Set to "Other"
          if ($params['sexuality_id'] === NULL) {
            $params['sexuality_id'] = CRM_sexualityselfidentify_BAO_Sexuality::otherOption();
          }
        }
      }
    }
    // If sexuality is specified in return params, we need to fetch the custom field as well.
    elseif ($apiRequest['action'] == 'get') {
      // Old-school syntax
      if (!empty($params['return.sexuality_id']) || !empty($params['return.sexuality'])) {
        $params['return.sexuality_id'] = 1;
        $params['return.custom_' . CRM_sexualityselfidentify_BAO_Sexuality::getCustomFieldId()] = 1;
      }
      if (!empty($params['return'])) {
        // Unfortunately the api accepts this param as an array or a string
        $return = is_string($params['return']) ? explode(',', str_replace(' ,', ',', $params['return'])) : $params['return'];
        if (in_array('sexuality', $return) && !in_array('sexuality_id', $return)) {
          $return[] = 'sexuality_id';
        }
        if (in_array('sexuality_id', $return)) {
          $customSexualityField = 'custom_' . CRM_sexualityselfidentify_BAO_Sexuality::getCustomFieldId();
          if (!in_array($customSexualityField, $return)) {
            $return[] = $customSexualityField;
          }
        }
        if (is_string($params['return'])) {
          $return = implode(',', $return);
        }
        $params['return'] = $return;
      }
    }
    return $apiRequest;
  }

  /**
   * @inheritdoc
   */
  public function toApiOutput($apiRequest, $result) {
    if ($apiRequest['action'] == 'get' && !empty($result['values'])) {
      foreach ($result['values'] as &$contact) {
        $this->fixContactSexuality($contact);
      }
    }
    return $result;
  }

  /**
   * Sets the "sexuality" field on a contact to the option label if it is a standard option,
   * or the contents of the custom field if it is "Other"
   *
   * @param array $contact
   */
  private function fixContactSexuality(&$contact) {
    $customSexualityField = 'custom_' . CRM_sexualityselfidentify_BAO_Sexuality::getCustomFieldId();
    $other = CRM_sexualityselfidentify_BAO_Sexuality::otherOption();
    if (array_key_exists('sexuality_id', $contact) && array_key_exists($customSexualityField, $contact)) {
      $contact['sexuality'] = !empty($contact['sexuality']) && ($contact['sexuality_id'] != $other || !strlen($contact[$customSexualityField])) ? $contact['sexuality'] : $contact[$customSexualityField];
    }
    elseif (!empty($contact['sexuality_id']) && $contact['sexuality_id'] == $other) {
      $contact['sexuality'] = CRM_sexualityselfidentify_BAO_Sexuality::get($contact['id']);
    }
  }
}