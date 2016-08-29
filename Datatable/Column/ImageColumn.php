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
use Twig_Environment;

/**
 * Class ImageColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class ImageColumn extends AbstractColumn
{
    /**
     * The imagine filter used to display image preview.
     *
     * @link https://github.com/liip/LiipImagineBundle#basic-usage
     *
     * @var string
     */
    protected $imagineFilter;

    /**
     * The imagine filter used to display the enlarged image's size;
     * if not set or null, no filter will be applied;
     * $enlarged need to be set to true.
     *
     * @link https://github.com/liip/LiipImagineBundle#basic-usage
     *
     * @var string
     */
    protected $imagineFilterEnlarged;

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
    public function renderContent(&$row, DatatableQuery $datatableQuery = null)
    {
        if (true === $datatableQuery->getImagineBundle()) {
            if ($this->isAssociation()) {
                $fields = explode('.', $this->getDql());
                $field = $fields[0];

                if (array_key_exists($field, $row) && null === $row[$field]) {
                    $row[$field] = $this->renderImage(null, $datatableQuery->getTwig());
                } else {
                    $this->getAccessor()->setValue($row, $this->getDqlProperty(), $this->renderImage($this->getAccessor()->getValue($row, $this->getDqlProperty()), $datatableQuery->getTwig()));
                }
            } else {
                $row[$this->getDql()] = $this->renderImage($row[$this->getDql()], $datatableQuery->getTwig());
            }
        } else {
            if ($this->isAssociation()) {
                $fields = explode('.', $this->getDql());
                $field = $fields[0];

                if (array_key_exists($field, $row) && null === $row[$field]) {
                    $row[$field] = $datatableQuery->getTwig()->render(
                        'SgDatatablesBundle:Helper:render_image.html.twig',
                        array(
                            'image_name' => null,
                            'path' => $this->getRelativePath(),
                        )
                    );
                } else {
                    $render = $datatableQuery->getTwig()->render(
                        'SgDatatablesBundle:Helper:render_image.html.twig',
                        array(
                            'image_name' => $this->getAccessor()->getValue($row, $this->getDqlProperty()),
                            'path' => $this->getRelativePath(),
                        )
                    );
                    $this->getAccessor()->setValue($row, $this->getDqlProperty(), $render);
                }
            } else {
                $this->getAccessor()->setValue(
                    $row,
                    $this->getDqlProperty(),
                    $datatableQuery->getTwig()->render(
                        'SgDatatablesBundle:Helper:render_image.html.twig',
                        array(
                            'image_name' => $this->getAccessor()->getValue($row, $this->getDqlProperty()),
                            'path' => $this->getRelativePath(),
                        )
                    )
                );
            }
        }
    }

    /**
     * Render image.
     *
     * @param string|null      $imageName
     * @param Twig_Environment $twig
     *
     * @return string
     */
    protected function renderImage($imageName, Twig_Environment $twig)
    {
        return $twig->render(
            'SgDatatablesBundle:Helper:ii_render_image.html.twig',
            array(
                'image_name' => $imageName,
                'filter' => $this->getImagineFilter(),
                'enlarged_filter' => $this->getImagineFilterEnlarged(),
                'path' => $this->getRelativePath(),
                'holder_url' => $this->getHolderUrl(),
                'width' => $this->getHolderWidth(),
                'height' => $this->getHolderHeight(),
                'enlarge' => $this->getEnlarge()
            )
        );
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
        parent::configureOptions($resolver);

        $resolver->remove('default_content');
        $resolver->remove('render');

        $resolver->setRequired(array('relative_path'));

        $resolver->setDefaults(array(
            'orderable' => false,
            'searchable' => false,
            'filter' => array('text', array()),
            'imagine_filter' => '',
            'imagine_filter_enlarged' => null,
            'holder_url' => '',
            'holder_width' => '50',
            'holder_height' => '50',
            'enlarge' => false
        ));

        $resolver->setAllowedTypes('filter', 'array');
        $resolver->setAllowedTypes('imagine_filter', 'string');
        $resolver->setAllowedTypes('imagine_filter_enlarged', array('string', 'null'));
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
     * Set imagineFilterEnlarged.
     *
     * @param string $imagineFilterEnlarged
     *
     * @return $this
     */
    public function setImagineFilterEnlarged($imagineFilterEnlarged)
    {
        $this->imagineFilterEnlarged = $imagineFilterEnlarged;

        return $this;
    }

    /**
     * Get imagineFilterEnlarged.
     *
     * @return string
     */
    public function getImagineFilterEnlarged()
    {
        return $this->imagineFilterEnlarged;
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
