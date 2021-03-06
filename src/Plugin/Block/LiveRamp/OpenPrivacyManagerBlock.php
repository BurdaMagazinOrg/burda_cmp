<?php

namespace Drupal\burda_cmp\Plugin\Block\LiveRamp;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides an 'Open privacy manager' link block for LiveRamp integration.
 *
 * @Block(
 *   id = "burda_cmp_liveramp_open_privacy_manager",
 *   admin_label = @Translation("Open privacy manager (LiveRamp)"),
 * )
 */
class OpenPrivacyManagerBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['link_text'] = $form_state->getValue('link_text');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [
      'link' => [
        '#type' => 'html_tag',
        '#tag' => 'a',
        '#value' => $this->configuration['link_text'] ?: $this->t('Open privacy manager'),
        '#attributes' => [
          'href' => '#!',
          'data-burda-cmp-open-privacy-manager' => TRUE,
        ],
        '#attached' => [
          'library' => [
            'burda_cmp/liveramp.open-privacy-manager',
          ],
        ],
      ],
    ];

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);

    $form['link_text'] = [
      '#title' => $this->t('Link text'),
      '#description' => $this->t('The text of the link (defaults to %link_text)', [
        '%link_text' => $this->t('Open privacy manager'),
      ]),
      '#type' => 'textfield',
      '#default_value' => $this->configuration['link_text'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'link_text' => '',
    ];
  }
}
