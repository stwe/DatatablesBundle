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

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\Exception\InvalidArgumentException;

/**
 * Class ImageColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class ImageColumn extends AbstractColumn
{
    /**
     * The imagine filter.
     *
     * @link https://github.com/liip/LiipImagineBundle#basic-usage
     *
     * @var string
     */
    protected $imagineFilter;

    /**
     * The relative path.
     *
     * @var string
     */
    protected $relativePath;

    /**
     * The placeholder url.
     * e.g. "http://placehold.it"
     *
     * @var boolean
     */
    protected $holderUrl;

    /**
     * The default width of the placeholder.
     *
     * @var string
     */
    protected $holderWidth;

    /**
     * The default height of the placeholder.
     *
     * @var string
     */
    protected $holderHeight;

    /**
     * Enlarge thumbnail.
     *
     * @var boolean
     */
    protected $enlarge;

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

        $this->data = $data;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return 'SgDatatablesBundle:Column:image.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'image';
    }

    //-------------------------------------------------
    // OptionsInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array('relative_path'));

        $resolver->setDefaults(array(
            'class' => '',
            'padding' => '',
            'name' => '',
            'orderable' => false,
            'searchable' => false,
            'title' => '',
            'type' => '',
            'visible' => true,
            'width' => '',
            'filter' => array('text', array()),
            'imagine_filter' => '',
            'holder_url' => '',
            'holder_width' => '50',
            'holder_height' => '50',
            'enlarge' => false
        ));

        $resolver->setAllowedTypes('class', 'string');
        $resolver->setAllowedTypes('padding', 'string');
        $resolver->setAllowedTypes('name', 'string');
        $resolver->setAllowedTypes('orderable', 'bool');
        $resolver->setAllowedTypes('searchable', 'bool');
        $resolver->setAllowedTypes('title', 'string');
        $resolver->setAllowedTypes('type', 'string');
        $resolver->setAllowedTypes('visible', 'bool');
        $resolver->setAllowedTypes('width', 'string');
        $resolver->setAllowedTypes('filter', 'array');
        $resolver->setAllowedTypes('imagine_filter', 'string');
        $resolver->setAllowedTypes('relative_path', 'string');
        $resolver->setAllowedTypes('holder_url', 'string');
        $resolver->setAllowedTypes('holder_width', 'string');
        $resolver->setAllowedTypes('holder_height', 'string');
        $resolver->setAllowedTypes('enlarge', 'bool');

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set imagineFilter.
     *
     * @param string $imagineFilter
     *
     * @return $this
     */
    public function setImagineFilter($imagineFilter)
    {
        $this->imagineFilter = $imagineFilter;

        return $this;
    }

    /**
     * Get imagineFilter.
     *
     * @return string
     */
    public function getImagineFilter()
    {
        return $this->imagineFilter;
    }

    /**
     * Set relativePath.
     *
     * @param string $relativePath
     *
     * @return $this
     */
    public function setRelativePath($relativePath)
    {
        $relativePath = rtrim($relativePath, '/');

        $this->relativePath = $relativePath;

        return $this;
    }

    /**
     * Get relativePath.
     *
     * @return string
     */
    public function getRelativePath()
    {
        return $this->relativePath;
    }

    /**
     * Set holderUrl.
     *
     * @param string $holderUrl
     *
     * @return $this
     */
    public function setHolderUrl($holderUrl)
    {
        $holderUrl = rtrim($holderUrl, '/');

        $this->holderUrl = $holderUrl;

        return $this;
    }

    /**
     * Get holderUrl.
     *
     * @return string
     */
    public function getHolderUrl()
    {
        return $this->holderUrl;
    }

    /**
     * Set default holder width.
     *
     * @param string $holderWidth
     *
     * @return $this
     */
    public function setHolderWidth($holderWidth)
    {
        $this->holderWidth = $holderWidth;

        return $this;
    }

    /**
     * Get default holder width.
     *
     * @return string
     */
    public function getHolderWidth()
    {
        return $this->holderWidth;
    }

    /**
     * Set default holder height.
     *
     * @param string $holderHeight
     *
     * @return $this
     */
    public function setHolderHeight($holderHeight)
    {
        $this->holderHeight = $holderHeight;

        return $this;
    }

    /**
     * Get default holder height.
     *
     * @return string
     */
    public function getHolderHeight()
    {
        return $this->holderHeight;
    }

    /**
     * Set enlarge.
     *
     * @param boolean $enlarge
     *
     * @return $this
     */
    public function setEnlarge($enlarge)
    {
        $this->enlarge = $enlarge;

        return $this;
    }

    /**
     * Get enlarge.
     *
     * @return boolean
     */
    public function getEnlarge()
    {
        return $this->enlarge;
    }
}
