<?php

if (!function_exists('rupiah')) {
    function rupiah($value) {
        return 'Rp ' . number_format($value, 0, ',', '.');
    }
}
