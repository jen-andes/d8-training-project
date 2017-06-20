<?php

namespace Drupal\d8t_system\Controller;
use Drupal\d8t_system\SimpleMenu;

/**
 * Sitewide block view preprocess
 */
class BlockViewController {

  /**
   * {@inheritdoc}
   */
  public function render(&$variables) {
    $id = $variables['elements']['#id'];

    // menus
    $menu_map = ['header' => 'main', 'footer' => 'footer'];
    $menu_id  = $menu_map[$id];
    $simpleMenu = new SimpleMenu();
    $menus = $simpleMenu->getMenu($menu_id);

    // hero
    $node = \Drupal::routeMatch()->getParameter('node');
    if ($node){
      $node_view_builder = \Drupal::entityTypeManager()->getViewBuilder('node');
      $variables['content']['hero'] = $node_view_builder->view($node, 'hero');
      $variables['content']['pagetype'] = ($node->getType() == 'homepage') ? 'default' : 'single';
    }

    $variables['content']['menus'] = $menus['items'];
    return $variables;
  }

}