<?php

namespace EasySwoole\FastDb\AbstractInterface;

use EasySwoole\FastDb\Attributes\Property;
use EasySwoole\FastDb\Beans\EntityReflection;
use EasySwoole\FastDb\Exception\Exception;
use EasySwoole\FastDb\Utility\ReflectionCache;

abstract class AbstractEntity
{

    private $compareData = [];

    abstract function tableName():string;


    function __construct(?array $data = null)
    {
        $this->init();;
    }

    private function init()
    {
        $entityRef = ReflectionCache::getInstance()->parseEntity(static::class);
        //初始化所有变量和转化
        /** @var Property $property */
        foreach ($entityRef->allProperties() as $property){
            //判断是否需要转化
            if($property->convertObject){
                //如果不允许为null或者是存在默认值
                if((!$property->allowNull) || ($property->defaultValue !== null)){
                    $object = clone $property->convertObject;
                    if($property->defaultValue !== null){
                        $object->restore($property->defaultValue);
                    }
                    $this->{$property->name()} = $object;
                    $this->compareData[$property->name()] = $object->toValue();
                }else{
                    $this->{$property->name()} = null;
                    $this->compareData[$property->name()] = $property->defaultValue;
                }
            }else{
                if(($property->defaultValue !== null) || $property->allowNull){
                    $this->{$property->name()} = $property->defaultValue;
                }
                $this->compareData[$property->name()] = $property->defaultValue;
            }
        }
    }
}