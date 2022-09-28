<?php
/**
 * @file
 * Contains \Drupal\custom_formatter\Plugin\Field\FieldFormatter.
 */

namespace Drupal\custom_formatter\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Cocur\Slugify\Slugify;
use Drupal\Core\Field\FieldDefinitionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\custom_formatter\CocurSlugifyInterface;

/**
 * Plugin implementation of the 'CustomSlugFormatter' formatter.
 *
 * @FieldFormatter(
 *   id = "sample_custom_slug_formatter",
 *   label = @Translation("Custom Slug Formatter"),
 *   field_types = {
 *     "string_long",
 *     "string"
 *   }
 * )
 */
class CustomSlugFormatter extends FormatterBase implements ContainerFactoryPluginInterface{

  /**
   * The CocurSlugify service
   *
   * @var \Drupal\custom_formatter\CocurSlugifyInterface
   */
  protected $convertSlug;

  /**
   * Construct a CocurSlugify object
   *
   * @param string $plugin_id
   *   The plugin_id for the formatter.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The definition of the field to which the formatter is associated.
   * @param array $settings
   *   The formatter settings.
   * @param string $label
   *   The formatter label display setting.
   * @param string $view_mode
   *   The view mode.
   * @param array $third_party_settings
   *   Any third party settings settings.
   * @param \Drupal\custom_formatter\CocurSlugifyInterface $convertSlug
   *   Allow to tonvert a text into slug.
   */

  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings, CocurSlugifyInterface $convertSlug) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);

    $this->convertSlug = $convertSlug;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('custom_formatter.cocur_slugify')
    );
  }
  
  /**
  * {@inheritdoc}
  */
  public function settingsSummary() {
    $summary = [];
    $settings = $this->getSettings();
    if(isset($settings['slugify_separator'])&&!empty($settings['slugify_separator'])){
      $summary[] = $this->t('Convert Text to Slug & Slugify Separator: ') . $settings['slugify_separator'];
    }else{
      $summary[] = $this->t('Convert Text to Slug');
    }
    return $summary;
  }

  /**
  * {@inheritdoc}
  */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = array();

    foreach ($items as $delta => $item) {
      //$slugify = new Slugify();
      $settings = $this->getSettings();
      if(isset($settings['slugify_separator'])&&!empty($settings['slugify_separator'])){
        $slugifyseparator = $settings['slugify_separator'];
        //$slug = $slugify->slugify($item->value,$slugifyseparator);
        $slug = $this->convertSlug->Convert($item->value, $separator);
        //$slug = \Drupal::service('custom_formatter.cocur_slugify')->Convert($item->value,$slugifyseparator);
      }else{
        //$slug = $slugify->slugify($item->value);
        //$slug = \Drupal::service('custom_formatter.cocur_slugify')->Convert($item->value,NULL);
        $slug = $this->convertSlug->Convert($item->value, NULL);
      }
      $elements[$delta] = [
        '#theme' => 'sample_custom_slug_formatter',
        '#value' => $slug,
      ];
    }
  
    return $elements;
  }

  /**
  * {@inheritdoc}
  */
  public static function defaultSettings() {
    return [
        'slugify_separator' => '', // Slugify Separator that you want to display.
      ] + parent::defaultSettings();
  }

  /**
  * {@inheritdoc}
  */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::settingsForm($form, $form_state);
    $form['slugify_separator'] = [
      '#title' => $this->t('Slugify Separator'),
      '#description' => $this->t('The Slugify Separator that you want to use.'),
      '#type' => 'textfield',
      '#size' => 5,
      '#default_value' => $this->getSetting('slugify_separator'),
    ];
    return $form;
  }

}