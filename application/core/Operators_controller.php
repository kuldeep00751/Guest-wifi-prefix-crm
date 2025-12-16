<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @deprecated 2.3.2
 * Use OperatorsController instead
 */
class Operators_controller extends OperatorsController
{
    public function __construct()
    {
        parent::__construct();
        _deprecated_function('Operators_controller', '2.3.2', 'OperatorsController');
    }
}
