<?php

namespace Drupal\d8t_system\TwigExtension;

use Drupal\Core\Controller\ControllerResolverInterface;
use Drupal\Core\Routing\UrlGeneratorInterface;
use Drupal\Core\Theme\ThemeManagerInterface;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Template\TwigExtension;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Render\Element;
use Drupal\Core\Render\ElementInfoManagerInterface;
use Drupal\file\Entity\File;

/**
 * A Twig extension that adds a Drupal pre-render Twig function.
 */
class PreRenderExtension extends TwigExtension {

  /**
   * The Controller Resolver.
   *
   * @var \Drupal\Core\Controller\ControllerResolverInterface
   */
  protected $controllerResolver;

  /**
   * Constructs \Drupal\Core\Template\TwigExtension.
   *
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer.
   * @param \Drupal\Core\Routing\UrlGeneratorInterface $url_generator
   *   The URL generator.
   * @param \Drupal\Core\Theme\ThemeManagerInterface $theme_manager
   *   The theme manager.
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   The date formatter.
   */
  public function __construct(RendererInterface $renderer, UrlGeneratorInterface $url_generator, ThemeManagerInterface $theme_manager, DateFormatterInterface $date_formatter, ControllerResolverInterface $controller_resolver) {
    parent::__construct($renderer, $url_generator, $theme_manager, $date_formatter);
    $this->controllerResolver = $controller_resolver;
    $this->renderer = $renderer;
    $this->urlGenerator = $url_generator;
    $this->themeManager = $theme_manager;
    $this->dateFormatter = $date_formatter;
  }

  /**
   * Gets a unique identifier for this Twig extension.
   *
   * @return string
   *   A unique identifier for this Twig extension.
   */
  public function getName() {
    return 'd8t_system.pre_render_extension';
  }

  /**
   * Generates a list of all Twig functions that this extension defines.
   *
   * @return array
   *   A key/value array that defines custom Twig functions. The key denotes the
   *   function name used in the tag, e.g.:
   *
   * @code
   *   {{ pre_render() }}
   * @endcode
   *
   *   The value is a standard PHP callback that defines what the function does.
   */
  public function getFunctions() {
    return [
      'pre_render' => new \Twig_SimpleFunction(
        'pre_render',
        [$this, 'preRenderFunction']
      ),
      'image_attr' => new \Twig_SimpleFunction(
        'image_attr',
        [$this, 'imageAttrFunction']
      ),
    ];
  } 

  /**
   * Pre-renders and preprocesses a render array.
   *
   * @param array $variables
   *   The render array.
   */
  protected function preRender(&$variables) {
    $controller_resolver = $this->controllerResolver;

    // If the default values for this element have not been loaded yet, populate
    // them.
    $children = [];
    if (isset($variables['#sorted'])) {
      $children = Element::children($variables);
    }
    if (isset($variables['#type']) && empty($variables['#defaults_loaded'])) {
      $variables += $this->elementInfo->getInfo($variables['#type']);
    }

    // Runs each pre-render function.
    if (isset($variables['#pre_render'])) {
      foreach ($variables['#pre_render'] as $callable) {
        if (is_string($callable) && strpos($callable, '::') === FALSE) {
          $callable = $controller_resolver->getControllerFromDefinition($callable);
        }
        $variables = call_user_func($callable, $variables);
      }
    }
  }

  /**
   * Returns an array of fully pre-rendered and preprocessed components.
   *
   * @param array $variables
   *   The input render array.
   *
   * @return array
   *   An array of render array components.
   */
  public function preRenderFunction($variables) {
    // Avoid re-rendering any array.
    if (!empty($variables['directory'])) {
      return $variables;
    }
    $this->preRender($variables);
    return $variables;
  }

  /**
   * Returns an array of image attributes from an input field.
   *
   * @param array $variables
   *   The field variables render array.
   *
   * @return array
   *   An array of image attributes.
   */
  public function imageAttrFunction($variables) {
    if (!isset($variables['#field_type']) || $variables['#field_type'] != 'image') {
      return $variables;
    }

    $count = $variables['#items']->count();
    $attributes = [];
    foreach ($variables['#items'] as $key => $item) { 
      if ($count == 1) {
        $image = $variables[$key];
        $fid = $image['#item']->target_id;
        $alt = $image['#item']->alt;
      }else{
        $fid = $item->get('target_id')->getValue();
        $alt = $item->get('alt')->getValue();
      }

      // load image to get the URI
      $file = File::load($fid);
      $uri = $file->getFileUri();
      $image_attr = [
        'src' => isset($uri) ? file_create_url($uri) : NULL,
        'alt' => $alt,
      ];
      $attributes[] = $image_attr;
    }
    
    if (count($attributes) == 1) {
      $attributes = $attributes[0];
    }

    return $attributes;
  }

}