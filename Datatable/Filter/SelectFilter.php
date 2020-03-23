<?php

/*
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Filter;

use Doctrine\ORM\Query\Expr\Andx;
use Doctrine\ORM\QueryBuilder;
use Exception;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SelectFilter extends AbstractFilter
{
    /**
     * This allows to define a search type (e.g. 'like' or 'isNull') for each item in 'selectOptions'.
     * Default: array() - The default value of searchType is used.
     *
     * @var array
     */
    protected $selectSearchTypes;

    /**
     * Select options for this filter type (e.g. for boolean column: '1' => 'Yes', '0' => 'No').
     * Default: array().
     *
     * @var array
     */
    protected $selectOptions;

    /**
     * Lets the user select more than one option in the select list.
     * Default: false.
     *
     * @var bool
     */
    protected $multiple;

    //-------------------------------------------------
    // FilterInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return '@SgDatatables/filter/select.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function addAndExpression(Andx $andExpr, QueryBuilder $qb, $searchField, $searchValue, $searchTypeOfField, &$parameterCounter)
    {
        $searchValues = explode(',', $searchValue);
        if (true === $this->multiple && \is_array($searchValues) && \count($searchValues) > 1) {
            $orExpr = $qb->expr()->orX();

            foreach ($searchValues as $searchValue) {
                $this->setSelectSearchType($searchValue);
                $orExpr->add($this->getExpression($qb->expr()->andX(), $qb, $this->searchType, $searchField, $searchValue, $searchTypeOfField, $parameterCounter));
            }

            return $andExpr->add($orExpr);
        }

        $this->setSelectSearchType($searchValue);

        return $this->getExpression($andExpr, $qb, $this->searchType, $searchField, $searchValue, $searchTypeOfField, $parameterCounter);
    }

    //-------------------------------------------------
    // Options
    //-------------------------------------------------

    /**
     * @return $this
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        // placeholder is not a valid attribute on a <select> input
        $resolver->remove('placeholder');
        $resolver->remove('placeholder_text');

        $resolver->setDefaults([
            'select_search_types' => [],
            'select_options' => [],
            'multiple' => false,
        ]);

        $resolver->setAllowedTypes('select_search_types', 'array');
        $resolver->setAllowedTypes('select_options', 'array');
        $resolver->setAllowedTypes('multiple', 'bool');

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * @return array
     */
    public function getSelectSearchTypes()
    {
        return $this->selectSearchTypes;
    }

    /**
     * @return $this
     */
    public function setSelectSearchTypes(array $selectSearchTypes)
    {
        $this->selectSearchTypes = $selectSearchTypes;

        return $this;
    }

    /**
     * @return array
     */
    public function getSelectOptions()
    {
        return $this->selectOptions;
    }

    /**
     * @return $this
     */
    public function setSelectOptions(array $selectOptions)
    {
        $this->selectOptions = $selectOptions;

        return $this;
    }

    /**
     * @return bool
     */
    public function isMultiple()
    {
        return $this->multiple;
    }

    /**
     * @param bool $multiple
     *
     * @return $this
     */
    public function setMultiple($multiple)
    {
        $this->multiple = $multiple;

        return $this;
    }

    //-------------------------------------------------
    // Private
    //-------------------------------------------------

    /**
     * @throws Exception
     */
    private function setSelectSearchType(string $searchValue): void
    {
        $searchTypesCount = \count($this->selectSearchTypes);

        if ($searchTypesCount > 0) {
            if ($searchTypesCount === \count($this->selectOptions)) {
                $this->searchType = $this->selectSearchTypes[$searchValue];
            } else {
                throw new Exception('SelectFilter::setSelectSearchType(): The search types array is not valid.');
            }
        }
    }
}
