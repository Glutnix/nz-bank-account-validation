<?php

namespace Glutnix;

class BankAccountValidator
{

    public $bankId = 0;
    public $bankBranch = 0;
    public $bankAccount = 0;
    public $bankAccountSuffix = 0;

    private $bankData = [];
    private $algorithms = [];

    public function __construct($input = null)
    {
        $this->initializeBankData();
        $this->initializeChecksumAlgorithms();
        $this->parseBankAccount($input);
    }

    private function initializeBankData()
    {
        $this->bankData = [
            // Data from pages 10 and 11 of
            // https://www.ird.govt.nz/resources/d/8/d8e49dce-1bda-4875-8acf-9ebf908c6e17/rwt-nrwt-spec-2014.pdf
            1 =>    ['algorithm' => 'AB', 'branches' => [[1, 999], [1100, 1199], [1800, 1899]]],
            2 =>    ['algorithm' => 'AB', 'branches' => [[1, 999], [1200, 1299]]],
            3 =>    ['algorithm' => 'AB',
                     'branches' => [[1, 999], [1300, 1399], [1500, 1599], [1700, 1799], [1900, 1999]]],
            6 =>    ['algorithm' => 'AB', 'branches' => [[1, 999], [1400, 1499]]],
            8 =>    ['algorithm' => 'D',  'branches' => [[6500, 6599]]],
            9 =>    ['algorithm' => 'E',  'branches' => [[0, 0]]],
            11 =>   ['algorithm' => 'AB', 'branches' => [[5000, 6499], [6600,8999]]],
            12 =>   ['algorithm' => 'AB', 'branches' => [[3000, 3299], [3400, 3499], [3600, 3699]]],
            13 =>   ['algorithm' => 'AB', 'branches' => [[4900, 4999]]],
            14 =>   ['algorithm' => 'AB', 'branches' => [[4700, 4799]]],
            15 =>   ['algorithm' => 'AB', 'branches' => [[3900, 3999]]],
            16 =>   ['algorithm' => 'AB', 'branches' => [[4400, 4499]]],
            17 =>   ['algorithm' => 'AB', 'branches' => [[3300, 3399]]],
            18 =>   ['algorithm' => 'AB', 'branches' => [[3500, 3599]]],
            19 =>   ['algorithm' => 'AB', 'branches' => [[4600, 4649]]],
            20 =>   ['algorithm' => 'AB', 'branches' => [[4100, 4199]]],
            21 =>   ['algorithm' => 'AB', 'branches' => [[4800, 4899]]],
            22 =>   ['algorithm' => 'AB', 'branches' => [[4000, 4049]]],
            23 =>   ['algorithm' => 'AB', 'branches' => [[3700, 3799]]],
            24 =>   ['algorithm' => 'AB', 'branches' => [[4300, 4349]]],
            25 =>   ['algorithm' => 'F',  'branches' => [[2500, 2599]]],
            26 =>   ['algorithm' => 'G',  'branches' => [[2600, 2699]]],
            27 =>   ['algorithm' => 'AB', 'branches' => [[3800, 3849]]],
            28 =>   ['algorithm' => 'G',  'branches' => [[2100, 2149]]],
            29 =>   ['algorithm' => 'G',  'branches' => [[2150, 2299]]],
            30 =>   ['algorithm' => 'AB', 'branches' => [[2900, 2949]]],
            31 =>   ['algorithm' => 'X',  'branches' => [[2800, 2849]]],
            33 =>   ['algorithm' => 'F',  'branches' => [[6700, 6799]]],
            35 =>   ['algorithm' => 'AB', 'branches' => [[2400, 2499]]],
            38 =>   ['algorithm' => 'AB', 'branches' => [[9000, 9499]]],
        ];
    }

    private function initializeChecksumAlgorithms()
    {
        $this->algorithms = [
            'A' => ['weightDigits' => [0, 0,  6, 3, 7, 9,  0, 0, 10, 5, 8, 4, 2, 1,  0, 0, 0, 0], 'modulo' => 11],
            'B' => ['weightDigits' => [0, 0,  0, 0, 0, 0,  0, 0, 10, 5, 8, 4, 2, 1,  0, 0, 0, 0], 'modulo' => 11],
            'C' => ['weightDigits' => [3, 7,  0, 0, 0, 0,  9, 1, 10, 5, 3, 4, 2, 1,  0, 0, 0, 0], 'modulo' => 11],
            'D' => ['weightDigits' => [0, 0,  0, 0, 0, 0,  0, 7, 6,  5, 4, 3, 2, 1,  0, 0, 0, 0], 'modulo' => 11],
            'E' => ['weightDigits' => [0, 0,  0, 0, 0, 0,  0, 0, 0,  0, 5, 4, 3, 2,  0, 0, 0, 1], 'modulo' => 11],
            'F' => ['weightDigits' => [0, 0,  0, 0, 0, 0,  0, 1, 7,  3, 1, 7, 3, 1,  0, 0, 0, 0], 'modulo' => 10],
            'G' => ['weightDigits' => [0, 0,  0, 0, 0, 0,  0, 1, 3,  7, 1, 3, 7, 1,  0, 3, 7, 1], 'modulo' => 10],
            'X' => ['weightDigits' => [0, 0,  0, 0, 0, 0,  0, 0, 0,  0, 0, 0, 0, 0,  0, 0, 0, 0], 'modulo' => 1],
        ];
    }

    public function parseBankAccount($input)
    {
        if (is_string($input)) {
            return $this->parseBankAccountString($input);
        }
        if (is_array($input)) {
            return $this->parseBankAccountArray($input);
        }
        if (! is_null($input)) {
            throw new \InvalidArgumentException("Expected a string, or an array of either four strings or integers.");
        }
    }

    private function parseBankAccountArray($parts)
    {
        $this->bankId = (int)$parts[0];
        $this->bankBranch = (int)$parts[1];
        $this->bankAccount = (int)$parts[2];
        $this->bankAccountSuffix = (int)$parts[3];
    }

    private function parseBankAccountString($string)
    {
        $parts = preg_split("/[\s-]+/", $string);
        if (count($parts) !== 4) {
            throw new \InvalidArgumentException("Could not break bank account string into exactly four parts.
             Make sure you separate the parts using spaces or minuses");
        }
        $this->parseBankAccountArray($parts);
    }

    public function bankIdIsValid()
    {
        return array_key_exists($this->bankId, $this->bankData);
    }

    public function bankBranchIsValid()
    {
        if (! $this->bankIdIsValid()) {
            return false;
        }
        $branches = $this->getValidBankBranches();
        foreach ($branches as $branchrange) {
            if ($this->bankBranch >= $branchrange[0] && $this->bankBranch <= $branchrange[1]) {
                return true;
            }
        }
        return false;
    }

    public function bankAccountIsValid()
    {
        return ($this->bankAccount > 0 && $this->bankAccount <= 99999999);
    }

    private function getValidBankBranches()
    {
        return $this->bankData[$this->bankId]['branches'];
    }

    public function isValid()
    {
        if (! $this->bankIdIsValid()) {
            return false;
        }
        if (! $this->bankBranchIsValid()) {
            return false;
        }
        return $this->checksumIsValid();
    }

    private function getBankIdAlgorithm()
    {
        $code = $this->bankData[$this->bankId]['algorithm'];
        if ($code == "AB") {
            if ($this->bankAccount < 990000) {
                return 'A';
            } else {
                return 'B';
            }
        }
        return $code;
    }

    private function getBankIdWeightDigits()
    {
        return $this->algorithms[$this->getBankIdAlgorithm()];
    }

    private function checksumIsValid()
    {
        $account = $this->getAccountAsDigitArray();
        $algorithm = $this->getBankIdWeightDigits();
        $sum = 0;
        foreach ($account as $index => $digit) {
            $sum += ($account[$index] * $algorithm['weightDigits'][$index]);
        }
        return $sum % $algorithm['modulo'] === 0;
    }

    public function getAccountAsDigitArray()
    {
        extract($this->getAccountPartsAsStrings());
        $digits = $bankId . $bankBranch . $bankAccount . $bankAccountSuffix;
        return str_split($digits, 1);
    }

    public function getAccountAsString(
        $seperator = "-",
        $bankIdPadding = 2,
        $bankBranchPadding = 4,
        $bankAccountPadding = 8,
        $bankAccountSuffix = 4
    ) {
        extract($this->getAccountPartsAsStrings(
            $bankIdPadding,
            $bankBranchPadding,
            $bankAccountPadding,
            $bankAccountSuffix
        ));
        $account = $bankId . $seperator
                . $bankBranch . $seperator
                . $bankAccount . $seperator
                . $bankAccountSuffix;
        return $account;
    }

    public function getAccountPartsAsStrings(
        $bankIdPadding = 2,
        $bankBranchPadding = 4,
        $bankAccountPadding = 8,
        $bankAccountSuffixPadding = 4
    ) {
        $bankIdPadding =            min($bankIdPadding, 2);
        $bankBranchPadding =        min($bankBranchPadding, 4);
        $bankAccountPadding =       min($bankAccountPadding, 8);
        $bankAccountSuffixPadding = min($bankAccountSuffixPadding, 4);

        $bankId =               sprintf("%'.0" . $bankIdPadding . "d", $this->bankId);
        $bankBranch =           sprintf("%'.0" . $bankBranchPadding . "d", $this->bankBranch);
        $bankAccount =          sprintf("%'.0" . $bankAccountPadding . "d", $this->bankAccount);
        $bankAccountSuffix =    sprintf("%'.0" . $bankAccountSuffixPadding . "d", $this->bankAccountSuffix);
        return compact('bankId', 'bankBranch', 'bankAccount', 'bankAccountSuffix');
    }
}
