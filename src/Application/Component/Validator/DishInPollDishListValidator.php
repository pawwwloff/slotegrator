<?php


namespace Meals\Application\Component\Validator;


use Meals\Application\Component\Validator\Exception\DishNotInDishListException;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Dish\DishList;
use Meals\Domain\Poll\Poll;

class DishInPollDishListValidator
{
    public function validate(DishList $dishList, Dish $dish): void
    {
        if (!$dishList->hasDish($dish)) {
            throw new DishNotInDishListException();
        }
    }
}