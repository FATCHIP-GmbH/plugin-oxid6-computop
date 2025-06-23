<?php

namespace Fatchip\ComputopPayments\Helper;

class Api
{
    /**
     * @var Payment
     */
    protected static $instance = null;

    /**
     * Source: https://en.wikipedia.org/wiki/ISO_4217#Active_codes_(List_One)
     *
     * @var array
     */
    protected $nonDecimalCurrencies = [
        'BIF', // Burundian franc	 Burundi
        'CLP', // Chilean peso	 Chile
        'DJF', // Djiboutian franc	 Djibouti
        'GNF', // Guinean franc	 Guinea
        'ISK', // Icelandic króna (plural: krónur)	 Iceland
        'JPY', // Japanese yen	 Japan
        'KMF', // Comoro franc	 Comoros
        'KRW', // South Korean won	 South Korea
        'PYG', // Paraguayan guaraní	 Paraguay
        'RWF', // Rwandan franc	 Rwanda
        'UGX', // Ugandan shilling	 Uganda
        'UYI', // Uruguay Peso en Unidades Indexadas (URUIURUI) (funds code)	 Uruguay
        'VND', // Vietnamese đồng	 Vietnam
        'VUV', // Vanuatu vatu	 Vanuatu
        'XAF', // CFA franc BEAC	 Cameroon (CM),  Central African Republic (CF),  Republic of the Congo (CG),  Chad (TD),  Equatorial Guinea (GQ),  Gabon (GA)
        'XOF', // CFA franc BCEAO	 Benin (BJ),  Burkina Faso (BF),  Côte d'Ivoire (CI),  Guinea-Bissau (GW),  Mali (ML),  Niger (NE),  Senegal (SN),  Togo (TG)
        'XPF', // CFP franc (franc Pacifique)	French territories of the Pacific Ocean:  French Polynesia (PF),  New Caledonia (NC),  Wallis and Futuna (WF)
    ];

    /**
     * Create singleton instance of this payment helper
     *
     * @return Payment
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = oxNew(self::class);
        }
        return self::$instance;
    }

    /**
     * Formats amount for API
     * Docs say: Amount in the smallest currency unit (e.g. EUR Cent)
     *
     * @param double $amount
     * @param string $currencyCode
     * @return float|int
     */
    public function formatAmount($amount, $currencyCode = 'EUR')
    {
        $decimalMultiplier = 100;
        if (in_array($currencyCode, $this->nonDecimalCurrencies)) {
            $decimalMultiplier = 1;
        }
        return number_format($amount * $decimalMultiplier, 0, '.', '');
    }

    /**
     * Encode array in json and then in base64 for api requests
     *
     * @param  array $array
     * @return string
     */
    public function encodeArray($array)
    {
        return base64_encode(json_encode($array));
    }
}
