<?php

namespace Drupal\extra_field_set\Plugin\ExtraField\Display;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\extra_field\Plugin\ExtraFieldDisplayBase;
use Drupal\menu_link_content\MenuLinkContentInterface;

/**
 * Class ParentMenuItem.
 *
 * @package Drupal\extra_field_set\Plugin\ExtraField\Display
 *
 * @ExtraFieldDisplay(
 *   id = "parent_menu_item",
 *   label = @Translation("Parent menu item"),
 *   bundles = {
 *     "node.*"
 *   },
 *   visible = false
 * )
 */
class ParentMenuItem extends ExtraFieldDisplayBase {

  /**
   * {@inheritdoc}
   */
  public function view(ContentEntityInterface $entity) {
    $render = [];

    $entity_type = $entity->getEntityTypeId();
    /** @var \Drupal\Core\Menu\MenuLinkManagerInterface $menu_link_manager */
    $menu_link_manager = \Drupal::service('plugin.manager.menu.link');

    $menu_links = $menu_link_manager->loadLinksByRoute(
      'entity.node.canonical',
      [$entity_type => $entity->id()]
    );

    if (!empty($menu_links)) {
      // Select first parent with a valid route.
      foreach ($menu_links as $menu_link) {
        $plugin_id = $menu_link->getParent();
        if (!empty($plugin_id)) {
          list($menu_entity_type, $uuid) = explode(':', $plugin_id);
          /** @var \Drupal\menu_link_content\Entity\MenuLinkContent $parent_link */
          $parent_link = \Drupal::service('entity.repository')
            ->loadEntityByUuid($menu_entity_type, $uuid);
          if ($parent_link instanceof MenuLinkContentInterface) {
            // If the menu link has translation for the current language, load
            // it.
            /** @var \Drupal\Core\Language\LanguageManagerInterface $language_manager */
            $language_manager = \Drupal::service('language_manager');
            $current_langcode = $language_manager->getCurrentLanguage()->getId();
            if ($parent_link->hasTranslation($current_langcode)) {
              $parent_link = $parent_link->getTranslation($current_langcode);
            }
            // Ensure the route is different from <nolink>.
            $url = $parent_link->getUrlObject();
            if ($url->getRouteName() !== '<nolink>') {
              $render = [
                '#type' => 'container',
                '#attributes' => [
                  'class' => [
                    'parent-menu-link',
                  ],
                ],
                'menu_item' => [
                  '#type' => 'link',
                  '#title' => $parent_link->label(),
                  '#url' => $url,
                ],
              ];
              break;
            }
          }
        }
      }
    }
    return $render;
  }

}
