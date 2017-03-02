<?php 
namespace Cooker\Rule;
use       Cooker\Rule\Parser\RuleParserInterface;
use       Cooker\Rule\Parser\SerializeParser;
class RuleParse
{

    private $parser = null;

    public function __construct(RuleParserInterface $parser = null){
        $this->setParser($parser);
    }
    public function setParser(RuleParserInterface $parser = null){
        $this->parser = $parser ? $parser : new SerializeParser(); 
    }

    public function getParser(){
        return $this->parser;
    }

    public function parse($data){
        return $this->parser->parse($data); 
    }


}
