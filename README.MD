# PortaOne Billing API datetime helper class

## Purpose

This package intended to simplify datetime and data fields management when work with Portabilling API.

PortaDateTime class exptends standatd DateTime class to provide specific methods. All the datatime fields of API has MySQL format and always UTC timezone. This helper class allows:
- Create a PortaDateTime class with desired timezone from Portaone UTC-based datetime API string.
- Create PortaDateTime class for any datetime and any timezone and get billing-format UTC datetime string
- Also use any method of DateTime

## Installation
In the Composer storage. Just add proper require section:

    "require": {
        "pavlyuts/porta-datetime": "^0.1"
    }

## Dependencies
- php: ^7.2

## Testing
Tested with PHPUnit 8.5 on php 7.2, up to 100% coverage, which does not mean everything will work as intended.

To test, install in dev mode and use: 
- `composer test` command from library root for run all standard modular tests

## Documentation
All methods has PHPdoc bloks, hope it is enough.


## Usage sample
```
// Typical PortaBilling API datetime string
$portaDateTime = = '2023-03-20 07:38:17';

// Create PortaDateTime from the string
$dateTime = \PortaDateTime::createFromPortaString(self::DATETIME, 'Asia/Seoul');

// Let's say we need to cancel addon product at the last second of today, local time
// First, create the object for 'now' at local timezone
$dateTime = new \PortaDateTime('now', 'Asia/Seoul');
// Get the string with 'last moment of the day' in UTC, ready to pass to the billing
$addonValidToString = $dateTime->getLastMomentString();
//Then, variable contains datetime string you may put to API call field, in UTC format.
```

