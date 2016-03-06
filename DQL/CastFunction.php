<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * Class CastFunction
 *
 * @package Sg\DatatablesBundle\DQL
 */
class CastFunction extends FunctionNode
{
    public $first = null;

    public $unit = null;

    /**
     * {@inheritdoc}
     */
    public function getSql(SqlWalker $sqlWalker)
    {
        return sprintf('CAST(%s AS %s)',  $this->first->dispatch($sqlWalker), $this->unit);
    }

    /**
     * {@inheritdoc}
     */
    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->first = $parser->StringPrimary();

        $parser->match(Lexer::T_AS);

        $parser->match(Lexer::T_IDENTIFIER);
        $lexer = $parser->getLexer();
        $this->unit = $lexer->token['value'];
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
