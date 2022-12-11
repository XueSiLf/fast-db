<?php

namespace EasySwoole\FastDb;

use EasySwoole\FastDb\Attributes\Property;
use EasySwoole\Mysqli\QueryBuilder;

abstract class Entity
{
    const FILTER_NOT_NULL = 2;
    const FILTER_ASSOCIATE_RELATION = 4;

    protected array $properties = [];
    protected array $propertyReflections = [];
    protected array $relateValues = [];

    final function __construct(?array $data = null){
        $this->reflection();
        $this->initialize();
    }

    abstract function tableName():string;


    final static function getOne(callable $whereCall):?static
    {
        $queryBuilder = new QueryBuilder();
        call_user_func($whereCall,$queryBuilder);
        $mode = new static();
        return $mode;
    }

    function save()
    {

    }

    function update()
    {

    }

    function delete()
    {

    }

    function toArray($filter = null):array
    {
        if($filter == null){
            return $this->properties;
        }
        $temp = $this->properties;
        if($filter == 2 || $filter == 6){
            foreach ($temp as $key => $item){
                if($item === null){
                    unset($temp[$key]);
                }
            }
        }
        if($filter == 4 || $filter == 6){
            //做关联判定处理
        }
        return $temp;
    }

    protected function initialize(): void
    {

    }

    private function reflection(): void
    {
        $ref = new \ReflectionClass(static::class);
        $list = $ref->getProperties(\ReflectionProperty::IS_PUBLIC|\ReflectionProperty::IS_PROTECTED);
        foreach ($list as $property){
            $temp = $property->getAttributes(Property::class);
            if(!empty($temp)){
                $this->properties[$property->name] = $property->getDefaultValue();
                $this->propertyReflections[$property->name] = $temp[0];
            }
        }
    }


}