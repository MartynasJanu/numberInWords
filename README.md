# numberInWords
Spells numbers in words. Supports multiple languages (Lithuanian and English included by default)

## Requirements
PHP 7+

## Usage

```
use numberInWords\numberInWords;

echo numberInWords::get(123456);
// one hundred twenty three thousand four hundred fifty six 00
```
```
use numberInWords\numberInWords;

numberInWords::setLang('lt');
echo numberInWords::get(123456);
// vienas šimtas dvidešimt trys tūkstančiai keturi šimtai penkiasdešimt šeši 00
```

```
use numberInWords\numberInWords;

numberInWords::setDecimals(0);
echo numberInWords::get(123456);
// one hundred twenty three thousand four hundred fifty six
```

```
use numberInWords\numberInWords;

numberInWords::setDecimals(1);
echo numberInWords::get(123456.12);
// one hundred twenty three thousand four hundred fifty six 1
```

```
use numberInWords\numberInWords;

$lang = [
    'DIGIT_1' => 'one',
    'DIGIT_10' => 'ten',
    'DIGIT_11' => 'eleven',
    'DIGIT_12' => 'twelve',
    'DIGIT_13' => 'thirteen',
    'DIGIT_14' => 'fourteen',
    'DIGIT_15' => 'fifteen',
    'DIGIT_16' => 'sixteen',
    'DIGIT_17' => 'seventeen',
    'DIGIT_18' => 'eighteen',
    'DIGIT_19' => 'nineteen',
    // ........
];

numberInWords::setLangStrings($lang);
```
