<?php

namespace Yoye\Bundle\LocalizationBundle\Twig\Extension;

use DateTime, IntlDateFormatter, NumberFormatter;
use Symfony\Component\Locale\Locale;
use Symfony\Component\Locale\Stub\StubLocale;
use Symfony\Component\HttpFoundation\Session;

class LocalizationExtension extends \Twig_Extension
{

    /**
     * @var Session
     */
    private $session;

    /**
     * @var array
     */
    private $currencies = array();

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getFunctions()
    {
        return array(
            'l10n_country' => new \Twig_Function_Method($this, 'getDisplayCountry'),
            'l10n_language' => new \Twig_Function_Method($this, 'getDisplayLanguage'),
            'l10n_currency_symbol' => new \Twig_Function_Method($this, 'getCurrencySymbol'),
        );
    }

    public function getFilters()
    {
        return array(
            'l10n_date' => new \Twig_Filter_Method($this, 'getDateIntl'),
            'l10n_time' => new \Twig_Filter_Method($this, 'getTimeIntl'),
            'l10n_number_format' => new \Twig_Filter_Method($this, 'getNumberFormat'),
        );
    }

    public function getName()
    {
        return 'localization_extension';
    }

    /**
     * Localize country name
     *
     * @param string $code 
     * @return string
     */
    public function getDisplayCountry($code)
    {
        $code = strtoupper($code);
        $countries = Locale::getDisplayCountries($this->session->getLocale());

        if (isset($countries[$code])) {
            return $countries[$code];
        }
    }

    /**
     * Get language name
     * 
     * @param string $code 
     * @return string
     */
    public function getDisplayLanguage($code)
    {
        $languages = Locale::getDisplayLanguages($this->session->getLocale());

        if (isset($languages[$code])) {
            return $languages[$code];
        }
    }

    /**
     * Get currency symbol
     * 
     * @param string $code
     * @return string
     */
    public function getCurrencySymbol($code)
    {
        if (empty($this->currencies)) {
            $this->currencies = StubLocale::getCurrenciesData('en');
        }

        if (isset($this->currencies[$code]['symbol'])) {
            return $this->currencies[$code]['symbol'];
        }
    }

    /**
     * Format date 
     * 
     * @param \DateTime $date 
     * @return string
     */
    public function getDateIntl(DateTime $date, $dateFormat = IntlDateFormatter::MEDIUM)
    {
        $intl = new IntlDateFormatter($this->session->getLocale(), $dateFormat, IntlDateFormatter::NONE);
        
        return $intl->format($date->getTimestamp());
    }

    /**
     * Format time
     * 
     * @param \DateTime $date 
     * @return string
     */
    public function getTimeIntl(DateTime $date, $timeFormat = IntlDateFormatter::MEDIUM)
    {
        $intl = new IntlDateFormatter($this->session->getLocale(), IntlDateFormatter::NONE, $timeFormat);

        return $intl->format($date->getTimestamp());
    }
    
    /**
     * Number formatter
     * 
     * @param mixed $value
     * @param integer $type
     * @return string
     */
    public function getNumberFormat($value, $type = NumberFormatter::DECIMAL)
    {
        $formatter = new NumberFormatter($this->session->getLocale(), $type);
        
        return $formatter->format($value);
    }

}