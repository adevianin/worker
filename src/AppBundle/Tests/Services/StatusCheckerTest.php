<?php

namespace AppBundle\Tests\Services;

use AppBundle\Services\StatusChecker;

class StatusCheckerTest extends \PHPUnit_Framework_TestCase
{
    public function testCulcProgressError()
    {
        $convertingProgress = ['progress' => 'not continue not end'];
        $fileInfo = [];
        $checker = new StatusChecker();

        $status = $checker->culcProgress($convertingProgress, $fileInfo);

        $this->assertEquals(-1, $status);
    }

    public function testCulcProgressFinished()
    {
        $convertingProgress = ['progress' => 'end'];
        $fileInfo = [];
        $checker = new StatusChecker();

        $status = $checker->culcProgress($convertingProgress, $fileInfo);

        $this->assertEquals(100, $status);
    }

    public function testCulcProgressCalculating()
    {
        $convertingProgress = ['progress' => 'continue', 'out_time_ms' => 86000000];
        $fileInfo = ['duration' => 100];
        $checker = new StatusChecker();

        $status = $checker->culcProgress($convertingProgress, $fileInfo);

        $this->assertEquals(86, $status);
    }
}
