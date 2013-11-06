<?php

namespace cipxml;

spl_autoload_register(function ($class_name) {
    include dirname(__DIR__) . '/' . str_replace('\\', '/', $class_name) . '.php';
});

