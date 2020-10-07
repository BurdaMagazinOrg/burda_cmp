<?php

namespace Drupal\burda_cmp\Plugin\Filter;

use Drupal\burda_cmp\StaticConsentDataInterface;
use Drupal\Component\Utility\Html;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a filter to to rewrite inline iFrames to be CMP compliant.
 *
 * Currently support the following iFrame types:
 *   - Google Maps
 *
 * All other iFrame types are filtered out completely.
 *
 * @Filter(
 *   id = "filter_burda_cmp_iframe",
 *   title = @Translation("Cookie consent compliant iFrames"),
 *   description = @Translation("Automatically makes iFrames cookie consent compliant. Either by displaying a placeholder before consented or by removing the whole iFrame."),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_TRANSFORM_REVERSIBLE
 * )
 */
class CmpCompliantIFrameFilter extends FilterBase implements ContainerFactoryPluginInterface {

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * Constructs a FilterIFrame object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, RendererInterface $renderer) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('renderer')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function process($text, $langcode) {
    $result = new FilterProcessResult($text);

    if (stristr($text, '<iframe') !== FALSE) {
      $dom = Html::load($text);
      $xpath = new \DOMXPath($dom);

      /** @var \DOMElement $node */
      foreach ($xpath->query('//iframe') as $node) {

        // Read the src attribute's value.
        $src = Html::escape($node->getAttribute('src'));

        // Google Maps.
        if (preg_match('!google\.com\/maps\/embed!', $src)) {
          $this->replaceWithCmpCompliantDomNode($node, StaticConsentDataInterface::VENDOR_GOOGLE_MAPS);
        }

        // Allow privacy policy iFrames.
        elseif (preg_match('!cdn\.datenschutz\.!', $src) && preg_match('!burda\.com!', $src)) {
          continue;
        }

        // Allow LeadGen iFrames.
        elseif (preg_match('!leadgen\.sso\-service\.de!', $src)) {
          continue;
        }

        // Allow Biallo iFrames.
        elseif (preg_match('!koop\.biallo\.de!', $src)) {
          continue;
        }
        elseif (preg_match('!geldsparen\.de!', $src)) {
          continue;
        }

        // @todo Support any other iFrames? If so, add conditions and processing
        //   logic here.

        // Any other iFrame will be removed.
        else {
          $node->parentNode->removeChild($node);
        }
      }

      $result->setProcessedText(Html::serialize($dom))
        ->addAttachments([
          'library' => [
            'burda_cmp/liveramp.conditional-content',
          ],
        ]);
    }

    return $result;
  }

  /**
   * Replace DOM node with CMP compliant DOM node.
   *
   * @param \DOMNode $node
   *   The DOM node to replace with a CMP compliant equivalent.
   * @param int|string $vendor
   *   The vendor name as defined by the StaticConsentDataInterface::VENDOR_*
   *   constants or a numeric vendor ID.
   * @param array $purposes
   *   An array of consent purpose IDs. Leave empty to use defaults defined in
   *   static cookie consent data (if any).
   * @param string $vendor_label
   *   The human-readable vendor label. Leave empty to use default defined in
   *   static cookie consent data (if any).
   * @param $toggle_label
   *   The label used for the consent toggle button. Leave empty to use default
   *   defined in static cookie consent data (if any).
   *
   * @see \Drupal\burda_cmp\StaticConsentDataInterface
   */
  protected function replaceWithCmpCompliantDomNode(\DOMNode $node, $vendor, array $purposes = [], $vendor_label = NULL, $toggle_label = NULL) {
    if (($node_compliant = $this->createCmpCompliantDomNode($node->ownerDocument->saveHTML($node), $vendor, $purposes, $vendor_label, $toggle_label))) {
      try {
        $node_compliant = $node->ownerDocument->importNode($node_compliant, TRUE);
        $node->parentNode->replaceChild($node_compliant, $node);
      }
      catch (\Exception $e) {
        // Do nothing yet...
      }
    }
  }

  /**
   * Create CMP compliant DOM node for given output, vendor and purposes.
   *
   * @param string $content
   *   The content to be CMP compliant.
   * @param int|string $vendor
   *   The vendor name as defined by the StaticConsentDataInterface::VENDOR_*
   *   constants or a numeric vendor ID.
   * @param array $purposes
   *   An array of consent purpose IDs. Leave empty to use defaults defined in
   *   static cookie consent data (if any).
   *   The human-readable vendor label. Leave empty to use default defined in
   *   static cookie consent data (if any).
   * @param $toggle_label
   *   The label used for the consent toggle button. Leave empty to use default
   *   defined in static cookie consent data (if any).
   *
   * @return \DOMNode|null
   *   Returns the CMP compliant DOM node on success, otherwise NULL.
   *
   * @see \Drupal\burda_cmp\StaticConsentDataInterface
   */
  protected function createCmpCompliantDomNode($content, $vendor, array $purposes = [], $vendor_label = NULL, $toggle_label = NULL) {
    $conditional_element = [
      '#theme' => 'burda_cmp_conditional_content',
      '#content' => $content,
      '#vendor' => $vendor,
      '#purposes' => $purposes,
      '#vendor_label' => $vendor_label,
      '#toggle_label' => $toggle_label,
    ];

    $output = $this->renderer->render($conditional_element);

    try {
      $dom = Html::load('<div data-burda-cmp-conditional-content-inline="true">' . $output . '</div>');
      $xpath = new \DOMXPath($dom);

      if (($nodes = $xpath->query('//*[@data-burda-cmp-conditional-content-inline="true"]'))) {
        return $nodes->item(0);
      }
    }
    catch (\Exception $e) {
      // Do nothing yet...
    }

    return NULL;
  }

}
