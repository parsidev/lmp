<?php

namespace Parsidev\Permission\Exceptions;

/**
 * Class UnauthorizedRole
 * @package Parsidev\Permission\Exceptions
 */
class UnauthorizedRole extends UnauthorizedException
{
    /**
     * UnauthorizedPermission constructor.
     *
     * @param $statusCode
     * @param string $message
     * @param array $requiredRoles
     */
    public function __construct($statusCode, string $message = null, array $requiredRoles = [])
    {
        parent::__construct($statusCode, $message, $requiredRoles);
    }
}
