<?php

namespace AppBundle\Services;

class StatusChecker
{
    public function culcProgress($convertingProgress, $fileInfo)
    {
        if ($convertingProgress['progress'] == 'continue') {
            $currentTime = $convertingProgress['out_time_ms']/1000000;
            $totalTime = $fileInfo['duration'];

            return round(($currentTime * 100) / $totalTime);
        } elseif ($convertingProgress['progress'] == 'end') {
            return 100;
        }

        return -1;
    }
}
