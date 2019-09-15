<?php


namespace Laradic\Generators\DocBlock;


class Result
{
    /** @var ClassDoc */
    protected $class;

    /** @var string */
    protected $doc;

    public function __construct(ClassDoc $class, string $doc)
    {
        $this->class = $class;
        $this->doc   = $doc;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function getDoc()
    {
        return $this->doc;
    }

    public function content()
    {

        $originalDocComment = $this->class->getDocComment();
        $classname          = $this->class->getShortName();
        $filename           = $this->class->getFileName();
        $contents           = $this->class->getContent();
        /** @noinspection ClassMemberExistenceCheckInspection */
        $type = method_exists($this, 'isInterface') && $this->class->isInterface() ? 'interface' : 'class';

        if ($originalDocComment) {
            $contents = str_replace($originalDocComment, $this->doc, $contents);
        } else {
            $needle  = "{$type} {$classname}";
            $replace = "{$this->doc}\n{$type} {$classname}";
            $pos     = strpos($contents, $needle);
            if ($pos !== false) {
                $contents = substr_replace($contents, $replace, $pos, strlen($needle));
            }
        }
        return $contents;
    }


}