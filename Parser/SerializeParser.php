<?php 
namespace Cooker\Rule\Parser;
use       Cooker\Rule\Parser\RuleParserInterface;

class SerializeParser implements RuleParserInterface
{

    public function parse($data = array()){
        return unserialize($data);     
    }
}
