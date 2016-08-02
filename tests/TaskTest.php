<?php

use bashkarev\r01\Task;

class TaskTest extends PHPUnit_Framework_TestCase
{

    public function testException()
    {
        $this->setExpectedException(
            'bashkarev\r01\soap\Exception',
            'No task with ID = 1 found on this agreement for the last 7 days.',
            (new Task(['taskid' => '1']))->check()
        );
    }

}