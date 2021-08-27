<?php


namespace Meals\Application\Component\Validator;


use Meals\Application\Component\Validator\Exception\DishNotInDishListException;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Poll\Poll;

class DishInPollDishListValidator
{
    public function validate(Poll $poll, Dish $dish): void
    {
        $pollMenu = $poll->getMenu();
        $dishList = $pollMenu->getDishes();
        if (!$dishList->hasDish($dish)) {
            throw new DishNotInDishListException();
        }
    }
}