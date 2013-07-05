<?php

namespace Kraken\Managers\Services;


abstract class BaseService {

    protected $logger;

    protected $enable;

    /**
     * Check if the service is available
     */
    public function isAvailable()
    {
        return $this->enable;
    }

}