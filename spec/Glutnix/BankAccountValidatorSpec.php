<?php

namespace spec\Glutnix;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BankAccountValidatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith("01-902-0068389-00");
        $this->shouldHaveType('Glutnix\BankAccountValidator');
    }

    function it_takes_a_bank_account_string()
    {
        $this->beConstructedWith("01-902-0068389-00");
    }

    function it_throws_when_given_numbers()
    {
        $this->beConstructedWith(1902006838900);
    }

    function it_takes_an_array_of_string_parts()
    {
        $this->beConstructedWith(['01', '902', '0068389', '00']);
    }

    function it_takes_an_array_of_integer_parts()
    {
        $this->beConstructedWith(['01', '902', '0068389', '00']);
    }

    function it_parses_input_string_into_parts()
    {
        $this->beConstructedWith("01-902-0068389-00");
        $this->bankId->shouldBe(1);
        $this->bankBranch->shouldBe(902);
        $this->bankAccount->shouldBe(68389);
        $this->bankAccountSuffix->shouldBe(0);
    }

    function it_parses_input_string_array_into_parts()
    {
        $this->beConstructedWith(["01","902","0068389","00"]);
        $this->bankId->shouldBe(1);
        $this->bankBranch->shouldBe(902);
        $this->bankAccount->shouldBe(68389);
        $this->bankAccountSuffix->shouldBe(0);
    }

    function it_parses_input_integer_array_into_parts()
    {
        $this->beConstructedWith([1, 902, 68389, 0]);
        $this->bankId->shouldBe(1);
        $this->bankBranch->shouldBe(902);
        $this->bankAccount->shouldBe(68389);
        $this->bankAccountSuffix->shouldBe(0);
    }
}
