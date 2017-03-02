<?php 
namespace Cooker\Rule;
use       Cooker\Rule\Rule;

class RuleGroup
{

    private $connector = 'AND';

    private $rules     = array();

    public function __construct(array $rules = array(),  $connector = 'AND'){
        $this->setRules($rules);
        $this->setConnector($connector);
    }


    public function setRules(array $rules){
        $class =  'Cooker\Rule\Rule';
        $rules = array_filter($rules, function($rule) use ($class){
            return $rule instanceof $class; 
        });
        $this->rules = $rules; 
        return $this;
    }

    public function getRules(){
        return $this->rules;
    }

    public function setConnector($connector){
        $this->connector = $connector; 
        return $this;
    }

    public function getConnector(){
        return $this->connector;
    }

}
