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

    $form['sourcepoint_script_url'] = [
      '#type' => 'url',
      '#title' => $this->t('Sourcepoint script URL'),
      '#default_value' => $config->get('sourcepoint_script_url'),
      '#required' => TRUE,
    ];

    $form['sourcepoint_shim_script'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Sourcepoint Shim URL'),
      '#default_value' => $config->get('sourcepoint_shim_script'),
      '#required' => TRUE,
    ];

    $form['sourcepoint_config_code'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Sourcepoint Config Code'),
      '#default_value' => $config->get('sourcepoint_config_code'),
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('burda_cmp.settings')
      ->set('sourcepoint_script_url', $form_state->getValue('sourcepoint_script_url'))
      ->set('sourcepoint_shim_script', $form_state->getValue('sourcepoint_shim_script'))
      ->set('sourcepoint_config_code', $form_state->getValue('sourcepoint_config_code'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
