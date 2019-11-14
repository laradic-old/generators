<?php

namespace Laradic\Generators\DocBlock\Definition;

use Reflector;
use Barryvdh\Reflection\DocBlock;

class ClassDefinition extends Definition
{
    /** @var \Laradic\Generators\DocBlock\Definition\DefinitionBuilder */
    protected $builder;

    /** @var \Laradic\Generators\DocBlock\Definition\DefinitionCollection */
    protected $collection;

    public function __construct(DefinitionBuilder $builder, Reflector $reflection, DocBlock $docBlock)
    {
        parent::__construct('class', $reflection, $docBlock);
        $this->builder    = $builder;
        $this->collection = new DefinitionCollection([ $this ]);
    }

    public function method($name)
    {
        $this->collection->push($definition = $this->builder->method($this, $name));
        return $definition;
    }

    public function property($name)
    {
        $this->collection->push($definition = $this->builder->property($this, $name));
        return $definition;
    }

    public function methods(array $methods)
    {
        foreach ($methods as $name => $params) {
            $this->method($name)->cleanAllTags()->ensureTag($params[ 0 ], $params[ 1 ]);
        }
        return $this;
    }

    public function properties(array $properties)
    {
        foreach ($properties as $name => $params) {
            $this->property($name)->cleanAllTags()->ensureTag($params[ 0 ], $params[ 1 ]);
        }
        return $this;
    }

    public function getCollection()
    {
        return $this->collection;
    }

    public function collect()
    {
        return $this->getCollection();
    }


}