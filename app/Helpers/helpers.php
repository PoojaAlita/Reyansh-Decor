<?php

function isActiveUrl($url)
{
    if (!$url) return false;

    $url = ltrim($url, '/');

    return request()->is($url) || request()->is($url.'/*');
}
