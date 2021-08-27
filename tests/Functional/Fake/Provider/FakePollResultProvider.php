<?php


namespace tests\Meals\Functional\Fake\Provider;


use Meals\Application\Component\Provider\PollResultProviderInterface;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Employee\Employee;
use Meals\Domain\Poll\Poll;
use Meals\Domain\Poll\PollResult;

class FakePollResultProvider implements PollResultProviderInterface
{
    /**
     * @var PollResult
     */
    private $pollResult;

    public function createPollResult(Poll $poll, Employee $employee, Dish $dish): PollResult
    {
        $this->pollResult = new PollResult(1,$poll,$employee,$dish,$employee->getFloor());

        return $this->pollResult;
    }
}