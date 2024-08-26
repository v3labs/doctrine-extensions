<?php

namespace V3labs\DoctrineExtensions\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class ConcatWs extends FunctionNode
{
    private $values = [];

    private $notEmpty = false;

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->values[] = $parser->ArithmeticExpression();

        $lexer = $parser->getLexer();

        while (count($this->values) < 3 || $lexer->lookahead->type == Lexer::T_COMMA) {
            $parser->match(Lexer::T_COMMA);
            $peek = $lexer->glimpse();

            $this->values[] = $peek->value == '(' ? $parser->FunctionDeclaration() : $parser->ArithmeticExpression();
        }

        while ($lexer->lookahead->type == Lexer::T_IDENTIFIER) {
            switch (strtolower($lexer->lookahead->value)) {
                case 'notempty':
                    $parser->match(Lexer::T_IDENTIFIER);
                    $this->notEmpty = true;
                    break;
                default:
                    $parser->match(Lexer::T_CLOSE_PARENTHESIS);
                    break;
            }
        }

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker)
    {
        $args = array_map(
            function($value) use ($sqlWalker) {
                $nodeSql = $sqlWalker->walkArithmeticPrimary($value);
                return $this->notEmpty ? sprintf("NULLIF(%s, '')", $nodeSql) : $nodeSql;
            },
            $this->values
        );

        return 'CONCAT_WS(' . implode(', ', $args) . ')';
    }
}