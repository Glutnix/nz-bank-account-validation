<?php

namespace spec\Glutnix;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BankAccountValidatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Glutnix\BankAccountValidator');
    }

    function it_takes_a_bank_account_string_separated_by_minuses()
    {
        $this->beConstructedWith("01-902-0068389-00");
    }

    function it_takes_a_bank_account_string_separated_by_spaces()
    {
        $this->beConstructedWith("01 902 0068389 00");
    }

    function it_takes_an_optional_constructor()
    {
        $this->beConstructedWith();
    }

    function it_throws_when_given_numbers()
    {
        $this->beConstructedWith(1902006838900);
        $this->shouldThrow('\InvalidArgumentException')->duringInstantiation();
    }

    function it_takes_an_array_of_string_parts()
    {
        $this->beConstructedWith(['01', '902', '0068389', '00']);
    }

    function it_takes_an_array_of_integer_parts()
    {
        $this->beConstructedWith([1, 902, 0068389, 00]);
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

    function it_detects_if_the_bank_id_is_valid()
    {
        $this->beConstructedWith("01-902-0068389-00");
        $this->bankIdIsValid()->shouldBe(true);
    }

    function it_detects_if_the_bank_id_is_invalid()
    {
        $this->beConstructedWith("98-902-0068389-00");
        $this->bankIdIsValid()->shouldBe(false);
    }

    function it_detects_if_the_bank_branch_is_valid()
    {
        $this->beConstructedWith("01-902-0068389-00");
        $this->bankBranchIsValid()->shouldBe(true);
    }

    function it_detects_if_the_bank_branch_is_isvalid()
    {
        $this->beConstructedWith("01-9898-0068389-00");
        $this->bankBranchIsValid()->shouldBe(false);
    }

    function it_detects_if_branch_account_number_is_valid()
    {
        $this->beConstructedWith("01-902-0068389-00");
        $this->bankAccountIsValid()->shouldBe(true);
    }

    function it_detects_if_branch_account_number_is_invalid()
    {
        $this->beConstructedWith("01-902-123456789-00");
        $this->bankAccountIsValid()->shouldBe(false);
    }

    function it_outputs_bank_account_as_parts_array()
    {
        $this->beConstructedWith("01-902-0068389-00");
        $this->getAccountPartsAsStrings()->shouldContain("01");
        $this->getAccountPartsAsStrings()->shouldContain("0902");
        $this->getAccountPartsAsStrings()->shouldContain("00068389");
        $this->getAccountPartsAsStrings()->shouldContain("0000");
    }

    function it_outputs_a_string_of_the_bank_account()
    {
        $this->beConstructedWith("01-902-0068389-00");
        $this->getAccountAsString()->shouldBe("01-0902-00068389-0000");
    }

    function it_outputs_a_string_of_the_bank_account_with_a_custom_seperator()
    {
        $this->beConstructedWith("01-902-0068389-00");
        $this->getAccountAsString(" ")->shouldBe("01 0902 00068389 0000");
    }

    function it_outputs_a_string_of_the_bank_account_with_a_custom_zero_padding()
    {
        $this->beConstructedWith("01-902-0068389-00");
        $this->getAccountAsString("-", 2, 3, 7, 2)->shouldBe("01-902-0068389-00");
    }

    function it_doesnt_truncate_account_numbers_when_low_padding_given()
    {
        $this->beConstructedWith("01-902-0068389-00");
        $this->getAccountAsString("-", 1, 1, 1, 1)->shouldBe("1-902-68389-0");
    }

    function it_outputs_bank_account_as_digit_array()
    {
        $this->beConstructedWith("01-902-0068389-00");
        $this->getAccountAsDigitArray()->shouldBe(str_split("010902000683890000", 1));
    }

    function it_detects_if_whole_account_number_is_valid_anz()
    {
        $this->beConstructedWith("01-902-0068389-00");
        $this->isValid()->shouldBe(true);
    }

    function it_detects_if_whole_account_number_is_invalid_anz()
    {
        $this->beConstructedWith("01-902-0068390-00");
        $this->isValid()->shouldBe(false);
    }

    function it_detects_if_whole_account_number_is_valid_bnz()
    {
        $this->beConstructedWith("08-6523-1954512-001");
        $this->isValid()->shouldBe(true);
    }

    function it_detects_if_whole_account_number_is_invalid_bnz()
    {
        $this->beConstructedWith("08-6523-1954513-001");
        $this->isValid()->shouldBe(false);
    }

    function it_detects_if_whole_account_number_is_valid_g_algorithm()
    {
        $this->beConstructedWith("26 2600 0320871 032");
        $this->isValid()->shouldBe(true);
    }

    function it_detects_if_whole_account_number_is_invalid_g_algorithm()
    {
        $this->beConstructedWith("26 2600 0320870 032");
        $this->isValid()->shouldBe(false);
    }
}
