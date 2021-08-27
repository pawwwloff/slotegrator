<?php

namespace tests\Meals\Unit\Application\Component\Validator;

use Meals\Application\Component\Validator\DishInPollDishListValidator;
use Meals\Application\Component\Validator\Exception\DishNotInDishListException;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Dish\DishList;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class DishInPollDishListValidatorTest extends TestCase
{
    use ProphecyTrait;

    public function testSuccessful()
    {
        $dish = $this->prophesize(Dish::class);

        $dishList = $this->prophesize(DishList::class);
        $dishList->hasDish($dish)->willReturn(true);

        $validator = new DishInPollDishListValidator();
        verify($validator->validate($dishList->reveal(),$dish->reveal()))->null();
    }

    public function testFail()
    {
        $this->expectException(DishNotInDishListException::class);

        $dish = $this->prophesize(Dish::class);

        $dishList = $this->prophesize(DishList::class);
        $dishList->hasDish($dish)->willReturn(false);

        $validator = new DishInPollDishListValidator();
        $validator->validate($dishList->reveal(),$dish->reveal());
    }
}
