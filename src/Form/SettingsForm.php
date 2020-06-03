<?php

namespace Drupal\burda_cmp\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Global settings form for consent management platform.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'burda_cmp.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'burda_cmp_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('burda_cmp.settings');

    $form['liveramp_script_url'] = [
      '#type' => 'url',
      '#title' => $this->t('LiveRamp script URL'),
      '#default_value' => $config->get('liveramp_script_url'),
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('burda_cmp.settings')
      ->set('liveramp_script_url', $form_state->getValue('liveramp_script_url'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
