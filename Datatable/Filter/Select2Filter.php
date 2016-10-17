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

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Andx;
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
     * The select is declared with the multiple attribute.
     *
     * @var boolean
     */
    protected $multiple;

    /**
     * A placeholder value can be defined and will be displayed until a selection is made.
     * Requires IE 10+
     *
     * @var string|null
     */
    protected $placeholder;

    /**
     * Setting this option to true will enable an "x" icon that will reset the selection to the placeholder.
     *
     * @var boolean
     */
    protected $allowClear;

    /**
     * Users can create their own options based on the text that they have entered.
     *
     * @var boolean
     */
    protected $tags;

    /**
     * i18n language code.
     *
     * @var string
     */
    protected $language;

    /**
     * URL to get the results from.
     *
     * @var string|null
     */
    protected $url;

    /**
     * Wait some milliseconds before triggering the request.
     * Defaults to 250 ms.
     *
     * @var integer
     */
    protected $delay;

    /**
     * Enable AJAX cache.
     *
     * @var boolean
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
        return 'SgDatatablesBundle:Filters:filter_select2.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function addAndExpression(Andx $andExpr, QueryBuilder $pivot, $searchField, $searchValue, &$i)
    {
        $orExpr = $pivot->expr()->orX();

        foreach (explode(',', $searchValue) as $searchItem) {
            $orExpr->add($this->getAndExpression($pivot->expr()->andX(), $pivot, $searchField, $searchItem, $i));
            $i++;
        }

        return $andExpr->add($orExpr);
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'select2';
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
            'multiple' => true,
            'placeholder' => null,
            'allow_clear' => false, // allow_clear will only work if a placeholder is set
            'tags' => true,
            'language' => 'en',
            'url' => null,
            'delay' => 250,
            'cache' => true
        ));

        $resolver->setAllowedTypes('multiple', 'bool');
        $resolver->setAllowedTypes('placeholder', array('string', 'null'));
        $resolver->setAllowedTypes('allow_clear', 'bool');
        $resolver->setAllowedTypes('tags', 'bool');
        $resolver->setAllowedTypes('language', 'string');
        $resolver->setAllowedTypes('url', array('string', 'null'));
        $resolver->setAllowedTypes('delay', 'int');
        $resolver->setAllowedTypes('cache', 'bool');

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set multiple.
     *
     * @param boolean $multiple
     *
     * @return $this
     */
    public function setMultiple($multiple)
    {
        $this->multiple = $multiple;

        return $this;
    }

    /**
     * Get multiple.
     *
     * @return boolean
     */
    public function getMultiple()
    {
        return $this->multiple;
    }

    /**
     * Set placeholder.
     *
     * @param string $placeholder
     *
     * @return $this
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * Get placeholder.
     *
     * @return string
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * Set allowClear.
     *
     * @param boolean $allowClear
     *
     * @return $this
     * @throws Exception
     */
    public function setAllowClear($allowClear)
    {
        if (null === $this->placeholder && true === $allowClear) {
            throw new Exception('setAllowClear: The allow_clear option will only work if a placeholder is set.');
        }

        $this->allowClear = $allowClear;

        return $this;
    }

    /**
     * Set allowClear.
     *
     * @return boolean
     */
    public function getAllowClear()
    {
        return $this->allowClear;
    }

    /**
     * Set tags.
     *
     * @param boolean $tags
     *
     * @return $this
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get tags.
     *
     * @return boolean
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set language.
     *
     * @param string $language
     *
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language.
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set url.
     *
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
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
     * Get delay.
     *
     * @return int
     */
    public function getDelay()
    {
        return $this->delay;
    }

    /**
     * Set cache.
     *
     * @param boolean $cache
     *
     * @return $this
     */
    public function setCache($cache)
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * Get cache.
     *
     * @return boolean
     */
    public function getCache()
    {
        return $this->cache;
    }
}
