<?php 
namespace Cooker\Rule;
use       Cooker\Rule\Rule;

class RuleCollection implements \IteratorAggregate, \Countable
{

    private $rules    = array();  

    private $connector = '';

    public function __clone(){
        foreach($this->rules as $name => $rule){
            $this->rules[$name] = clone $rule;
        }
    }



    public function getIterator()
    {
        return new \ArrayIterator($this->rules);
    }

    public function count(){
        return count($this->rules);
    }

    public function get($name)
    {
        return array_key_exists($name, $this->rules) ? $this->rules[$name] : null;
    }

    public function remove($name){
        foreach((array)$name as $n){
            unset($this->rules[$n]);
        }
    } 

    public function add($name, Rule $rule){
        unset($this->rules[$name]); 
        $this->rules[$name] = $rule;
    }

    public function addCollection(RuleCollection $collection){
        foreach($collection->all() as $name => $rule){
            unset($this->rules[$name]);     
            $this->rules[$name] = $rule;
        }
    }

    public function setConnector($connector = 'AND'){
        $this->connector = $connector;
    }
}
