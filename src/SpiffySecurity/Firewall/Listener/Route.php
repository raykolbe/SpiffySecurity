<?php

namespace SpiffySecurity\Firewall\Listener;

use Zend\Mvc\MvcEvent;

class Route
{
    public static function onRoute(MvcEvent $e)
    {
        $app      = $e->getTarget();
        $route    = $e->getRouteMatch()->getMatchedRouteName();
        $security = $app->getServiceManager()->get('SpiffySecurity\Service\Security');

        if (!$security->getFirewall('route')->isGranted($route)) {
            $e->setError($security::ERROR_ROUTE_UNAUTHORIZED)
              ->setParam('identity', $security->getIdentity())
              ->setParam('route', $route);

            $app->getEventManager()->trigger('dispatch.error', $e);
        }
    }
}
