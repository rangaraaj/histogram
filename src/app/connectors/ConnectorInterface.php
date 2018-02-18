<?php

namespace app\connectors;

/**
 * Interface ConnectorInterface
 *
 * @package app\connectors
 */
interface ConnectorInterface
{
    public function init($params);
    public function getData($username);
}