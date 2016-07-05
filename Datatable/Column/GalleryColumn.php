<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Column;

use Sg\DatatablesBundle\Datatable\Data\DatatableQuery;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\Exception\InvalidArgumentException;

/**
 * Class GalleryColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class GalleryColumn extends ImageColumn
{
    /**
     * Maximum number of images to be displayed.
     *
     * @var integer
     */
    protected $viewLimit;

    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function setData($data)
    {
        if (empty($data) || !is_string($data)) {
            throw new InvalidArgumentException('setData(): Expecting non-empty string.');
        }

        if (false === strstr($data, '.')) {
            throw new InvalidArgumentException('setData(): An association is expected.');
        }

        $fields = explode('.', $data);
        $this->data = $fields[0];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function renderContent(&$row, DatatableQuery $datatableQuery = null)
    {
        $fields = explode('.', $this->getDql());

        if (true === $datatableQuery->getImagineBundle()) {
            $galleryImages = '';
            $counter = 0;
            $images = count($row[$fields[0]]);
            if (0 === $images) {
                $row[$fields[0]] = $this->renderImage(null, $datatableQuery->getTwig());
            } else {
                foreach ($row[$fields[0]] as $image) {
                    $galleryImages = $galleryImages . $this->renderImage($image[$fields[1]], $datatableQuery->getTwig());
                    if (++$counter == $this->getViewLimit()) break;
                }
                $row[$fields[0]] = $galleryImages;
            }
        } else {
            throw new InvalidArgumentException('renderContent(): Bundle "LiipImagineBundle" does not exist or it is not enabled.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'gallery';
    }

    //-------------------------------------------------
    // OptionsInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(array('imagine_filter'));

        $resolver->setDefault('view_limit', 4);

        $resolver->setAllowedTypes('view_limit', 'integer');

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set view limit.
     *
     * @param integer $viewLimit
     *
     * @return $this
     */
    public function setViewLimit($viewLimit)
    {
        $this->viewLimit = $viewLimit;

        return $this;
    }

    /**
     * Get view limit.
     *
     * @return integer
     */
    public function getViewLimit()
    {
        return $this->viewLimit;
    }
}
