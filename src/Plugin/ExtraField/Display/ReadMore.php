<?php

namespace Drupal\extra_field_set\Plugin\ExtraField\Display;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\extra_field_plus\Plugin\ExtraFieldPlusDisplayBase;
use Drupal\Core\Link;

/**
 * Class NodeExtraFieldDisplay.
 *
 * @package Drupal\extra_field_set\Plugin\ExtraField\Display
 *
 * @ExtraFieldDisplay(
 *   id = "read_more",
 *   label = @Translation("Read more"),
 *   bundles = {
 *     "node.*"
 *   },
 *   visible = false
 * )
 */
class ReadMore extends ExtraFieldPlusDisplayBase {

  /**
   * {@inheritdoc}
   */
  public function view(ContentEntityInterface $entity) {
    // phpcs:ignore
    $text = $this->t((string) $this->getEntityExtraFieldSetting('text'));
    if ($this->getEntityExtraFieldSetting('linked')) {
      $text = Link::fromTextAndUrl($text, $entity->toUrl())->toString();
    }
    $output = [];
    $output[] = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#attributes' => ['class' => 'read-more-link'],
      '#value' => $text,
    ];
    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public static function getExtraFieldSettingsForm(string $field_id, string $entity_type_id, string $bundle, string $view_mode = 'default'): array {
    $form = parent::getExtraFieldSettingsForm($field_id, $entity_type_id, $bundle, $view_mode);

    $form['linked'] = [
      '#title' => t('Link to the content'),
      '#type' => 'checkbox',
      '#description' => t('Link to the content (or just show as plain text).'),
    ];
    $form['text'] = [
      '#title' => 'Text to show',
      '#type' => 'textfield',
      '#description' => t('Text to be shohwn. Use original language, it will be translatable.'),
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  protected static function defaultExtraFieldSettings() : array {
    $values = parent::defaultExtraFieldSettings();

    $values += [
      'linked' => '1',
      'text' => t('Read more'),
    ];

    return $values;
  }

}
