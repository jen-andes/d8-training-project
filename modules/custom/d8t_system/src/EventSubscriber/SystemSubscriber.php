<?php

namespace Drupal\d8t_system\EventSubscriber;
 
use Drupal;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Drupal\Core\Routing\RouteMatch;

class SystemSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = array('checkLoginNeeded');
    $events[KernelEvents::REQUEST][] = array('checkNodeRedirect');
    $events[KernelEvents::REQUEST][] = array('checkUserRedirect');
    return $events;
  }

  /**
   * Redirect anonymous user to login page if he encounters 403
   * response.
   *
   * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
   *   The created response object that will be returned.
   */
  public function checkLoginNeeded(GetResponseEvent $event) {
    $routeMatch = RouteMatch::createFromRequest($event->getRequest());
    $route_name = $routeMatch->getRouteName();
    $is_anonymous = \Drupal::currentUser()->isAnonymous();
    $is_not_login = $route_name != 'user.login';
    if ($is_anonymous && $route_name == 'system.403' && $is_not_login) {
      $query = $event->getRequest()->query->all();
      $login_uri = \Drupal::url('user.login', [], ['query' => $query]);
      $event = new RedirectResponse($login_uri);
      return $event->send();
    }
  }

  /**
   * Add a destination paramater on user login page
   *
   * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
   *   The created response object that will be returned.
   */
  public function checkUserRedirect(GetResponseEvent $event) {
    $routeMatch = RouteMatch::createFromRequest($event->getRequest());
    $route_name = $routeMatch->getRouteName();
    $query = $event->getRequest()->query->all();
    if ($route_name == 'user.login' && !isset($query['destination'])) {
      $request = $event->getRequest();
      $request->query->add(['destination' => '/admin/content']);
    }
  }

  /**
   * Add a destination paramater when adding node
   *
   * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
   *   The created response object that will be returned.
   */
  public function checkNodeRedirect(GetResponseEvent $event) {
    $routeMatch = RouteMatch::createFromRequest($event->getRequest());
    $route_name = $routeMatch->getRouteName();
    if ($route_name == 'node.add') {
      $request = $event->getRequest();
      $request->query->add(['destination' => '/admin/content']);
    }
  }

}