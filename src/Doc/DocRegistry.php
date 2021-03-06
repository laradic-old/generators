<?php

namespace Laradic\Generators\Doc;

use Laradic\Generators\Doc\Doc\ClassDoc;

class DocRegistry
{
    /**
     * @var ClassDoc[]
     */
    protected $classes = [];

    /**
     * @param $className
     * @return ClassDoc
     */
    public function getClass($className)
    {
        if ( ! array_key_exists($className, $this->classes)) {
            $this->classes[ $className ] = new ClassDoc($className);
        }
        return $this->classes[ $className ];
    }

    public function getClasses()
    {
        return $this->classes;
    }
}