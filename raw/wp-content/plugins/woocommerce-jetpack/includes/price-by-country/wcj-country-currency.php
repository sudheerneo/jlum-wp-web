<?php
/**
 * WooCommerce Jetpack Country Currency functions
 *
 * @version 2.4.4
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! function_exists( 'wcj_get_yahoo_exchange_rates_supported_currency' ) ) {
	/**
	 * wcj_get_yahoo_exchange_rates_supported_currency.
	 *
	 * @version 2.3.9
	 */
	function wcj_get_yahoo_exchange_rates_supported_currency() {
		return array(
			'KRW' => true,
			'XAG' => true,
			'VND' => true,
			'BOB' => true,
			'MOP' => true,
			'BDT' => true,
			'MDL' => true,
			'VEF' => true,
			'GEL' => true,
			'ISK' => true,
			'BYR' => true,
			'THB' => true,
			'MXV' => true,
			'TND' => true,
			'JMD' => true,
			'DKK' => true,
			'SRD' => true,
			'BWP' => true,
			'NOK' => true,
			'MUR' => true,
			'AZN' => true,
			'INR' => true,
			'MGA' => true,
			'CAD' => true,
			'XAF' => true,
			'LBP' => true,
			'XDR' => true,
			'IDR' => true,
			'IEP' => true,
			'AUD' => true,
			'MMK' => true,
			'LYD' => true,
			'ZAR' => true,
			'IQD' => true,
			'XPF' => true,
			'TJS' => true,
			'CUP' => true,
			'UGX' => true,
			'NGN' => true,
			'PGK' => true,
			'TOP' => true,
			'TMT' => true,
			'KES' => true,
			'CRC' => true,
			'MZN' => true,
			'SYP' => true,
			'ANG' => true,
			'ZMW' => true,
			'BRL' => true,
			'BSD' => true,
			'NIO' => true,
			'GNF' => true,
			'BMD' => true,
			'SLL' => true,
			'MKD' => true,
			'BIF' => true,
			'LAK' => true,
			'BHD' => true,
			'SHP' => true,
			'BGN' => true,
			'SGD' => true,
			'CNY' => true,
			'EUR' => true,
			'TTD' => true,
			'SCR' => true,
			'BBD' => true,
			'SBD' => true,
			'MAD' => true,
			'GTQ' => true,
			'MWK' => true,
			'PKR' => true,
			'ITL' => true,
			'PEN' => true,
			'AED' => true,
			'LVL' => true,
			'XPD' => true,
			'UAH' => true,
			'FRF' => true,
			'LRD' => true,
			'LSL' => true,
			'SEK' => true,
			'RON' => true,
			'XOF' => true,
			'COP' => true,
			'CDF' => true,
			'USD' => true,
			'TZS' => true,
			'GHS' => true,
			'NPR' => true,
			'ZWL' => true,
			'SOS' => true,
			'DZD' => true,
			'FKP' => true,
			'LKR' => true,
			'JPY' => true,
			'CHF' => true,
			'KYD' => true,
			'CLP' => true,
			'IRR' => true,
			'AFN' => true,
			'DJF' => true,
			'SVC' => true,
			'PLN' => true,
			'PYG' => true,
			'ERN' => true,
			'ETB' => true,
			'ILS' => true,
			'TWD' => true,
			'KPW' => true,
			'SIT' => true,
			'GIP' => true,
			'BND' => true,
			'HNL' => true,
			'CZK' => true,
			'HUF' => true,
			'BZD' => true,
			'DEM' => true,
			'JOD' => true,
			'RWF' => true,
			'LTL' => true,
			'RUB' => true,
			'RSD' => true,
			'WST' => true,
			'XPT' => true,
			'NAD' => true,
			'PAB' => true,
			'DOP' => true,
			'ALL' => true,
			'HTG' => true,
			'AMD' => true,
			'KMF' => true,
			'MRO' => true,
			'HRK' => true,
			'ECS' => true,
			'KHR' => true,
			'PHP' => true,
			'CYP' => true,
			'KWD' => true,
			'XCD' => true,
			'XCP' => true,
			'CNH' => true,
			'SDG' => true,
			'CLF' => true,
			'KZT' => true,
			'TRY' => true,
			'FJD' => true,
			'NZD' => true,
			'BAM' => true,
			'BTN' => true,
			'STD' => true,
			'VUV' => true,
			'MVR' => true,
			'AOA' => true,
			'EGP' => true,
			'QAR' => true,
			'OMR' => true,
			'CVE' => true,
			'KGS' => true,
			'MXN' => true,
			'MYR' => true,
			'GYD' => true,
			'SZL' => true,
			'YER' => true,
			'SAR' => true,
			'UYU' => true,
			'GBP' => true,
			'UZS' => true,
			'GMD' => true,
			'AWG' => true,
			'MNT' => true,
			'XAU' => true,
			'HKD' => true,
			'ARS' => true,
		);
	}
}

if ( ! function_exists( 'wcj_get_paypal_supported_currencies' ) ) {
	/**
	 * wcj_get_paypal_supported_currencies.
	 *
	 * @version 2.3.9
	 */
	function wcj_get_paypal_supported_currencies() {
		return array(
			'AUD' => true,
			'BRL' => true,
			'CAD' => true,
			'CHF' => true,
			'CZK' => true,
			'DKK' => true,
			'EUR' => true,
			'GBP' => true,
			'HKD' => true,
			'HUF' => true,
			'ILS' => true,
			'JPY' => true,
			'MYR' => true,
			'MXN' => true,
			'NOK' => true,
			'NZD' => true,
			'PHP' => true,
			'PLN' => true,
			'RUB' => true,
			'SEK' => true,
			'SGD' => true,
			'THB' => true,
			'TRY' => true,
			'TWD' => true,
			'USD' => true,
		);
	}
}

if ( ! function_exists( 'wcj_get_country_currency' ) ) {
	/**
	 * wcj_get_country_currency.
	 *
	 * @version 2.4.4
	 */
	function wcj_get_country_currency() {
		return array(
			'ZW' => 'ZAR',
			'BT' => 'BTN',
			'BN' => 'BND',
			'KH' => 'KHR',
			'CU' => 'CUP',
			'IM' => 'GBP',
			'JE' => 'JEP',
			'LS' => 'LSL',
			'NA' => 'NAD',
			'PS' => 'JOD',
			'PA' => 'PAB',
			'SG' => 'SGD',
			'UA' => 'UAH',
			'AF' => 'AFN',
			'AL' => 'ALL',
			'DZ' => 'DZD',
			'AD' => 'EUR',
			'AO' => 'AOA',
			'AI' => 'XCD',
			'AG' => 'XCD',
			'AR' => 'ARS',
			'AM' => 'AMD',
			'AW' => 'AWG',
			'AU' => 'AUD',
			'AT' => 'EUR',
			'AZ' => 'AZN',
			'BS' => 'BSD',
			'BH' => 'BHD',
			'BD' => 'BDT',
			'BB' => 'BBD',
			'BY' => 'BYR',
			'BE' => 'EUR',
			'BZ' => 'BZD',
			'BJ' => 'XOF',
			'BM' => 'BMD',
			'BO' => 'BOB',
			'BQ' => 'USD',
			'BA' => 'BAM',
			'BW' => 'BWP',
			'BR' => 'BRL',
			'IO' => 'USD',
			'VG' => 'USD',
			'BG' => 'BGN',
			'BF' => 'XOF',
			'BI' => 'BIF',
			'KY' => 'KYD',
			'CM' => 'XAF',
			'CA' => 'CAD',
			'CV' => 'CVE',
			'CF' => 'XAF',
			'TD' => 'XAF',
			'CL' => 'CLP',
			'CN' => 'CNY',
			'CY' => 'EUR',
			'CC' => 'AUD',
			'CO' => 'COP',
			'KM' => 'KMF',
			'CG' => 'CDF',
			'CK' => 'NZD',
			'CR' => 'CRC',
			'CI' => 'XOF',
			'HR' => 'HRK',
			'CW' => 'ANG',
			'CZ' => 'CZK',
			'DK' => 'DKK',
			'DJ' => 'DJF',
			'DM' => 'XCD',
			'DO' => 'DOP',
			'TP' => 'USD',
			'EC' => 'USD',
			'EG' => 'EGP',
			'SV' => 'USD',
			'GQ' => 'XAF',
			'ER' => 'ERN',
			'EE' => 'EUR',
			'ET' => 'ETB',
			'FK' => 'FKP',
			'FO' => 'DKK',
			'FJ' => 'FJD',
			'FI' => 'EUR',
			'FR' => 'EUR',
			'PF' => 'XPF',
			'GA' => 'XAF',
			'GM' => 'GMD',
			'GE' => 'GEL',
			'DE' => 'EUR',
			'GH' => 'GHS',
			'GI' => 'GIP',
			'GR' => 'EUR',
			'GD' => 'XCD',
			'GT' => 'GTQ',
			'GG' => 'GBP',
			'GY' => 'GYD',
			'GN' => 'GNF',
			'GW' => 'XOF',
			'HT' => 'HTG',
			'HN' => 'HNL',
			'HK' => 'HKD',
			'HU' => 'HUF',
			'IS' => 'ISK',
			'YE' => 'YER',
			'IN' => 'INR',
			'ID' => 'IDR',
			'IR' => 'IRR',
			'IQ' => 'IQD',
			'IE' => 'EUR',
			'IL' => 'ILS',
			'IT' => 'EUR',
			'JM' => 'JMD',
			'JP' => 'JPY',
			'JO' => 'JOD',
			'KZ' => 'KZT',
			'KE' => 'KES',
			'KG' => 'KGS',
			'KI' => 'AUD',
			'KP' => 'KPW',
			'KR' => 'KRW',
			'XK' => 'EUR',
			'KW' => 'KWD',
			'LA' => 'LAK',
			'LV' => 'EUR',
			'LB' => 'LBP',
			'LR' => 'LRD',
			'LY' => 'LYD',
			'LI' => 'CHF',
			'LT' => 'EUR',
			'LU' => 'EUR',
			'MO' => 'MOP',
			'MK' => 'MKD',
			'MG' => 'MGA',
			'MY' => 'MYR',
			'MW' => 'MWK',
			'MV' => 'MVR',
			'ML' => 'XOF',
			'MT' => 'EUR',
			'MH' => 'USD',
			'MR' => 'MRO',
			'MU' => 'MUR',
			'MX' => 'MXN',
			'MM' => 'MMK',
			'FM' => 'USD',
			'MD' => 'MDL',
			'MC' => 'EUR',
			'MN' => 'MNT',
			'ME' => 'EUR',
			'MS' => 'XCD',
			'MA' => 'MAD',
			'MZ' => 'MZN',
			'NR' => 'AUD',
			'NP' => 'NPR',
			'NL' => 'EUR',
			'NC' => 'XPF',
			'NZ' => 'NZD',
			'NI' => 'NIO',
			'NE' => 'XOF',
			'NG' => 'NGN',
			'NU' => 'NZD',
			'NO' => 'NOK',
			'OM' => 'OMR',
			'PK' => 'PKR',
			'PW' => 'USD',
			'PG' => 'PGK',
			'PY' => 'PYG',
			'PE' => 'PEN',
			'PH' => 'PHP',
			'PN' => 'NZD',
			'PL' => 'PLN',
			'PT' => 'EUR',
			'QA' => 'QAR',
			'RO' => 'RON',
			'RU' => 'RUB',
			'RW' => 'RWF',
			'SH' => 'SHP',
			'KN' => 'XCD',
			'LC' => 'XCD',
			'VC' => 'XCD',
			'WS' => 'WST',
			'SM' => 'EUR',
			'ST' => 'STD',
			'SA' => 'SAR',
			'SC' => 'SCR',
			'SN' => 'XOF',
			'RS' => 'RSD',
			'SL' => 'SLL',
			'SX' => 'ANG',
			'SY' => 'SYP',
			'SK' => 'EUR',
			'SI' => 'EUR',
			'SB' => 'SBD',
			'SO' => 'SOS',
			'ZA' => 'ZAR',
			'GS' => 'GBP',
			'SS' => 'SSP',
			'ES' => 'EUR',
			'LK' => 'LKR',
			'SD' => 'SDG',
			'SR' => 'SRD',
			'SZ' => 'SZL',
			'SE' => 'SEK',
			'CH' => 'CHF',
			'TW' => 'TWD',
			'TJ' => 'TJS',
			'TZ' => 'TZS',
			'TH' => 'THB',
			'TG' => 'XOF',
			'TO' => 'TOP',
			'TT' => 'TTD',
			'SH' => 'SHP',
			'TN' => 'TND',
			'TR' => 'TRY',
			'TM' => 'TMT',
			'TC' => 'USD',
			'TV' => 'AUD',
			'UG' => 'UGX',
			'AE' => 'AED',
//			'UK' => 'GBP',
			'GB' => 'GBP',
			'US' => 'USD',
			'UY' => 'UYU',
			'UZ' => 'UZS',
			'VU' => 'VUV',
			'VA' => 'EUR',
			'VE' => 'VEF',
			'VN' => 'VND',
			'WF' => 'XPF',
			'ZM' => 'ZMW',
		);
	}
}
