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
 * interface 
 *
 * @author Cooker <thinklang0917@gmail.com> 
 *
 **/ 

interface QueryGeneratorInterface{

    /**
     * generate query string 
     *
     * @access public 
     * @param  mixed $rule instance of Rule,RuleGroup
     * @param  array $values 
     *
     **/ 

    public function generate($rule, $values = array());
}
