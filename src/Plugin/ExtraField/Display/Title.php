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
 *   id = "title",
 *   label = @Translation("Title"),
 *   bundles = {
 *     "node.*",
 *     "taxonomy_term.*"
 *   },
 *   visible = false
 * )
 */
class Title extends ExtraFieldPlusDisplayBase {

  /**
   * {@inheritdoc}
   */
  public function view(ContentEntityInterface $entity) {
    $title = $entity->label();
    if ($this->getEntityExtraFieldSetting('linked')) {
      $title = Link::fromTextAndUrl($title, $entity->toUrl())->toString();
    }
    $output = [];
    $output[] = [
      '#type' => 'html_tag',
      '#tag' => $this->getEntityExtraFieldSetting('tag'),
      '#value' => $title,
      '#attributes' => [
        'class' => [$this->getEntityExtraFieldSetting('class')],
      ],
    ];
    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public static function getExtraFieldSettingsForm(string $field_id, string $entity_type_id, string $bundle, string $view_mode = 'default'): array {
    $form = parent::getExtraFieldSettingsForm($field_id, $entity_type_id, $bundle, $view_mode);
    $heading_options = [
      'span' => 'span',
      'div' => 'div',
    ];
    foreach (range(1, 5) as $level) {
      $heading_options['h' . $level] = 'H' . $level;
    }
    $form['tag'] = [
      '#title' => t('Tag'),
      '#type' => 'select',
      '#description' => t('Select the tag which will be wrapped around the title.'),
      '#options' => $heading_options,
    ];
    $form['linked'] = [
      '#title' => t('Link to the Content'),
      '#type' => 'checkbox',
      '#description' => t('Wrap the title with a link to the content.'),
    ];
    $form['class'] = [
      '#title' => t('CSS Class'),
      '#type' => 'textfield',
      '#description' => t('An optional css class to add to the wrapper.'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  protected static function defaultExtraFieldSettings() : array {
    $values = parent::defaultExtraFieldSettings();

    $values += [
      'tag' => 'h2',
      'linked' => FALSE,
      'class' => '',
    ];

    return $values;
  }

}
