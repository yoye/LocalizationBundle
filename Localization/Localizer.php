<?php

namespace Yoye\Bundle\LocalizationBundle\Localization;

use DateTime,
    IntlDateFormatter,
    NumberFormatter;
use Symfony\Component\Locale\Locale;
use Symfony\Component\Locale\Stub\StubLocale;
use Symfony\Component\HttpFoundation\Session\Session;

class Localizer
{

    /**
     * @var Session
     */
    private $session;

    /**
     * @var array
     */
    private $currencies = array();

    /**
     * __construct
     * 
     * @param Session $session 
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
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
     * @param DateTime $date 
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
     * @param DateTime $date 
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
    
    /**
     * Get datetime formatter
     * 
     * @param DateTime $date
     * @param integer $dateFormat
     * @param integer $timeFormat
     * @return string
     */
    public function getDateTimeIntl(DateTime $date, $dateFormat = IntlDateFormatter::MEDIUM, $timeFormat = IntlDateFormatter::SHORT)
    {
        $intl = new IntlDateFormatter($this->session->getLocale(), $dateFormat, $timeFormat);

        return $intl->format($date->getTimestamp());
    }

}
