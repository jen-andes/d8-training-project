<?php

namespace Drupal\d8t_system\Plugin\Block;

use Drupal\Core\Menu\MenuTreeParameters;
use Drupal\d8t_system\SimpleMenuBlockBase;

use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a topic navigation block.
 *
 * @Block(
 *   id = "d8t_header",
 *   admin_label = @Translation("Header block"),
 *   category = @Translation("D8T System")
 * )
 */
class HeaderBlock extends SimpleMenuBlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $headerMenu = $this->getHeaderMenu();

    $social_media = $this->getSocialMedia();
    $social_links = [];
    foreach ($social_media as $key => $name) {
      $social_links[$name] = $this->configuration[$name];
    }

    $content = [
      '#menu' => $headerMenu,
      '#email' => $this->configuration['email'],
      '#phone' => $this->configuration['phone'],
      '#social_links' => $social_links,
    ];

    return $content;
  }

  /**
   * Get header menu links.
   */
  protected function getHeaderMenu() {
    $menu_tree = \Drupal::menuTree();
    $parameters = new MenuTreeParameters();
    $parameters->onlyEnabledLinks();
    $manipulators = [
      ['callable' => 'menu.default_tree_manipulators:checkAccess'],
      ['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
    ];

    $header_nav_tree = $menu_tree->load('header-nav', $parameters);
    $header_nav_tree = $menu_tree->transform($header_nav_tree, $manipulators);

    return [
      'items' => $this->simplifyLinks($header_nav_tree),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $config = $this->getConfiguration();
    
    $form['email'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Email'),
      '#default_value' => isset($config['email']) ? $config['email'] : '',
    );

    $form['phone'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Phone'),
      '#default_value' => isset($config['phone']) ? $config['phone'] : '',
    );

    $social_media = $this->getSocialMedia();
    foreach ($social_media as $key => $name) {
      $label = ucfirst($name) . ' Link';
      $form[$name] = array(
        '#type' => 'url',
        '#title' => t($label),
        '#size' => 60,
        '#default_value' => isset($config[$name]) ? $config[$name] : '',
      );
    }

    return $form;
  }

  /**
   * {@inheritdoc}  
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
    $values = $form_state->getValues();
    $this->configuration['email'] = $values['email'];
    $this->configuration['phone'] = $values['phone'];

    $social_media = $this->getSocialMedia();
    foreach ($social_media as $key => $name) {
      $this->configuration[$name] = $values[$name];
    }
  }

  private function getSocialMedia() {
    return [
      'facebook',
      'twitter',
      'dribble',
      'behance',
      'feed'
    ];
  }
}
