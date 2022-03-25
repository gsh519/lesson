<?php

abstract class BaseValidator
{
    public $valid = true;
    public $errors = [];

    abstract function validate($value, $bool);
}
