<?php

/**
 * Debugging helper function, inpsired by laravel. Halts execution.
 *
 * @param mixed $var Var to dump
 */
function dd($var){
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    die();
}