<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Language
 *
 * @package Sg\DatatablesBundle\Datatable
 */
class Language
{
    /**
     * Use the OptionsResolver.
     */
    use OptionsTrait;

    /**
     * @var array
     */
    protected $languageCDNFile = array(
        'af' => 'Afrikaans.json',
        'ar' => 'Arabic.json',
        'az' => 'Azerbaijan.json',
        'be' => 'Belarusian.json',
        'bg' => 'Bulgarian.json',
        'bn' => 'Bangla.json',
        'ca' => 'Catalan.json',
        'cs' => 'Czech.json',
        'cy' => 'Welsh.json',
        'da' => 'Danish.json',
        'de' => 'German.json',
        'el' => 'Greek.json',
        'en' => 'English.json',
        'es' => 'Spanish.json',
        'et' => 'Estonian.json',
        'eu' => 'Basque.json',
        'fa' => 'Persian.json',
        'fi' => 'Finnish.json',
        'fr' => 'French.json',
        'ga' => 'Irish.json',
        'gl' => 'Galician.json',
        'gu' => 'Gujarati.json',
        'he' => 'Hebrew.json',
        'hi' => 'Hindi.json',
        'hr' => 'Croatian.json',
        'hu' => 'Hungarian.json',
        'hy' => 'Armenian.json',
        'id' => 'Indonesian.json',
        'is' => 'Icelandic.json',
        'it' => 'Italian.json',
        'ja' => 'Japanese.json',
        'ka' => 'Georgian.json',
        'ko' => 'Korean.json',
        'lt' => 'Lithuanian.json',
        'lv' => 'Latvian.json',
        'mk' => 'Macedonian.json',
        'mn' => 'Mongolian.json',
        'ms' => 'Malay.json',
        'nb' => 'Norwegian.json',
        'ne' => 'Nepali.json',
        'nl' => 'Dutch.json',
        'nn' => 'Norwegian.json',
        'pl' => 'Polish.json',
        'ps' => 'Pashto.json',
        'pt' => 'Portuguese.json',
        'ro' => 'Romanian.json',
        'ru' => 'Russian.json',
        'si' => 'Sinhala.json',
        'sk' => 'Slovak.json',
        'sl' => 'Slovenian.json',
        'sq' => 'Albanian.json',
        'sr' => 'Serbian.json',
        'sv' => 'Swedish.json',
        'sw' => 'Swahili.json',
        'ta' => 'Tamil.json',
        'te' => 'Telugu.json',
        'th' => 'Thai.json',
        'tr' => 'Turkish.json',
        'uk' => 'Ukranian.json',
        'ur' => 'Urdu.json',
        'uz' => 'Uzbek.json',
        'vi' => 'Vietnamese.json',
        'zh' => 'Chinese.json',
    );

    /**
     * Get the actual language file by app.request.locale from CDN.
     * Default: false
     *
     * @var bool
     */
    protected $cdnLanguageByLocale;

    /**
     * Get the actual language by app.request.locale.
     * Default: false
     *
     * @var bool
     */
    protected $languageByLocale;

    /**
     * Set a language by given ISO 639-1 code.
     * Default: null
     *
     * @var null|string
     */
    protected $language;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Options constructor.
     */
    public function __construct()
    {
        $this->initOptions();
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
        $resolver->setDefaults(array(
            'cdn_language_by_locale' => false,
            'language_by_locale' => false,
            'language' => null,
        ));

        $resolver->setAllowedTypes('cdn_language_by_locale', 'bool');
        $resolver->setAllowedTypes('language_by_locale', 'bool');
        $resolver->setAllowedTypes('language', array('null', 'string'));

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get languageCDNFile.
     *
     * @return array
     */
    public function getLanguageCDNFile()
    {
        return $this->languageCDNFile;
    }

    /**
     * Get cdnLanguageByLocale.
     *
     * @return bool
     */
    public function isCdnLanguageByLocale()
    {
        return $this->cdnLanguageByLocale;
    }

    /**
     * Set cdnLanguageByLocale.
     *
     * @param bool $cdnLanguageByLocale
     *
     * @return $this
     */
    public function setCdnLanguageByLocale($cdnLanguageByLocale)
    {
        $this->cdnLanguageByLocale = $cdnLanguageByLocale;

        return $this;
    }

    /**
     * Get languageByLocale.
     *
     * @return bool
     */
    public function isLanguageByLocale()
    {
        return $this->languageByLocale;
    }

    /**
     * Set languageByLocale.
     *
     * @param bool $languageByLocale
     *
     * @return $this
     */
    public function setLanguageByLocale($languageByLocale)
    {
        $this->languageByLocale = $languageByLocale;

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
}
