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

use Sg\DatatablesBundle\Datatable\Filter\TextFilter;
use Sg\DatatablesBundle\Datatable\Helper;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Exception;

/**
 * Class ImageColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class ImageColumn extends AbstractColumn
{
    /**
     * The Column is filterable.
     */
    use FilterableTrait;

    /**
     * The imagine filter used to display image preview.
     * Required option.
     *
     * @link https://github.com/liip/LiipImagineBundle#create-thumbnails
     *
     * @var string
     */
    protected $imagineFilter;

    /**
     * The imagine filter used to display the enlarged image's size;
     * if not set or null, no filter will be applied;
     * $enlarged need to be set to true.
     * Default: null
     *
     * @link https://github.com/liip/LiipImagineBundle#create-thumbnails
     *
     * @var null|string
     */
    protected $imagineFilterEnlarged;

    /**
     * The relative path.
     * Required option.
     *
     * @var string
     */
    protected $relativePath;

    /**
     * The placeholder url.
     * e.g. "http://placehold.it"
     * Default: null
     *
     * @var null|string
     */
    protected $holderUrl;

    /**
     * The default width of the placeholder.
     * Default: '50'
     *
     * @var string
     */
    protected $holderWidth;

    /**
     * The default height of the placeholder.
     * Default: '50'
     *
     * @var string
     */
    protected $holderHeight;

    /**
     * Enlarge thumbnail.
     * Default: false
     *
     * @var bool
     */
    protected $enlarge;

    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function renderSingleField(array &$row)
    {
        $path = Helper::getDataPropertyPath($this->data);

        $content = $this->renderImageTemplate($this->accessor->getValue($row, $path), '-image');

        $this->accessor->setValue($row, $path, $content);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function renderToMany(array &$row)
    {
        // e.g. images[ ].fileName
        //     => $path = [images]
        //     => $value = [fileName]
        $value = null;
        $path = Helper::getDataPropertyPath($this->data, $value);

        $images = $this->accessor->getValue($row, $path);

        if (count($images) > 0) {
            foreach ($images as $key => $image) {
                $currentPath = $path.'['.$key.']'.$value;
                $content = $this->renderImageTemplate($this->accessor->getValue($row, $currentPath), '-gallery-image');
                $this->accessor->setValue($row, $currentPath, $content);
            }
        } else {
            // create an entry for the placeholder image
            $currentPath = $path.'[0]'.$value;
            $content = $this->renderImageTemplate(null, '-gallery-image');
            $this->accessor->setValue($row, $currentPath, $content);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCellContentTemplate()
    {
        return 'SgDatatablesBundle:render:thumb.html.twig';
    }

    //-------------------------------------------------
    // Options
    //-------------------------------------------------

    /**
     * Config options.
     *
     * @param OptionsResolver $resolver
     *
     * @return $this
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(array('imagine_filter'));
        $resolver->setRequired(array('relative_path'));

        $resolver->setDefaults(array(
            'filter' => array(TextFilter::class, array()),
            'imagine_filter_enlarged' => null,
            'holder_url' => null,
            'holder_width' => '50',
            'holder_height' => '50',
            'enlarge' => false,
        ));

        $resolver->setAllowedTypes('filter', 'array');
        $resolver->setAllowedTypes('imagine_filter', 'string');
        $resolver->setAllowedTypes('imagine_filter_enlarged', array('null', 'string'));
        $resolver->setAllowedTypes('relative_path', 'string');
        $resolver->setAllowedTypes('holder_url', array('null', 'string'));
        $resolver->setAllowedTypes('holder_width', 'string');
        $resolver->setAllowedTypes('holder_height', 'string');
        $resolver->setAllowedTypes('enlarge', 'bool');

        $resolver->setNormalizer('enlarge', function (Options $options, $value) {
            if (null === $options['imagine_filter_enlarged'] && true === $value) {
                throw new Exception('ImageColumn::configureOptions(): For the enlarge option, imagine_filter_enlarged should not be null.');
            }

            return $value;
        });

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

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
     * Get imagineFilterEnlarged.
     *
     * @return null|string
     */
    public function getImagineFilterEnlarged()
    {
        return $this->imagineFilterEnlarged;
    }

    /**
     * Set imagineFilterEnlarged.
     *
     * @param null|string $imagineFilterEnlarged
     *
     * @return $this
     */
    public function setImagineFilterEnlarged($imagineFilterEnlarged)
    {
        $this->imagineFilterEnlarged = $imagineFilterEnlarged;

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
     * Set relativePath.
     *
     * @param string $relativePath
     *
     * @return $this
     */
    public function setRelativePath($relativePath)
    {
        $this->relativePath = $relativePath;

        return $this;
    }

    /**
     * Get holderUrl.
     *
     * @return string|null
     */
    public function getHolderUrl()
    {
        return $this->holderUrl;
    }

    /**
     * Set holderUrl.
     *
     * @param string|null $holderUrl
     *
     * @return $this
     */
    public function setHolderUrl($holderUrl)
    {
        $this->holderUrl = $holderUrl;

        return $this;
    }

    /**
     * Get holderWidth.
     *
     * @return string
     */
    public function getHolderWidth()
    {
        return $this->holderWidth;
    }

    /**
     * Set holderWidth.
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
     * Get holderHeight.
     *
     * @return string
     */
    public function getHolderHeight()
    {
        return $this->holderHeight;
    }

    /**
     * Set holderHeight.
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
     * Get enlarge.
     *
     * @return bool
     */
    public function isEnlarge()
    {
        return $this->enlarge;
    }

    /**
     * Set enlarge.
     *
     * @param bool $enlarge
     *
     * @return $this
     */
    public function setEnlarge($enlarge)
    {
        $this->enlarge = $enlarge;

        return $this;
    }

    //-------------------------------------------------
    // Helper
    //-------------------------------------------------

    /**
     * Render image template.
     *
     * @param string $data
     * @param string $classSuffix
     *
     * @return mixed|string
     */
    private function renderImageTemplate($data, $classSuffix)
    {
        return $this->twig->render(
            $this->getCellContentTemplate(),
            array(
                'data' => $data,
                'image' => $this,
                'image_class' => 'sg-datatables-'.$this->getDatatableName().$classSuffix,
            )
        );
    }
}
