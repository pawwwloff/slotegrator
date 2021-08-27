<?php


namespace Meals\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\PollResultCreateTimeException;

class PollResultCreateTimeValidator
{
    public function validate($timestamp): void
    {
        $day = (int)date('w',$timestamp);
        $hour = (int)date('Gi',$timestamp);
        if ($day !== 1 || $hour>2200 || $hour < 600) {
            throw new PollResultCreateTimeException();
        }
    }
}