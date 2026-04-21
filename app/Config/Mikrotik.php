<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Mikrotik extends BaseConfig
{
    public bool $verifyTls = false;
    public int $timeoutSeconds = 15;
    public string $hotspotUserPrefix = 'hs';
    public string $hotspotUserProfile = 'default';
    public string $limitUptime = '1h';
}
