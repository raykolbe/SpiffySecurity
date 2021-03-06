<?php

namespace SpiffySecurity\Rbac;

use InvalidArgumentException;
use RecursiveIteratorIterator;

abstract class AbstractRole extends AbstractIterator
{
    /**
     * @var null|AbstractRole
     */
    protected $parent;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $permissions = array();

    /**
     * Get the name of the role.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add permission to the role.
     *
     * @param $name
     * @return AbstractRole
     */
    public function addPermission($name)
    {
        $this->permissions[$name] = true;
        return $this;
    }

    /**
     * Checks if a permission exists for this role or any child roles.
     *
     * @param string $name
     * @return bool
     */
    public function hasPermission($name)
    {
        if (isset($this->permissions[$name])) {
            return true;
        }

        $it = new RecursiveIteratorIterator($this, RecursiveIteratorIterator::CHILD_FIRST);
        foreach($it as $leaf) {
            if ($leaf->hasPermission($name)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Add a child.
     *
     * @param AbstractRole|string $child
     * @return Role
     */
    public function addChild($child)
    {
        if (is_string($child)) {
            $child = new Role($child);
        }
        if (!$child instanceof AbstractRole) {
            throw new InvalidArgumentException(
                'Child must be a string or instance of SpiffySecurity\Role\AbstractRole'
            );
        }

        $child->setParent($this);
        $this->children[] = $child;
        return $this;
    }

    /**
     * @param AbstractRole $parent
     * @return AbstractRole
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return null|AbstractRole
     */
    public function getParent()
    {
        return $this->parent;
    }
}