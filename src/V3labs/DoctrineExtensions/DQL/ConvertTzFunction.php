<?php

namespace V3labs\DoctrineExtensions\DQL;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\Parser;

class ConvertTzFunction extends FunctionNode
{
    public $date;

    public $fromTimezone;

    public $toTimezone;

    public function getSql(SqlWalker $sqlWalker)
    {
        return 'CONVERT_TZ(' .
            $sqlWalker->walkArithmeticPrimary($this->date) . ', ' .
            $sqlWalker->walkArithmeticPrimary($this->fromTimezone) . ', ' .
            $sqlWalker->walkArithmeticPrimary($this->toTimezone) .
        ')';
    }

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->date = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->fromTimezone = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->toTimezone = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}