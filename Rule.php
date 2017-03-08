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

/**
 *
 * Rule class file 
 *  1. implements interface Serializable
 *  2. support serialize && unserialize($serialize)
 * @author Cooker <thinklang0917@gmail.com>
 *
 **/

class Rule implements \Serializable
{

    /**
     * @var string $table database.tablename 
     **/ 

    private $table    = '';

    /**
     * @var string $field database.tablename.field 
     **/

    private $field    = '';    

    /**
     * @var string $field database.tablename.field 
     **/

    private $key      = '';

    /**
     * @var string $operator eq,neq,in,not in... 
     **/

    private $operator = '';

    /**
     * @var mixed $value 
     **/

    private $value    = null;

    /**
     * __construct 
     *
     * @param  string $field 
     * @param  string $operator 
     * @param  mixed  $value 
     * @param  string $table 
     * @return void 
     *
     **/

    public function __construct($key = '', $operator = '', $value = null, $table = '', $field = '')
    {
        $this->setKey($key);
        $this->setField($field);
        $this->setOperator($operator);
        $this->setValue($value);
        $this->setTable($table);
    }

    public function setKey($key){
        $this->key = (string)$key;
        return $this;
    }

    public function getKey(){
        return trim($this->key, ',');
    }

    /**
     * set table
     * 
     * @access public 
     * @param  string $table 
     * @return $this 
     *
     **/

    public function setTable($table = '')
    {
        $this->table = (string)$table;
        return $this;
    }

    /**
     * get table value
     *
     * @access public 
     * @param  void 
     * @return string  
     *
     **/ 

    public function getTable()
    {
        return $this->table;
    }

    /**
     * set field 
     * 
     * @access public 
     * @param  string $field
     * @return $this 
     *
     **/

    public function setField($field = '')
    {
        $this->field = (string)$field;
        return $this;
    }  

    /**
     * get field 
     *
     * @access public 
     * @param  void 
     * @return string  
     *
     **/ 

    public function getField()
    {
        return $this->field;
    }

    /**
     * set operator 
     * 
     * @access public 
     * @param  string $operator
     * @return $this 
     *
     **/

    public function setOperator($operator = '')
    {
        $this->operator = $operator;
        return $this;
    }

    /**
     * get operator 
     *
     * @access public 
     * @param  void 
     * @return string  
     *
     **/ 

    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * set value 
     * 
     * @access public 
     * @param  mixed $value
     * @return $this 
     *
     **/

    public function setValue($value = null)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * get value
     *
     * @access public 
     * @param  void 
     * @return mixed 
     *
     **/ 

    public function getValue()
    {
        return $this->value;
    }


    /**
     *
     * {@inheritdoc}
     *
     **/

    public function serialize()
    {
        return serialize(array(
            'key'      => $this->key,
            'field'    => $this->field,
            'operator' => $this->operator,
            'value'    => $this->value,    
            'table'    => $this->table,
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
        $this->field    = (string)$data['field'];
        $this->operator = $data['operator'];
        $this->value    = $data['value'];
        $this->key      = $data['key'];
        $this->table    = $data['table'];
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
        $value = is_array($this->value) ? implode(',', $this->value) : $this->value;
        return "{$this->table}.{$this->field}.{$this->key}.{$this->operator}.{$value}"; 
    }


}
