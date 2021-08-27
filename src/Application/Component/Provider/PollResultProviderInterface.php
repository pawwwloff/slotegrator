<?php


namespace Meals\Application\Component\Provider;


use Meals\Domain\Dish\Dish;
use Meals\Domain\Employee\Employee;
use Meals\Domain\Poll\Poll;
use Meals\Domain\Poll\PollResult;

interface PollResultProviderInterface
{
    public function createPollResult(Poll $poll, Employee $employee, Dish $dish): PollResult;
}