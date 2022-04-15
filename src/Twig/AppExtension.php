<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Twig;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\UsageTrackingTokenStorage;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{

    public function getFunctions(): array
    {
        return [
            new TwigFunction('rename', [$this, 'rename']),
            new TwigFunction('json_decode', [$this, 'jsonDecode']),
            new TwigFunction('convertTime', [$this, 'convertTime']),
            new TwigFunction('array_search', [$this, 'array_search']),
        ];
    }

    public function array_search($a, $b): bool|int|string
    {
        return array_search($a, $b);
    }

    public function jsonDecode($str)
    {
        return json_decode($str);
    }

    public function rename(array $endings, $number, $time): string
    {
        $cases = [2, 0, 1, 1, 1, 2];
        $n = $number;

        return sprintf($endings[($n % 100 > 4 && $n % 100 < 20) ? 2 : $cases[min($n % 10, 5)]], $n, $time);
    }

    public function convertTime($time): string
    {
        $ts = (int) (time() - (int) $time);

        if (date('Y-m-d', $time) === date('Y-m-d', time())) {
            if ($ts < 1) {
                $date = 'тільки що';
            } elseif ($ts < 60) {
                $date = $this->rename(['%d сек', '%d сек', '%d сек'], $ts, '');
            } elseif (60 === $ts) {
                $date = 'мин';
            } elseif ($ts < 3600) {
                $date = $this->rename(['%d хв.', '%d хв.', '%d хв.'], floor($ts / 60), '');
            } elseif (3600 === $ts) {
                $date = 'годину тому';
            } else {
                $date = $this->rename(['%d год. %d хв.', '%d год. %d хв.', '%d год. %d хв.'], floor($ts / 3600), floor($ts / 3600));
            }
        } elseif (date('Y-m-d', $time) === date('Y-m-d', (time() - 84600))) {
            $date = date('вчора у H:i', $time);
        } else {
            $date = date('d.m.Y H:i', $time);
        }
        return $date;
    }
}
