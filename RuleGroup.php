<?php 

/**
 *
 * The file is part of Cooker\Rule 
 * (c) Cooker <thinklang0917@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 **/

namespace Cooker\Rule;
use       Cooker\Rule\Rule;

/**
 *
 * RuleGroup class file 
 *  1. implements interface Serializable
 *  2. support serialize && unserialize($serialize)
 * @author Cooker <thinklang0917@gmail.com>
 *
 **/

class RuleGroup implements \Serializable
{

    /**
     *
     * @var string $mark RuleGroup Mark 
     *
     **/

    private $mark      = '';


    /**
     *
     * @var string $connector 
     *
     **/ 

    private $connector = 'AND';

    /**
     * 
     * @var array $rules 
     *
     *  array(
     *      'key' => instance of Rule
     *  )
     **/ 

    private $rules     = array();


    /**
     * __construct 
     * 
     * @param  string $mark 
     * @param  array $rules 
     * @param  string $connector 
     * @return void 
     *
     **/ 

    public function __construct($mark = '', array $rules = array(),  $connector = 'AND')
    {
        $this->setMark($mark);
        $this->setRules($rules);
        $this->setConnector($connector);
    }

    /**
     * reset $this 
     *
     * @access public 
     * @param  void 
     * @return void 
     *
     **/ 

    public function reset()
    {
        $this->mark      = '';
        $this->connector = 'AND';
        $this->rules     = array();
    }

    /**
     * set $mark 
     *
     * @access public 
     * @param  string $mark 
     * @return $this 
     *
     **/ 

    public function setMark($mark = '')
    {
        $this->mark = (string)$mark;
        return $this;
    }


    /**
     * get $mark 
     * 
     * @access public 
     * @param  void 
     * @return string 
     *
     **/

    public function getMark(){
        return $this->mark;
    }


    /**
     * set $rules 
     *
     * @access public 
     * @param  mixed $rules 
     * @return $this 
     *
     **/ 

    public function setRules($rules = null)
    {
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

    /**
     * get $rules 
     *
     * @access public 
     * @param  void 
     * @return array 
     *
     **/ 

    public function getRules()
    {
        return array_values($this->rules);
    }

    /**
     * reset $rules 
     *
     * @access public 
     * @param  void 
     * @return void 
     *
     **/ 

    public function resetRules()
    {
        $this->rules = array();
    }


    /**
     * set $connector 
     * 
     * @access public 
     * @param  string $connector 
     * @return $this 
     *
     **/ 

    public function setConnector($connector = '')
    {
        $this->connector = (string)$connector; 
        return $this;
    }

    /**
     * get $connector 
     * 
     * @access public 
     * @param  void 
     * @return string 
     *
     **/

    public function getConnector()
    {
        return (string)$this->connector;
    }

    /**
     * add ruleGroup 
     *
     * @access public 
     * @param  object 
     * @return $this 
     *
     **/ 

    public function addRuleGroup(RuleGroup $ruleGroup)
    {
        if($ruleGroup->getConnector() === $this->getConnector()){
            $this->setRules($ruleGroup->getRules()); 
        }else{
            $this->rules[md5((string)$ruleGroup)] = $ruleGroup; 
        }
        return $this;
    }

    /**
     *
     * {@inheritdoc}
     *
     **/

    public function serialize()
    {
        return serialize(array(
            'rules'     => serialize($this->rules),
            'connector' => $this->connector,
            'mark'      => $this->mark
        )); 
    }

    /**
     *
     * {@inheritdoc}
     *
     **/

    public function unserialize($unserialize)
    {
        $data = unserialize($unserialize); 
        $this->rules     = unserialize($data['rules']);
        $this->connector = $data['connector'];
        $this->mark      = $data['mark'];
    }

    /**
     * __toString 
     *
     * @access public 
     * @param  void 
     * @return string 
     *
     **/

    public function __toString()
    {
        return implode('', $this->rules).strtoupper($this->connector); 
    }

}
