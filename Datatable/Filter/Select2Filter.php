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

use Exception;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Select2Filter extends SelectFilter
{
    /**
     * Select2 supports displaying a placeholder by default using the placeholder option.
     * Default: null.
     *
     * @var string|null
     */
    protected $placeholder;

    /**
     * Setting this option to true will enable an "x" icon that will reset the selection to the placeholder.
     * Default: null.
     *
     * @var bool|null
     */
    protected $allowClear;

    /**
     * Tagging support.
     * Default: null.
     *
     * @var bool|null
     */
    protected $tags;

    /**
     * i18n language code.
     * Default: null (get locale).
     *
     * @var string|null
     */
    protected $language;

    /**
     * URL to get the results from.
     * Default: null.
     *
     * @var string|null
     */
    protected $url;

    /**
     * Wait some milliseconds before triggering the request.
     * Default: 250.
     *
     * @var int
     */
    protected $delay;

    /**
     * The AJAX cache.
     * Default: true.
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
        return '@SgDatatables/filter/select2.html.twig';
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

        $resolver->setDefaults([
            'placeholder' => null,
            'allow_clear' => null,
            'tags' => null,
            'language' => null,
            'url' => null,
            'delay' => 250,
            'cache' => true,
        ]);

        $resolver->setAllowedTypes('placeholder', ['string', 'null']);
        $resolver->setAllowedTypes('allow_clear', ['bool', 'null']);
        $resolver->setAllowedTypes('tags', ['bool', 'null']);
        $resolver->setAllowedTypes('language', ['string', 'null']);
        $resolver->setAllowedTypes('url', ['string', 'null']);
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
     * @return string|null
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * @param string|null $placeholder
     *
     * @return $this
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getAllowClear()
    {
        return $this->allowClear;
    }

    /**
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
     * @return bool|null
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
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
     * @return string|null
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string|null $language
     *
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return int
     */
    public function getDelay()
    {
        return $this->delay;
    }

    /**
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
     * @return bool
     */
    public function isCache()
    {
        return $this->cache;
    }

    /**
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
