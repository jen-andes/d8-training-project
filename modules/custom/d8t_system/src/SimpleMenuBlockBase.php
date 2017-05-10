<?php

namespace Drupal\d8t_system;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Umby Navigation Simple Menu' block base class.
 */
abstract class SimpleMenuBlockBase extends BlockBase {

  /**
   * Map menu tree into an array.
   *
   * @param array $links
   *   The array of menu tree links.
   * @param string $submenu_key
   *   The key for the submenu to simplify.
   *
   * @return array
   *   The simplified menu tree array.
   */
  protected function simplifyLinks(array $links, $submenu_key = 'submenu') {
    $current_path =  \Drupal::service('path.current')->getPath();
    $result = [];

    foreach ($links as $item) {
      $is_active = false;
      if ($item->link->getUrlObject()->toString() == $current_path) {
        $is_active = true;
      }

      $simplified_link = [
        'text' => $item->link->getTitle(),
        'url' => $item->link->getUrlObject()->toString(),
        'is_active' => $is_active,
      ];

      if ($item->hasChildren) {
        $simplified_link[$submenu_key] = $this->simplifyLinks($item->subtree);
        foreach ($simplified_link[$submenu_key] as $submenu) {
          if ($submenu['is_active'] == true) {
            $simplified_link['is_active'] = true;
            break;
          }
        }
      } 

      $result[] = $simplified_link;
    }

    return $result;
  }

}
