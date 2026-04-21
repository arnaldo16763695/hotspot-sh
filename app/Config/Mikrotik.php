<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Mikrotik extends BaseConfig
{
    public bool $verifyTls = false;
    public int $timeoutSeconds = 15;
    public string $limitUptime = '1h';
    public string $bindingCommentPrefix = 'portal-bind';
    public string $schedulerPrefix = 'portal-unbind';
}
