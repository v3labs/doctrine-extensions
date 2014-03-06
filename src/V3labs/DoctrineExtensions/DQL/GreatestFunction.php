<?php

namespace V3labs\DoctrineExtensions\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class GreatestFunction extends FunctionNode
{
    private $values = array();

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->values[] = $parser->ArithmeticExpression();

        $lexer = $parser->getLexer();

        while (count($this->values) < 2 || $lexer->lookahead['type'] != Lexer::T_CLOSE_PARENTHESIS) {
            $parser->match(Lexer::T_COMMA);
            $this->values[] = $parser->ArithmeticExpression();
        }

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker)
    {
        $sql = 'GREATEST(';

        foreach ($this->values as $i => $value) {
            if ($i > 0) {
                $sql .= ', ';
            }

            $sql .= $sqlWalker->walkArithmeticPrimary($value);
        }

        $sql .= ')';

        return $sql;
    }
}