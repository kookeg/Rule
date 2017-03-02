<?php 
namespace Cooker\Rule;

class Rule implements \Serializable
{

    private $table    = '';

    private $field    = '';    

    private $operator = '';

    private $value    = null;

    public function __construct($field, $operator, $value, $table = '')
    {
        $this->setField($field);
        $this->setOperator($operator);
        $this->setValue($value);
        $this->setTable($table);
    }


    public function setTable($table){
        $this->table = (string)$table;
        return $this;
    }

    public function getTable(){
        return $this->table;
    }

    public function setField($field){
        $this->field = (string)$field;
        return $this;
    }  

    public function getField(){
        return $this->field;
    }

    public function setOperator($operator){
        $this->operator = $operator;
        return $this;
    }

    public function getOperator(){
        return $this;
    }

    public function setValue($value){
        $this->value = $value;
        return $this;
    }

    public function getValue(){
        return $this->value;
    }


    public function serialize(){
        return serialize(array(
            'field'    => $this->field,
            'operator' => $this->operator,
            'value'    => $this->value,    
        ));
    }

    public function unserialize($unserialize){
        $data = unserialize($unserialize); 
        $this->field    = (string)$data['field'];
        $this->operator = $data['operator'];
        $this->value    = $data['value'];
    }

}
