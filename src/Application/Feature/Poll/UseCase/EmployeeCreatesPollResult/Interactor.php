<?php

namespace Meals\Application\Feature\Poll\UseCase\EmployeeCreatesPollResult;

use Meals\Application\Component\Provider\DishProviderInterface;
use Meals\Application\Component\Provider\EmployeeProviderInterface;
use Meals\Application\Component\Provider\PollProviderInterface;
use Meals\Application\Component\Provider\PollResultProviderInterface;
use Meals\Application\Component\Validator\DishInPollDishListValidator;
use Meals\Application\Component\Validator\PollIsActiveValidator;
use Meals\Application\Component\Validator\PollResultCreateTimeValidator;
use Meals\Application\Component\Validator\UserHasAccessToViewPollsValidator;
use Meals\Domain\Poll\PollResult;

class Interactor
{
    /** @var EmployeeProviderInterface */
    private $employeeProvider;

    /** @var PollProviderInterface */
    private $pollProvider;

    /** @var UserHasAccessToViewPollsValidator */
    private $userHasAccessToPollsValidator;

    /** @var PollIsActiveValidator */
    private $pollIsActiveValidator;
    /**
     * @var PollResultCreateTimeValidator
     */
    private $pollResultCreateTimeValidator;
    /**
     * @var DishInPollDishListValidator
     */
    private $dishInPollDishListValidator;
    /**
     * @var PollResultProviderInterface
     */
    private $pollResultProvider;
    /**
     * @var DishProviderInterface
     */
    private $dishProvider;

    /**
     * Interactor constructor.
     * @param EmployeeProviderInterface $employeeProvider
     * @param PollProviderInterface $pollProvider
     * @param UserHasAccessToViewPollsValidator $userHasAccessToPollsValidator
     * @param PollIsActiveValidator $pollIsActiveValidator
     */
    public function __construct(
        EmployeeProviderInterface $employeeProvider,
        PollResultProviderInterface $pollResultProvider,
        PollProviderInterface $pollProvider,
        DishProviderInterface $dishProvider,
        UserHasAccessToViewPollsValidator $userHasAccessToPollsValidator,
        PollIsActiveValidator $pollIsActiveValidator,
        PollResultCreateTimeValidator $pollResultCreateTimeValidator,
        DishInPollDishListValidator $dishInPollDishListValidator
    ) {
        $this->employeeProvider = $employeeProvider;
        $this->pollProvider = $pollProvider;
        $this->dishProvider = $dishProvider;
        $this->userHasAccessToPollsValidator = $userHasAccessToPollsValidator;
        $this->pollIsActiveValidator = $pollIsActiveValidator;
        $this->pollResultCreateTimeValidator = $pollResultCreateTimeValidator;
        $this->dishInPollDishListValidator = $dishInPollDishListValidator;
        $this->pollResultProvider = $pollResultProvider;
    }

    public function createPollResult(int $pollId, int $employeeId, int $dishId, int $timestamp = null): PollResult
    {
        $timestamp = $timestamp ?? time();
        $employee = $this->employeeProvider->getEmployee($employeeId);
        $poll = $this->pollProvider->getPoll($pollId);
        $dish = $this->dishProvider->getDish($dishId);
        $pollMenu = $poll->getMenu();
        $dishList = $pollMenu->getDishes();

        $this->userHasAccessToPollsValidator->validate($employee->getUser());
        $this->pollIsActiveValidator->validate($poll);

        $this->dishInPollDishListValidator->validate($dishList,$dish);
        $this->pollResultCreateTimeValidator->validate($timestamp);

        return $this->pollResultProvider->createPollResult($poll, $employee, $dish);
    }
}
