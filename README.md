# NZ Bank Account Validation for PHP

A PHP library that validates New Zealand bank account numbers (BECS numbers) as matching the format, within valid ranges, and contain a valid checksum.

## Licence
MIT

## Requirements
* PHP 5.4+

## Usage
```php
$account = new \Glutnix\BankAccountValidator("01-902-0068389-00");
echo $account->isValid() ? "Valid" : "Not Valid";
```
There are other usable methods on BankAccountValidator, so check the code: check the spec for how they are used. Maybe contribute some documentation?

## Contributing
Open an issue to check if your contribution is desirable, or just go right ahead and send a Pull Request against the master branch.

## References
* [PaymentsNZ Bulk Electronic Clearing System (BECS)](http://www.paymentsnz.co.nz/clearing-systems/bulk-electronic-clearing-system)
* [IRD - Validating Bank Account Numbers](https://www.ird.govt.nz/resources/d/8/d8e49dce-1bda-4875-8acf-9ebf908c6e17/rwt-nrwt-spec-2014.pdf)
