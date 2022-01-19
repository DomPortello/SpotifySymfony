<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TimeConverterExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('get_duration_in_minutes', [$this, 'getDurationInMinutes']),
        ];
    }

    public function getDurationInMinutes(int $time): string
    {
        $secondes = $time % 60;
        $time = ($time - $secondes)/60;

        $minutes = $time % 60;
        $time = ($time - $minutes) / 60;
        $hours = $time % 24;

        if ($minutes < 10) {
            $minutes = '0' . $minutes;
        }
        if($hours < 10) {
            $hours = '0' . $hours;
        }

        if ($hours > 0){
            return $hours. 'h' . $minutes . 'm' . $secondes . 's';
        }else
            return $minutes . 'm' . $secondes . 's';
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('function_name', [$this, 'doSomething']),
        ];
    }



    public function doSomething($value)
    {
        // ...
    }
}
