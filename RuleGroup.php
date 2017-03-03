<?php 
namespace Cooker\Rule;
use       Cooker\Rule\Rule;

class RuleGroup implements \Serializable
{

    private $mark      = '';

    private $connector = 'AND';

    private $rules     = array();

    public function __construct($mark = '', array $rules = array(),  $connector = 'AND'){
        $this->setMark($mark);
        $this->setRules($rules);
        $this->setConnector($connector);
    }

    public function reset(){
        $this->mark      = '';
        $this->connector = 'AND';
        $this->rules     = array();
    }
    public function setMark($mark = ''){
        $this->mark = $mark;
        return $this;
    }

    public function getMark(){
        return $this->mark;
    }

    public function setRules($rules = null){
        $ruleClass =  'Cooker\Rule\Rule';
        $rules     = is_array($rules) ? $rules : array($rules);
        foreach($rules as $rule){
            if($rule instanceof $ruleClass){
                $this->rules[md5((string)$rule)] = $rule;    
            }elseif($rule instanceof RuleGroup){
                $this->addRuleGroup($rule); 
            } 

        }
        return $this;
    }

    public function getRules(){
        return array_values($this->rules);
    }

    public function resetRules(){
        $this->rules = array();
    }

    public function setConnector($connector){
        $this->connector = $connector; 
        return $this;
    }

    public function getConnector(){
        return (string)$this->connector;
    }

    public function addRuleGroup(RuleGroup $ruleGroup){
        if($ruleGroup->getConnector() === $this->getConnector()){
            $this->setRules($ruleGroup->getRules()); 
        }else{
            $this->rules[md5((string)$ruleGroup)] = $ruleGroup; 
        }
        return $this;
    }

    public function serialize(){
        return serialize(array(
            'rules'     => serialize($this->rules),
            'connector' => $this->connector,
            'mark'      => $this->mark
        )); 
    }

    public function unserialize($unserialize){
        $data = unserialize($unserialize); 
        $this->rules     = unserialize($data['rules']);
        $this->connector = $data['connector'];
        $this->mark      = $data['mark'];
    }

    public function __toString(){
        return implode('', $this->rules).strtoupper($this->connector); 
    }
}
