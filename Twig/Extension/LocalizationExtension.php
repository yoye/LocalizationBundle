<?php

namespace Yoye\Bundle\LocalizationBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Yoye\Bundle\LocalizationBundle\Localization\Localizer;

class LocalizationExtension extends \Twig_Extension
{

    /**
     * @var Localizer
     */
    private $localizer;

    public function __construct(ContainerInterface $container)
    {
        if ($container->isScopeActive('request')) {
            $this->localizer = $container->get('yoye_localization.localizer');
        }
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
            'l10n_datetime' => new \Twig_Filter_Method($this, 'getDateTimeIntl'),
            'l10n_number_format' => new \Twig_Filter_Method($this, 'getNumberFormat'),
        );
    }

    public function getName()
    {
        return 'localization_extension';
    }
    
    public function __call($method, $arguments)
    {
        if (is_callable(array($this->localizer, $method))) {
            return call_user_func_array(array($this->localizer, $method), $arguments);
        }
    }
    
}
