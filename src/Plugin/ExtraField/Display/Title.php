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
 *     "node.*"
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
    if ($this->getSetting('linked')) {
      $title = Link::fromTextAndUrl($title, $entity->toUrl())->toString();
    }
    $output = [];
    $output[] = [
      '#type' => 'html_tag',
      '#tag' => $this->getSetting('tag'),
      '#value' => $title,
      '#attributes' => [
        'class' => [$this->getSetting('class')],
      ],
    ];
    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm() {
    $form = parent::settingsForm();
    $heading_options = [
      'span' => 'span',
      'div' => 'div',
    ];
    foreach (range(1, 5) as $level) {
      $heading_options['h' . $level] = 'H' . $level;
    }
    $form['tag'] = [
      '#title' => $this->t('Tag'),
      '#type' => 'select',
      '#description' => $this->t('Select the tag which will be wrapped around the title.'),
      '#options' => $heading_options,
    ];
    $form['linked'] = [
      '#title' => $this->t('Link to the Content'),
      '#type' => 'checkbox',
      '#description' => $this->t('Wrap the title with a link to the content.'),
    ];
    $form['class'] = [
      '#title' => $this->t('CSS Class'),
      '#type' => 'textfield',
      '#description' => $this->t('An optional css class to add to the wrapper.'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultFormValues() {
    $values = parent::defaultFormValues();

    $values += [
      'tag' => 'h2',
      'linked' => FALSE,
      'class' => '',
    ];

    return $values;
  }

}
