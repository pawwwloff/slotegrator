<?php


namespace tests\Meals\Functional\Interactor;


use Meals\Application\Component\Validator\Exception\AccessDeniedException;
use Meals\Application\Component\Validator\Exception\DishNotInDishListException;
use Meals\Application\Component\Validator\Exception\PollIsNotActiveException;
use Meals\Application\Component\Validator\Exception\PollResultCreateTimeException;
use Meals\Application\Feature\Poll\UseCase\EmployeeCreatesPollResult\Interactor;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Dish\DishList;
use Meals\Domain\Employee\Employee;
use Meals\Domain\Menu\Menu;
use Meals\Domain\Poll\Poll;
use Meals\Domain\Poll\PollResult;
use Meals\Domain\User\Permission\Permission;
use Meals\Domain\User\Permission\PermissionList;
use Meals\Domain\User\User;
use tests\Meals\Functional\Fake\Provider\FakeDishProvider;
use tests\Meals\Functional\Fake\Provider\FakeEmployeeProvider;
use tests\Meals\Functional\Fake\Provider\FakePollProvider;
use tests\Meals\Functional\FunctionalTestCase;

class EmployeeCreatesPollResult extends FunctionalTestCase
{

    public function testSuccessful()
    {
        $poll = $this->getPoll(true);
        $employee = $this->getEmployeeWithPermissions();
        $dish = $this->getDish();
        $pollResult = $this->performTestMethod($poll,$employee,$dish, $this->getAllowTime());
        verify($pollResult)->equals($pollResult);
    }

    public function testDishNotInDishListError()
    {
        $this->expectException(DishNotInDishListException::class);
        $poll = $this->getPoll(true);
        $employee = $this->getEmployeeWithPermissions();
        $dish = $this->getDishNotInDishList();
        $pollResult = $this->performTestMethod($poll,$employee,$dish, $this->getAllowTime());
        verify($pollResult)->equals($pollResult);
    }

    public function testPollResultCreateTimeError()
    {
        $this->expectException(PollResultCreateTimeException::class);
        $poll = $this->getPoll(true);
        $employee = $this->getEmployeeWithPermissions();
        $dish = $this->getDish();
        $pollResult = $this->performTestMethod($poll,$employee,$dish, $this->getDenidTime());
        verify($pollResult)->equals($pollResult);
    }

    public function testUserHasNotPermissions()
    {
        $this->expectException(AccessDeniedException::class);
        $poll = $this->getPoll(true);
        $employee = $this->getEmployeeWithNoPermissions();
        $dish = $this->getDish();
        $pollResult = $this->performTestMethod($poll,$employee,$dish, $this->getAllowTime());
        verify($pollResult)->equals($pollResult);
    }

    public function testPollIsNotActive()
    {
        $this->expectException(PollIsNotActiveException::class);
        $poll = $this->getPoll(false);
        $employee = $this->getEmployeeWithPermissions();
        $dish = $this->getDish();
        $pollResult = $this->performTestMethod($poll,$employee,$dish, $this->getDenidTime());
        verify($pollResult)->equals($pollResult);
    }

    private function performTestMethod(Poll $poll, Employee $employee, Dish $dish, int $timestamp): PollResult
    {
        $this->getContainer()->get(FakeEmployeeProvider::class)->setEmployee($employee);
        $this->getContainer()->get(FakePollProvider::class)->setPoll($poll);
        $this->getContainer()->get(FakeDishProvider::class)->setDish($dish);

        return $this->getContainer()
            ->get(Interactor::class)
            ->createPollResult(
                $poll->getId(),
                $employee->getId(),
                $dish->getId(),
                $timestamp
            );
    }

    private function getEmployeeWithPermissions(): Employee
    {
        return new Employee(
            1,
            $this->getUserWithPermissions(),
            4,
            'Surname'
        );
    }

    private function getUserWithPermissions(): User
    {
        return new User(
            1,
            new PermissionList(
                [
                    new Permission(Permission::VIEW_ACTIVE_POLLS),
                ]
            ),
        );
    }

    private function getEmployeeWithNoPermissions(): Employee
    {
        return new Employee(
            1,
            $this->getUserWithNoPermissions(),
            4,
            'Surname'
        );
    }

    private function getUserWithNoPermissions(): User
    {
        return new User(
            1,
            new PermissionList([]),
        );
    }

    private function getPoll(bool $active): Poll
    {
        return new Poll(
            1,
            $active,
            new Menu(
                1,
                'title',
                new DishList([new Dish(
                    1,
                    'dish',
                    'description'
                )]),
            )
        );
    }

    private function getDish(): Dish
    {
        return new Dish(
            1,
            'dish',
            'description'
        );
    }

    private function getDishNotInDishList(): Dish
    {
        return new Dish(
            2,
            'dish2',
            'description'
        );
    }

    private function getAllowTime(){
        return strtotime("2021-08-23 07:00:00");
    }

    private function getDenidTime(){
        return strtotime("2021-08-23 05:00:00");
    }
}