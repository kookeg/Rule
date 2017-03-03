<?php 
namespace Cooker\Rule;

interface QueryGeneratorInterface{
    public function generate($rules, $values = array());
}
