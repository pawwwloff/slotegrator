<?php

namespace tests\Meals\Unit\Application\Component\Validator;

use Meals\Application\Component\Validator\DishInPollDishListValidator;
use Meals\Application\Component\Validator\Exception\DishNotInDishListException;
use Meals\Application\Component\Validator\Exception\PollResultCreateTimeException;
use Meals\Application\Component\Validator\PollResultCreateTimeValidator;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Dish\DishList;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class PollResultCreateTimeValidatorTest extends TestCase
{
    use ProphecyTrait;

    public function testSuccessful()
    {
        $validator = new PollResultCreateTimeValidator();
        verify($validator->validate($this->getAllowTime()))->null();
    }

    public function testFail()
    {
        $this->expectException(PollResultCreateTimeException::class);
        $validator = new PollResultCreateTimeValidator();
        $validator->validate($this->getDenidTime());
    }

    public function testFailDay()
    {
        $this->expectException(PollResultCreateTimeException::class);
        $validator = new PollResultCreateTimeValidator();
        $validator->validate($this->getDayDenidTime());
    }

    private function getAllowTime(){
        return strtotime("2021-08-23 07:00:00");
    }

    private function getDenidTime(){
        return strtotime("2021-08-23 05:00:00");
    }

    private function getDayDenidTime(){
        return strtotime("2021-08-22 07:00:00");
    }
}
