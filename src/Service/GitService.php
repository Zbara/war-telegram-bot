<?php

namespace App\Service;

class GitService
{
    public const MAJOR = 1;

    public function getBuild(): string
    {
        $rev = mb_str_split(substr_count(shell_exec('cat ../.git/logs/HEAD'), "\n"));

        return sprintf('v%s.%s build %s', self::MAJOR, implode('.', $rev), date('Y/m/d/h-m-s', $this->gitDate()));
    }

    public function gitDate(): bool|int
    {
        $branch = trim(substr(shell_exec('cat ../.git/HEAD'), 4));

        return filemtime(sprintf( '../.git/%s', $branch ));
    }
}
