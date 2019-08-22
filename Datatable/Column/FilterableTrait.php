<?php

/*
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Column;

use Exception;
use Sg\DatatablesBundle\Datatable\Factory;
use Sg\DatatablesBundle\Datatable\Filter\FilterInterface;

trait FilterableTrait
{
    /**
     * A FilterInterface instance for individual filtering.
     * Default: See the column type.
     *
     * @var FilterInterface
     */
    protected $filter;

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get Filter instance.
     *
     * @return FilterInterface
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * Set Filter instance.
     *
     * @throws Exception
     *
     * @return $this
     */
    public function setFilter(array $filterClassAndOptions)
    {
        if (2 !== \count($filterClassAndOptions)) {
            throw new Exception('AbstractColumn::setFilter(): Two arguments expected.');
        }

        if (! isset($filterClassAndOptions[0]) || ! \is_string($filterClassAndOptions[0]) && ! $filterClassAndOptions[0] instanceof FilterInterface) {
            throw new Exception('AbstractColumn::setFilter(): Set a Filter class.');
        }

        if (! isset($filterClassAndOptions[1]) || ! \is_array($filterClassAndOptions[1])) {
            throw new Exception('AbstractColumn::setFilter(): Set an options array.');
        }

        $newFilter = Factory::create($filterClassAndOptions[0], FilterInterface::class);
        $this->filter = $newFilter->set($filterClassAndOptions[1]);

        return $this;
    }
}
