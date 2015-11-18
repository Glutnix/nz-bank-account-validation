<?php

namespace Glutnix;

class BankAccountValidator
{

    public $bankId = 0;
    public $bankBranch = 0;
    public $bankAccount = 0;
    public $bankAccountSuffix = 0;

    public function __construct($input)
    {
        $this->parseBankAccount($input);
    }

    public function parseBankAccount($input)
    {
        if (is_string($input)) {
            return $this->parseBankAccountString($input);
        }
        if (is_array($input)) {
            return $this->parseBankAccountArray($input);
        }

        throw new \InvalidArgumentException();
    }

    protected function parseBankAccountArray($parts)
    {
        $this->bankId = (int)$parts[0];
        $this->bankBranch = (int)$parts[1];
        $this->bankAccount = (int)$parts[2];
        $this->bankAccountSuffix = (int)$parts[3];
    }

    protected function parseBankAccountString($string)
    {
        $parts = preg_split("/[\s-]+/", $string);
        if (count($parts) !== 4) {
            throw new \InvalidArgumentException("Could not break bank account string into exactly four parts.
             Make sure you separate the parts using spaces or minuses");
        }
        $this->parseBankAccountArray($parts);
    }
}
