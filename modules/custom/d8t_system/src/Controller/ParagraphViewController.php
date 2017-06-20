<?php

namespace Drupal\d8t_system\Controller;
use Drupal\file\Entity\File;
use Drupal\node\Entity\Node;

/**
 * Sitewide paragraph preprocess
 */
class ParagraphViewController {

  /**
   * {@inheritdoc}
   */
  public function render(&$variables) {
    $paragraph = $variables['paragraph'];
    $paragraph_type = $paragraph->getType();

    // Menu.
    if ($paragraph_type == 'menu') {
      $items = self::getMenus();
      $variables['content']['menus'] = $items['menus'];
      $variables['content']['categories'] = $items['categories'];
    }

    // Upcoming Event.
    if ($paragraph_type == 'upcoming_event') {
      $max_num = $variables['content']['field_max_num'][0]['#markup'];
      $items = self::getEvents($max_num);
      $variables['content']['events'] = $items;
    }

    return $variables;
  }

  /**
  * Helper function to get list of menus.
  */
  private function getMenus() {
    $vid = 'menu_category';
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
    $categories = [];
    foreach ($terms as $key => $term) {
      $categories[] = [
        'id' => 'term' . $term->tid,
        'name' => $term->name,
      ];
    }

    $query = \Drupal::entityQuery('node');
    $nids = $query->condition('type', 'product')
      ->condition('status', 1)
      ->sort('created', 'DESC')
      ->range(0, 8)
      ->execute();
    $nodes = Node::loadMultiple($nids);

    $menus['#items'] = [];
    foreach ($nodes as $key => $node) {
      if ($node->get('field_menu_category')->isEmpty()) {
        continue;
      }
      $view_builder = \Drupal::entityManager()->getViewBuilder('node');
      $menus['#items'][] = $view_builder->view($node, 'teaser');
    }
    return [
      'categories' => $categories,
      'menus' => $menus,
    ];
  }

  /**
  * Helper function to get list of latest event.
  *
  * @param int $max_num
  *   The number of events to return
  */
  private function getEvents($max_num) {
    $query = \Drupal::entityQuery('node');
    $nids = $query->condition('type', 'event')
      ->condition('status', 1)
      ->sort('field_timestamp', 'DESC')
      ->range(0, $max_num)
      ->execute();
    $nodes = Node::loadMultiple($nids);
    $events['#items'] = [];
    foreach ($nodes as $key => $node) {
      $view_builder = \Drupal::entityManager()->getViewBuilder('node');
      $events['#items'][] = $view_builder->view($node, 'featured');
    }
    return $events;
  }

}