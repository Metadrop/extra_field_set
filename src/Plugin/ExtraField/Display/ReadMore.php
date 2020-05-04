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
    $text = $this->t((string) $this->getSetting('text'));
    if ($this->getSetting('linked')) {
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
  public function settingsForm() {
    $form = parent::settingsForm();

    $form['linked'] = [
      '#title' => $this->t('Link to the content'),
      '#type' => 'checkbox',
      '#description' => $this->t('Link to the content (or just show as plain text).'),
    ];
    $form['text'] = [
      '#title' => 'Text to show',
      '#type' => 'textfield',
      '#description' => $this->t('Text to be shohwn. Use original language, it will be translatable.'),
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultFormValues() {
    $values = parent::defaultFormValues();

    $values += [
      'linked' => '1',
      'text' => $this->t('Read more'),
    ];

    return $values;
  }

}
