<?php

namespace numberInWords;

use Exception;

class numberInWords {
    private static $decimals = 2;
    private static $lang = null;

    public static function setLang(string $lang = 'lt') {
        $lang_filepath = __DIR__.'/../lang/'.$lang.'.php';
        if (!file_exists($lang_filepath)) {
            throw new Exception('Lang file not found');
        }

        require_once($lang_filepath);
    }

    public static function setLangStrings(array $lang) {
        self::$lang = $lang;
    }

    /**
     * Alias for getNumberInWordsTranslated
     */
    public static function get(float $number): string {
        return self::getNumberInWordsTranslated($number);
    }

    public static function getNumberInWordsTranslated($number): string {
        if (self::$lang === null) {
            self::setLang();
        }

        $words = self::getNumberInWords($number);
        $string = [];
        foreach ($words as $word) {
            $string[] = self::$lang[$word];
        }

        // get decimal
        $decimal = '';
        if (!empty(self::$decimals)) {
            $decimal = self::getDecimalPartAsString($number);
        }

        return implode(' ', $string).' '.$decimal;
    }

    public static function getNumberInWords($number): array {
        // remove from number
        $whole = self::getWholePartAsString($number);

        // split into hundreds
        $hundreds = self::getHundredsFromWhole($whole);

        // generate string for all hundreds
        $words = [];
        $group_zeroes = (count($hundreds) - 1) * 3;
        foreach ($hundreds as $hundred) {
            $words = array_merge($words, self::getWordsForHundred($hundred));
            if ($group_zeroes >= 3 && $hundred !== '000') {
                $words[] = self::getNameOfGroupByZeroCount($group_zeroes, (int)$hundred);
            }

            $group_zeroes -= 3;
        }

        return $words;
    }

    protected static function getWordsForHundred(string $hundred): array {
        if (strlen($hundred) !== 3) {
            throw new Exception('Hundred must consist of exactly three characters');
        }

        $words = [];

        if ($hundred[0] !== '0') {
            $words[] = self::getDigitName((int)$hundred[0]);
            $words[] = self::getHundredString((int)$hundred[0]);
        }

        if ($hundred[1] === '1') {
            $words[] = self::getElevenToNineteenName((int)$hundred[2]);
        } else {
            if ($hundred[1] !== '0') {
                $words[] = self::getNameOfATen((int)$hundred[1]);
            }

            if ($hundred[2] !== '0') {
                $words[] = self::getDigitName((int)$hundred[2]);
            }
        }

        return $words;
    }

    public static function getDecimalPartAsString($number): string {
        $float_str = number_format($number, self::$decimals, '.', '');
        $parts = explode('.', $float_str);
        return $parts[1];
    }

    protected static function getWholePartAsString($number): string {
        $float_str = number_format($number, self::$decimals, '.', '');
        $parts = explode('.', $float_str);
        return $parts[0];
    }

    protected static function getHundredsFromWhole(string $whole): array {
        $rev = strrev($whole);
        $split = str_split($rev, 3);
        array_walk(
            $split,
            function (&$val) {
                $val = strrev($val);
            }
        );
        $split = array_reverse($split);

        $split[0] = str_pad($split[0], 3, '0', STR_PAD_LEFT);

        return $split;
    }

    public static function getDecimals(): int {
        return self::$decimals;
    }

    public static function setDecimals(int $decimals) {
        self::$decimals = $decimals;
    }

    public static function getDigitName(int $digit): string {
        if ($digit < 0 || $digit > 9) {
            throw new Exception('Must be a single digit from 0 to 9');
        }

        return 'DIGIT_' . (string)$digit;
    }

    public static function getNameOfATen(int $ten): string {
        if ($ten < 2 || $ten > 9) {
            throw new Exception("Ten must be between 2 and 9. {$ten} given");
        }

        return 'DIGIT_' . (string)$ten . '0';
    }

    public static function getHundredString(int $hundred): string {
        if ($hundred === 1) {
            return 'HUNDRED_SINGULAR';
        } elseif ($hundred <= 9) {
            return 'HUNDRED_PLURAL';
        } else {
            throw new Exception("Hundred must be between 1 and 9. {$hundred} given");
        }
    }

    public static function getNameOfGroupByZeroCount(int $zeroes, int $count): string {
        if ($zeroes < 3) {
            return '';
        }

        $names = [
            '3' => 'THOUSAND',
            '6' => 'MILLION',
            '9' => 'BILLION',
            '12' => 'TRILLION',
            '15' => 'QUADRILLION',
            '18' => 'QUINTILLION',
            '21' => 'SEXTILLION',
        ];

        if (!isset($names[$zeroes])) {
            throw new Exception('Invalid zero count. {$zeroes} given');
        }

        $group = 'GROUP_' . $names[$zeroes];
        $form = self::getNumberNameForm($count);

        return $group . '_' . $form;
    }

    public static function getNumberNameForm(int $count) {
        $count_str = (string)$count;
        $last_digit = substr($count_str, -1, 1);

        if ($count >= 10 && $count <= 19 || $count % 10 === 0) {
            return 'POSSESIVE';
        } elseif ($last_digit === '1') {
            return 'SINGULAR';
        } else {
            return 'PLURAL';
        }
    }

    public static function getElevenToNineteenName(int $last_digit) {
        if ($last_digit < 0 || $last_digit > 9) {
            throw new Exception("Digit must be between 1 and 9. {$last_digit} given");
        }

        return 'DIGIT_1' . $last_digit;
    }
}
