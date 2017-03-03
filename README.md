# rule
```
use Cooker\Rule\Rule;
use Cooker\Rule\RuleGroup;
$rule1 = new Rule();
$rule1
    ->setTable('tablename')
    ->setField('field1')
    ->setOperator('eq')
    ->setValue("value1");

$rule2 = clone $rule1;
$rule2
    ->setTable('tablename')
    ->setField('field2')
    ->setOperator('in')
    ->setValue(array('value1', 'value2'));
    
$ruleGroup = new RuleGroup('Rule1OrRule2',  array($rule1, $rule2), 'OR');
$sqlQueryGenerator = new Cooker\Rule\SqlQueryGenerator();
$sql = $sqlQueryGenerator->generate($ruleGroup);

(tablename.`field1` = 'value1' OR tablename.`field2` in ('value1','value2'))
```
