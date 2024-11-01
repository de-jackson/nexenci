<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\CurrenciesModel;

class CurrenciesSeeder extends Seeder
{
    public function run()
    {
        $currenciesModel = new CurrenciesModel;
        $currencies = [
            [
                'currency' => 'AED',
                'symbol' => '&#1583;.&#1573;',
            ],
            [
                'currency' => 'AFN',
                'symbol' => '&#65;&#102;',
            ],
            [
                'currency' => 'ALL',
                'symbol' => '&#76;&#101;&#107;',
            ],
            [
                'currency' => 'AMD',
                'symbol' => '&#1423;',
            ],
            [
                'currency' => 'ANG',
                'symbol' => '&#402;',
            ],
            [
                'currency' => 'AOA',
                'symbol' => '&#75;&#122;',
            ],
            [
                'currency' => 'ARS',
                'symbol' => '&#36;',
            ],
            [
                'currency' => 'AUD',
                'symbol' => '&#36;',
            ],
            [
                'currency' => 'AWG',
                'symbol' => '&#402;',
            ],
            [
                'currency' => 'AZN',
                'symbol' => '&#1084;&#1072;&#1085;',
            ],
            [
                'currency' => 'BAM',
                'symbol' => '&#75;&#77;',
            ],
            [
                'currency' => 'BBD',
                'symbol' => '&#36;',
            ],
            [
                'currency' => 'BDT',
                'symbol' => '&#2547;',
            ],
            [
                'currency' => 'BGN',
                'symbol' => '&#1083;&#1074;',
            ],
            [
                'currency' => 'BHD',
                'symbol' => '.&#1583;.&#1576;',
            ],
            [
                'currency' => 'BIF',
                'symbol' => '&#70;&#66;&#117;',
            ],
            [
                'currency' => 'BMD',
                'symbol' => '&#36;',
            ],
            [
                'currency' => 'BND',
                'symbol' => '&#36;',
            ],
            [
                'currency' => 'BOB',
                'symbol' => '&#36;&#98;',
            ],
            [
                'currency' => 'BRL',
                'symbol' => '&#82;&#36;',
            ],
            [
                'currency' => 'BSD',
                'symbol' => '&#36;',
            ],
            [
                'currency' => 'BTN',
                'symbol' => '&#78;&#117;&#46;',
            ],
            [
                'currency' => 'BWP',
                'symbol' => '&#80;',
            ],
            [
                'currency' => 'BYR',
                'symbol' => '&#112;&#46;',
            ],
            [
                'currency' => 'BZD',
                'symbol' => '&#66;&#90;&#36;',
            ],
            [
                'currency' => 'CAD',
                'symbol' => '&#36;',
            ],
            [
                'currency' => 'CDF',
                'symbol' => '&#70;&#67;',
            ],
            [
                'currency' => 'CHF',
                'symbol' => '&#67;&#72;&#70;',
            ],
            [
                'currency' => 'CLF',
                'symbol' => 'CLF',
            ],
            [
                'currency' => 'CLP',
                'symbol' => '&#36;',
            ],
            [
                'currency' => 'CNY',
                'symbol' => '&#165;',
            ],
            [
                'currency' => 'COP',
                'symbol' => '&#36;',
            ],
            [
                'currency' => 'CRC',
                'symbol' => '&#8353;',
            ],
            [
                'currency' => 'CUP',
                'symbol' => '&#8396;',
            ],
            [
                'currency' => 'CVE',
                'symbol' => '&#36;',
            ],
            [
                'currency' => 'CZK',
                'symbol' => '&#75;&#269;',
            ],
            [
                'currency' => 'DJF',
                'symbol' => '&#70;&#100;&#106;',
            ],
            [
                'currency' => 'DKK',
                'symbol' => '&#107;&#114;',
            ],
            [
                'currency' => 'DOP',
                'symbol' => '&#82;&#68;&#36;',
            ],
            [
                'currency' => 'DZD',
                'symbol' => '&#1583;&#1580;',
            ],
            [
                'currency' => 'EGP',
                'symbol' => '&#163;',
            ],
            [
                'currency' => 'ETB',
                'symbol' => '&#66;&#114;',
            ],
            [
                'currency' => 'EUR',
                'symbol' => '&#8364;',
            ],
            [
                'currency' => 'FJD',
                'symbol' => '&#36;',
            ],
            [
                'currency' => 'FKP',
                'symbol' => '&#163;',
            ],
            [
                'currency' => 'GBP',
                'symbol' => '&#163;',
            ],
            [
                'currency' => 'GEL',
                'symbol' => '&#4314;',
            ],
            [
                'currency' => 'GHS',
                'symbol' => '&#162;',
            ],
            [
                'currency' => 'GIP',
                'symbol' => '&#163;',
            ],
            [
                'currency' => 'GMD',
                'symbol' => '&#68;',
            ],
            [
                'currency' => 'GNF',
                'symbol' => '&#70;&#71;',
            ],
            [
                'currency' => 'GTQ',
                'symbol' => '&#81;',
            ],
            [
                'currency' => 'GYD',
                'symbol' => '&#36;',
            ],
            [
                'currency' => 'HKD',
                'symbol' => '&#36;',
            ],
            [
                'currency' => 'HNL',
                'symbol' => '&#76;',
            ],
            [
                'currency' => 'HRK',
                'symbol' => '&#107;&#110;',
            ],
            [
                'currency' => 'HTG',
                'symbol' => '&#71;',
            ],
            [
                'currency' => 'HUF',
                'symbol' => '&#70;&#116;',
            ],
            [
                'currency' => 'IDR',
                'symbol' => '&#82;&#112;',
            ],
            [
                'currency' => 'ILS',
                'symbol' => '&#8362;',
            ],
            [
                'currency' => 'INR',
                'symbol' => '&#8377;',
            ],
            [
                'currency' => 'IQD',
                'symbol' => '&#1593;.&#1583;',
            ],
            [
                'currency' => 'IRR',
                'symbol' => '&#65020;',
            ],
            [
                'currency' => 'ISK',
                'symbol' => '&#107;&#114;',
            ],
            [
                'currency' => 'JEP',
                'symbol' => '&#163;',
            ],
            [
                'currency' => 'JMD',
                'symbol' => '&#74;&#36;',
            ],
            [
                'currency' => 'JOD',
                'symbol' => '&#74;&#68;',
            ],
            [
                'currency' => 'JPY',
                'symbol' => '&#165;',
            ],
            [
                'currency' => 'KES',
                'symbol' => '&#75;&#83;&#104;',
            ],
            [
                'currency' => 'KGS',
                'symbol' => '&#1083;&#1074;',
            ],
            [
                'currency' => 'KHR',
                'symbol' => '&#6107;',
            ],
            [
                'currency' => 'KMF',
                'symbol' => '&#67;&#70;',
            ],
            [
                'currency' => 'KPW',
                'symbol' => '&#8361;',
            ],
            [
                'currency' => 'KRW',
                'symbol' => '&#8361;',
            ],
            [
                'currency' => 'KWD',
                'symbol' => '&#1583;.&#1603;',
            ],
            [
                'currency' => 'KYD',
                'symbol' => '&#36;',
            ],
            [
                'currency' => 'KZT',
                'symbol' => '&#1083;&#1074;',
            ],
            [
                'currency' => 'LAK',
                'symbol' => '&#8365;',
            ],
            [
                'currency' => 'LBP',
                'symbol' => '&#163;',
            ],
            [
                'currency' => 'LKR',
                'symbol' => '&#8360;',
            ],
            [
                'currency' => 'LRD',
                'symbol' => '&#36;',
            ],
            [
                'currency' => 'LSL',
                'symbol' => '&#76;',
            ],
            [
                'currency' => 'LTL',
                'symbol' => '&#76;&#116;',
            ],
            [
                'currency' => 'LVL',
                'symbol' => '&#76;&#115;',
            ],
            [
                'currency' => 'LYD',
                'symbol' => '&#1604;.&#1583;',
            ],
            [
                'currency' => 'MAD',
                'symbol' => '&#1583;.&#1605;.',
            ],
            [
                'currency' => 'MDL',
                'symbol' => '&#76;',
            ],
            [
                'currency' => 'MGA',
                'symbol' => '&#65;&#114;',
            ],
            [
                'currency' => 'MKD',
                'symbol' => '&#1076;&#1077;&#1085;',
            ],
            [
                'currency' => 'MMK',
                'symbol' => '&#75;',
            ],
            [
                'currency' => 'MNT',
                'symbol' => '&#8366;',
            ],
            [
                'currency' => 'MOP',
                'symbol' => '&#77;&#79;&#80;&#36;',
            ],
            [
                'currency' => 'MRO',
                'symbol' => '&#85;&#77;',
            ],
            [
                'currency' => 'MUR',
                'symbol' => '&#8360;',
            ],
            [
                'currency' => 'MVR',
                'symbol' => '.&#1923;',
            ],
            [
                'currency' => 'MWK',
                'symbol' => '&#77;&#75;',
            ],
            [
                'currency' => 'MXN',
                'symbol' => '&#36;',
            ],
            [
                'currency' => 'MYR',
                'symbol' => '&#82;&#77;',
            ],
            [
                'currency' => 'MZN',
                'symbol' => '&#77;&#84;',
            ],
            [
                'currency' => 'NAD',
                'symbol' => '&#36;',
            ],
            [
                'currency' => 'NGN',
                'symbol' => '&#8358;',
            ],
            [
                'currency' => 'NIO',
                'symbol' => '&#67;&#36;',
            ],
            [
                'currency' => 'NOK',
                'symbol' => '&#107;&#114;',
            ],
            [
                'currency' => 'NPR',
                'symbol' => '&#8360;',
            ],
            [
                'currency' => 'NZD',
                'symbol' => '&#36;',
            ],
            [
                'currency' => 'OMR',
                'symbol' => '&#65020;',
            ],
            [
                'currency' => 'PAB',
                'symbol' => '&#66;&#47;&#46;',
            ],
            [
                'currency' => 'PEN',
                'symbol' => '&#83;&#47;&#46;',
            ],
            [
                'currency' => 'PGK',
                'symbol' => '&#75;',
            ],
            [
                'currency' => 'PHP',
                'symbol' => '&#8369;',
            ],
            [
                'currency' => 'PKR',
                'symbol' => '&#8360;',
            ],
            [
                'currency' => 'PLN',
                'symbol' => '&#122;&#322;',
            ],
            [
                'currency' => 'PYG',
                'symbol' => '&#71;&#115;',
            ],
            [
                'currency' => 'QAR',
                'symbol' => '&#65020;',
            ],
            [
                'currency' => 'RON',
                'symbol' => '&#108;&#101;&#105;',
            ],
            [
                'currency' => 'RSD',
                'symbol' => '&#1044;&#1080;&#1085;&#46;',
            ],
            [
                'currency' => 'RUB',
                'symbol' => '&#1088;&#1091;&#1073;',
            ],
            [
                'currency' => 'RWF',
                'symbol' => '&#1585;.&#1587;',
            ],
            [
                'currency' => 'SAR',
                'symbol' => '&#65020;',
            ],
            [
                'currency' => 'SBD',
                'symbol' => '&#36;',
            ],
            [
                'currency' => 'SCR',
                'symbol' => '&#8360;',
            ],
            [
                'currency' => 'SDG',
                'symbol' => '&#163;',
            ],
            [
                'currency' => 'SEK',
                'symbol' => '&#107;&#114;',
            ],
            [
                'currency' => 'SGD',
                'symbol' => '&#36;',
            ],
            [
                'currency' => 'SHP',
                'symbol' => '&#163;',
            ],
            [
                'currency' => 'SLL',
                'symbol' => '&#76;&#101;',
            ],
            [
                'currency' => 'SOS',
                'symbol' => '&#83;',
            ],
            [
                'currency' => 'SRD',
                'symbol' => '&#36;',
            ],
            [
                'currency' => 'STD',
                'symbol' => '&#68;&#98;',
            ],
            [
                'currency' => 'SVC',
                'symbol' => '&#36;',
            ],
            [
                'currency' => 'SYP',
                'symbol' => '&#163;',
            ],
            [
                'currency' => 'SZL',
                'symbol' => '&#76;',
            ],
            [
                'currency' => 'THB',
                'symbol' => '&#3647;',
            ],
            [
                'currency' => 'TJS',
                'symbol' => '&#84;&#74;&#83;',
            ],
            [
                'currency' => 'TMT',
                'symbol' => '&#109;',
            ],
            [
                'currency' => 'TND',
                'symbol' => '&#1583;.&#1578;',
            ],
            [
                'currency' => 'TOP',
                'symbol' => '&#84;&#36;',
            ],
            [
                'currency' => 'TRY',
                'symbol' => '&#8356;',
            ],
            [
                'currency' => 'TTD',
                'symbol' => '&#36;',
            ],
            [
                'currency' => 'TWD',
                'symbol' => '&#78;&#84;&#36;',
            ],
            [
                'currency' => 'TZS',
                'symbol' => 'TZS',
            ],
            [
                'currency' => 'UAH',
                'symbol' => '&#8372;',
            ],
            [
                'currency' => 'UGX',
                'symbol' => '&#85;&#83;&#104;',
            ],
            [
                'currency' => 'USD',
                'symbol' => '&#36;',
            ],
            [
                'currency' => 'UYU',
                'symbol' => '&#36;&#85;',
            ],
            [
                'currency' => 'UZS',
                'symbol' => '&#1083;&#1074;',
            ],
            [
                'currency' => 'VEF',
                'symbol' => '&#66;&#115;',
            ],
            [
                'currency' => 'VND',
                'symbol' => '&#8363;',
            ],
            [
                'currency' => 'VUV',
                'symbol' => '&#86;&#84;',
            ],
            [
                'currency' => 'WST',
                'symbol' => '&#87;&#83;&#36;',
            ],
            [
                'currency' => 'XAF',
                'symbol' => '&#70;&#67;&#70;&#65;',
            ],
            [
                'currency' => 'XCD',
                'symbol' => '&#36;',
            ],
            [
                'currency' => 'XDR',
                'symbol' => 'XDR',
            ],
            [
                'currency' => 'XOF',
                'symbol' => 'XOF',
            ],
            [
                'currency' => 'XPF',
                'symbol' => '&#70;',
            ],
            [
                'currency' => 'YER',
                'symbol' => '&#65020;',
            ],
            [
                'currency' => 'ZAR',
                'symbol' => '&#82;',
            ],
            [
                'currency' => 'ZMK',
                'symbol' => '&#90;&#77;&#87;',
            ],
            [
                'currency' => 'ZWL',
                'symbol' => '&#90;&#36;',
            ],
        ];

        $currenciesModel->insertBatch($currencies);
    }
}
