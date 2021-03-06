<?php

namespace SpiffySecurity\Service;

use SpiffySecurity\View\UnauthorizedStrategy;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UnauthorizedStrategyFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
        $security = $sl->get('SpiffySecurity\Service\Security');

        $strategy = new UnauthorizedStrategy;
        $strategy->setUnauthorizedTemplate($security->options()->getTemplate());

        return $strategy;
    }
}