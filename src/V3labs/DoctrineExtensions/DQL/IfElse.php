<?php

namespace V3labs\DoctrineExtensions\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class IfElse extends FunctionNode
{
    private $expressions = [];

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->expressions[] = $parser->ConditionalExpression();

        $parser->match(Lexer::T_COMMA);
        $this->expressions[] = $parser->ArithmeticExpression();

        $parser->match(Lexer::T_COMMA);
        $this->expressions[] = $parser->ArithmeticExpression();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker)
    {
        return sprintf('IF (%s, %s, %s)',
            $sqlWalker->walkConditionalExpression($this->expressions[0]),
            $sqlWalker->walkArithmeticPrimary($this->expressions[1]),
            $sqlWalker->walkArithmeticPrimary($this->expressions[2])
        );
    }
}