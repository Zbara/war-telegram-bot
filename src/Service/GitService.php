<?php

namespace App\Service;

class GitService
{
    public const MAJOR = 1;

    public function getBuild(): string
    {
        $rev = mb_str_split(substr_count(shell_exec('cat ../.git/logs/HEAD'), "\n"));

        return sprintf('v%s.%s build %s', self::MAJOR, implode('.', $rev), date('Y/m/d-h:m', $this->gitDate()));
    }

    public function gitDate(): string
    {
        $date = explode(' ', exec('cat ../.git/logs/HEAD'));

        if (isset($date[4])) {
            return $date[4];
        }
        return time();
    }
}
