<?php 
namespace Cooker\Rule;
use       Cooker\Rule\QueryGeneratorInterface;
use       Cooker\Rule\Rule;
use       Cooker\Rule\RuleGroup;

class SqlQueryGenerator implements QueryGeneratorInterface
{

    public function generate($rule, $data = array())
    {
        $sql = '';
        if($rule instanceof Rule){
            $sql .= $this->parseRule($rule, $data); 
        }elseif($rule instanceof RuleGroup){
            $sql .= $this->parseRuleGroup($rule, $data); 
        } 
        return $sql;
    }

    private function parseRule(Rule $rule, $value = array())
    {
        $query    = '';
        $value    = $value ? $value : $rule->getValue();
        $operator = $rule->getOperator();
        $field    = $rule->getField();
        $table    = $rule->getTable();
        if($this->filterValue($operator, $value)){
            $query = $this->generateQuery($operator, $value, $field, $table); 
        }
        return $query;
    }

    private function parseRuleGroup(RuleGroup $ruleGroup, $value = array())
    {
        $sql = '(';
        foreach($ruleGroup->getRules() as $key => $obj){
            if($obj instanceof Rule){
                $sql .= $this->parseRule($obj, $value) . " {$ruleGroup->getConnector()} "; 
            }elseif($obj instanceof RuleGroup){
                $sql = trim($sql, $ruleGroup->getConnector() . " ") . ") {$obj->getConnector()} (";
                $sql .= $this->parseRuleGroup($obj);
            } 
        }
        $sql = trim($sql, $ruleGroup->getConnector() . " ") . ') ';
        return $sql;
    }


    private function filterValue($operator, $value){
        return in_array(gettype($value), self::$operatorRules[$operator]['value']);
    }

    private function generateQuery($operator, $value, $field, $table = ''){
        $operatorRule = self::$operatorRules[$operator]; 
        //如果是可执行的PHP代码，则执行PHP代码取得相应的value值
        $value = is_string($value) && (strpos($value, '@') === 0) ? eval("return " . substr($value, 1) . ';') : $value;
        if(isset($operatorRule['ext']['function'])){
            array_walk($value, function(&$item, $key){
                $item = is_string($item) ? "'{$item}'" : $item;
            });
            //注意 "\$value" 如果是单引号的话就不可以。
            $value = eval("return ".str_replace('{value}', "\$value", $operatorRule['ext']['function']) . ';');
        }else{
            if(is_string($value)){
                $value = "'{$value}'"; 
            }
        }
        $table = $table ? $table . '.' : '';
        return str_replace($operatorRule['build']['replace'], array($table, $field, $value), $operatorRule['build']['sql']);
    }

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
