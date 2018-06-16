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