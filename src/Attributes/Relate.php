<?php

namespace EasySwoole\FastDb\Attributes;


#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Relate
{
    const RELATE_ONE_TO_NOE = 1;
    const RELATE_ONE_TO_MULTIPLE = 2;

    function __construct(
        public string $targetEntity,
        public int $relateType = self::RELATE_ONE_TO_NOE,
        public ?string $relateProperty = null
    ){

    }
}