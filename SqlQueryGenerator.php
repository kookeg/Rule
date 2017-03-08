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
use       Cooker\Rule\QueryGeneratorInterface;
use       Cooker\Rule\Rule;
use       Cooker\Rule\RuleGroup;

/**
 * Sqlquerygenerator generate sql base on rule 
 *
 * @author Cooker <thinklang0917@gmail.com>
 * 
 **/

class SqlQueryGenerator implements QueryGeneratorInterface
{


    private $fields = '';
    private $table  = '';

    /**
     *
     * {@inheritdoc}
     *
     **/

    public function generate($rule, $data = array())
    {
        $sql = '';
        if($rule instanceof Rule){
            $sql .= $this->parseRule($rule, $data); 
        }elseif($rule instanceof RuleGroup){
            $sql .= $this->parseRuleGroup($rule, $data); 
        } 
        if($this->table && $this->fields){
            $sql = "SELECT {$this->fields} FROM {$this->table} WHERE {$sql}";
        }
        return $sql;
    }


    /**
     *
     * parse Rule 
     *
     * @access private 
     * @param  object instanceof Rule 
     * @param  array  $value 
     * @return string 
     *
     **/ 

    private function parseRule(Rule $rule, $value = array())
    {
        $query    = '';
        $value    = $value ? $value : $rule->getValue();
        $operator = $rule->getOperator();
        $key      = $rule->getField();
        $table    = $rule->getTable();
        $this->table  = $table ? $table : $this->table;
        $this->fields = $rule->getField() ? $rule->getField() : $this->fields;
        if($this->filterValue($operator, $value)){
            $query = $this->generateQuery($operator, $value, $key, $table); 
        }
        return $query;
    }

    /**
     *
     * parse RuleGroup 
     *
     * @access private 
     * @param  object instanceof RuleGroup 
     * @param  array  $value 
     * @return string 
     *
     **/ 

    private function parseRuleGroup(RuleGroup $ruleGroup, $value = array())
    {
        $sql = '(';
        foreach($ruleGroup->getRules() as $key => $obj){
            if($obj instanceof Rule){
                $tmpQuery = $this->parseRule($obj, $value);
                if($tmpQuery){
                    $sql .=  $tmpQuery . " {$ruleGroup->getConnector()} "; 
                }
            }elseif($obj instanceof RuleGroup){
                $sql = trim($sql, $ruleGroup->getConnector() . " ") . ") {$obj->getConnector()} (";
                $sql .= $this->parseRuleGroup($obj);
            } 
        }
        $sql = trim($sql, $ruleGroup->getConnector() . " ") . ') ';
        return $sql;
    }


    /**
     *
     * validate value type  base self::$operatorRules
     *
     * @access private 
     * @param  string $operator 
     * @param  string $value 
     * @return boolean
     **/ 

    private function filterValue($operator, $value)
    {
        return in_array(gettype($value), self::$operatorRules[$operator]['value']);
    }

    /**
     * generate query string 
     *
     * @access private 
     * @param  string $operator 
     * @param  mixed  $value 
     * @param  string $key 
     * @param  string $table 
     * @return string 
     *
     **/ 

    private function generateQuery($operator, $value, $key, $table = '')
    {
        $operatorRule = self::$operatorRules[$operator]; 
        //check if PHP code, and eval PHP code
        $value = is_string($value) && (strpos($value, '@') === 0) ? eval("return " . substr($value, 1) . ';') : $value;
        if(isset($operatorRule['ext']['function'])){
            array_walk($value, function(&$item, $k){
                $item = is_string($item) ? "'{$item}'" : $item;
            });
            //notice "\$value" diff between ""  with ''。
            $value = eval("return ".str_replace('{value}', "\$value", $operatorRule['ext']['function']) . ';');
        }else{
            if(is_string($value)){
                $value = "'{$value}'"; 
            }
        }
        $table = $table ? $table . '.' : '';
        return str_replace($operatorRule['build']['replace'], array($table, $key, $value), $operatorRule['build']['sql']);
    }

    /**
     * parse rules 
     *
     * @var array 
     *
     **/ 

    static private $operatorRules = array(
        'eq'  => array(
            'value' => array(
                'integer',
                'string' 
            ),
            'build' => array(
                'replace' => array(
                    '{table}.',
                    '{field}',
                    '{value}', 
                ), 
                'sql' => '{table}.`{field}` = {value}',
            ),
        ), 
        'neq' => array(
            'value' => array(
                'integer',
                'string' 
            ),
            'build' => array(
                'replace' => array(
                    '{table}.',
                    '{field}',
                    '{value}', 
                ), 
                'sql' => '{table}.`{field}` <> {value}',
            ),
        ), 
        'gt'  => array(
            'value' => array(
                'integer',
                'string' 
            ),
            'build' => array(
                'replace' => array(
                    '{table}.',
                    '{field}',
                    '{value}', 
                ), 
                'sql' => '{table}.`{field}` >  {value}',
            ),
        ), 
        'egt' => array(
            'value' => array(
                'integer',
                'string' 
            ),
            'build' => array(
                'replace' => array(
                    '{table}.',
                    '{field}',
                    '{value}', 
                ), 
                'sql' => '{table}.`{field}` >= {value}',
            ),
        ), 
        'lt'  => array(
            'value' => array(
                'integer',
                'string' 
            ),
            'build' => array(
                'replace' => array(
                    '{table}.',
                    '{field}',
                    '{value}', 
                ), 
                'sql' => '{table}.`{field}` < {value}',
            ),
        ), 
        'elt' => array(
            'value' => array(
                'integer',
                'string', 
            ),
            'build' => array(
                'replace' => array(
                    '{table}.',
                    '{field}',
                    '{value}', 
                ), 
                'sql' => '{table}.`{field}` <= {value}',
            ),
        ), 
        'in'  => array(
            'value' => array(
                'array',
            ),
            'build' => array(
                'replace' => array(
                    '{table}.',
                    '{field}',
                    '{value}', 
                ), 
                'sql' => '{table}.`{field}` in ({value})',
            ),
            'ext'   => array(
                'function' => "implode(',', {value})", 
            ),
        ),
        'not in' => array(
            'value' => array(
                'array',
            ),
            'build' => array(
                'replace' => array(
                    '{table}.',
                    '{field}',
                    '{value}', 
                ), 
                'sql' => '{table}.`{field}` not in ({value})',
            ),
            'ext'   => array(
                'function' => "implode(',', {value})", 
            ),
        ),
    );
}
