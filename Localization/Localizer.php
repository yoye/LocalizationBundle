<?php

namespace Yoye\Bundle\LocalizationBundle\Localization;

use DateTime,
    IntlDateFormatter,
    NumberFormatter;
use Symfony\Component\Locale\Locale;
use Symfony\Component\Locale\Stub\StubLocale;
use Symfony\Component\HttpFoundation\Request;

class Localizer
{

    /**
     * @var Request
     */
    private $request;

    /**
     * @var array
     */
    private $currencies = array();

    /**
     * __construct
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Localize country name
     *
     * @param  string $code
     * @return string
     */
    public function getDisplayCountry($code)
    {
        $code = strtoupper($code);
        $countries = Locale::getDisplayCountries($this->request->getLocale());

        if (isset($countries[$code])) {
            return $countries[$code];
        }
    }

    /**
     * Get language name
     *
     * @param  string $code
     * @return string
     */
    public function getDisplayLanguage($code)
    {
        $languages = Locale::getDisplayLanguages($this->request->getLocale());

        if (isset($languages[$code])) {
            return $languages[$code];
        }
    }

    /**
     * Get currency symbol
     *
     * @param  string $code
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
     * @param  DateTime $date
     * @return string
     */
    public function getDateIntl(DateTime $date, $pattern = null, $dateFormat = IntlDateFormatter::MEDIUM)
    {
        $intl = new IntlDateFormatter($this->request->getLocale(), $dateFormat, IntlDateFormatter::NONE);
        
        if (null !== $pattern) {
            $intl->setPattern($pattern);
        }

        return $intl->format($date->getTimestamp());
    }

    /**
     * Format time
     *
     * @param  DateTime $date
     * @return string
     */
    public function getTimeIntl(DateTime $date, $timeFormat = IntlDateFormatter::MEDIUM)
    {
        $intl = new IntlDateFormatter($this->request->getLocale(), IntlDateFormatter::NONE, $timeFormat);

        return $intl->format($date->getTimestamp());
    }

    /**
     * Number formatter
     *
     * @param  mixed   $value
     * @param  integer $type
     * @return string
     */
    public function getNumberFormat($value, $type = NumberFormatter::DECIMAL)
    {
        $formatter = new NumberFormatter($this->request->getLocale(), $type);

        return $formatter->format($value);
    }

    /**
     *
     * @param  mixed  $value
     * @param  string $currency
     * @return string
     */
    public function getNumberCurrencyFormat($value, $currency)
    {
        $formatter = new NumberFormatter($this->request->getLocale(), NumberFormatter::CURRENCY);

        return $formatter->formatCurrency($value, $currency);
    }

    /**
     * Get datetime formatter
     *
     * @param  DateTime $date
     * @param  integer  $dateFormat
     * @param  integer  $timeFormat
     * @return string
     */
    public function getDateTimeIntl(DateTime $date, $dateFormat = IntlDateFormatter::MEDIUM, $timeFormat = IntlDateFormatter::SHORT)
    {
        $intl = new IntlDateFormatter($this->request->getLocale(), $dateFormat, $timeFormat);

        return $intl->format($date->getTimestamp());
    }

}
