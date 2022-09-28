<?php
/**
 * @file
 * Contains \Drupal\custom_formatter\Plugin\field\formatter\CustomSlugFormatter.
 */

namespace Drupal\custom_formatter\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Cocur\Slugify\Slugify;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
class CustomSlugFormatter extends FormatterBase {

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
        $slug = \Drupal::service('custom_formatter.cocur_slugify')->Convert($item->value,$slugifyseparator);
      }else{
        //$slug = $slugify->slugify($item->value);
        $slug = \Drupal::service('custom_formatter.cocur_slugify')->Convert($item->value,NULL);
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
      '#default_value' => $this->getSetting('slugify_separator'),
    ];
    return $form;
  }

}