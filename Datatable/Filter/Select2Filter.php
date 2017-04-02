<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Filter;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Exception;

/**
 * Class Select2Filter
 *
 * @package Sg\DatatablesBundle\Datatable\Filter
 */
class Select2Filter extends SelectFilter
{
    /**
     * Select2 supports displaying a placeholder by default using the placeholder option.
     * Default: null
     *
     * @var string|null
     */
    protected $placeholder;

    /**
     * Setting this option to true will enable an "x" icon that will reset the selection to the placeholder.
     * Default: null
     *
     * @var bool|null
     */
    protected $allowClear;

    /**
     * Tagging support.
     * Default: null
     *
     * @var bool|null
     */
    protected $tags;

    /**
     * i18n language code.
     * Default: null (get locale)
     *
     * @var string|null
     */
    protected $language;

    /**
     * URL to get the results from.
     * Default: null
     *
     * @var string|null
     */
    protected $url;

    /**
     * Wait some milliseconds before triggering the request.
     * Default: 250
     *
     * @var int
     */
    protected $delay;

    /**
     * The AJAX cache.
     * Default: true
     *
     * @var bool
     */
    protected $cache;

    //-------------------------------------------------
    // FilterInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return 'SgDatatablesBundle:filter:select2.html.twig';
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

        $resolver->setDefaults(array(
            'placeholder' => null,
            'allow_clear' => null,
            'tags' => null,
            'language' => null,
            'url' => null,
            'delay' => 250,
            'cache' => true,
        ));

        $resolver->setAllowedTypes('placeholder', array('string', 'null'));
        $resolver->setAllowedTypes('allow_clear', array('bool', 'null'));
        $resolver->setAllowedTypes('tags', array('bool', 'null'));
        $resolver->setAllowedTypes('language', array('string', 'null'));
        $resolver->setAllowedTypes('url', array('string', 'null'));
        $resolver->setAllowedTypes('delay', 'int');
        $resolver->setAllowedTypes('cache', 'bool');

        $resolver->setNormalizer('allow_clear', function (Options $options, $value) {
            if (null === $options['placeholder'] && true === $value) {
                throw new Exception('Select2Filter::configureOptions(): The allow_clear option will only work if a placeholder is set.');
            }

            return $value;
        });

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get placeholder.
     *
     * @return null|string
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * Set placeholder.
     *
     * @param null|string $placeholder
     *
     * @return $this
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * Get allowClear.
     *
     * @return bool|null
     */
    public function getAllowClear()
    {
        return $this->allowClear;
    }

    /**
     * Set allowClear.
     *
     * @param bool|null $allowClear
     *
     * @return $this
     */
    public function setAllowClear($allowClear)
    {
        $this->allowClear = $allowClear;

        return $this;
    }

    /**
     * Get tags.
     *
     * @return bool|null
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set tags.
     *
     * @param bool|null $tags
     *
     * @return $this
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get language.
     *
     * @return null|string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set language.
     *
     * @param null|string $language
     *
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get url.
     *
     * @return null|string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set url.
     *
     * @param null|string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get delay.
     *
     * @return int
     */
    public function getDelay()
    {
        return $this->delay;
    }

    /**
     * Set delay.
     *
     * @param int $delay
     *
     * @return $this
     */
    public function setDelay($delay)
    {
        $this->delay = $delay;

        return $this;
    }

    /**
     * Get cache.
     *
     * @return bool
     */
    public function isCache()
    {
        return $this->cache;
    }

    /**
     * Set cache.
     *
     * @param bool $cache
     *
     * @return $this
     */
    public function setCache($cache)
    {
        $this->cache = $cache;

        return $this;
    }
}
