<?php

function isActiveUrl($url)
{
   if (!$url) return false;

    $current = trim(request()->path(), '/');
    $url = trim($url, '/');

    return $current === $url;
}
