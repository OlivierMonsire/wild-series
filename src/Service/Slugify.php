<?php

namespace App\Service;

class Slugify
{

    public function generate(string $input): string
    {
        $slug = strtolower($input);
        $slug = str_replace(array(' ', 'à', 'ç'), array('-', 'a', 'c'), $slug);
        $slug = preg_replace("/[^a-z-]/", "", $slug);
        $slug = preg_replace("/-{2,}/", "-", $slug);
        if ($slug[0] === '-') {
            $slug[0] = ' ';
        }
        if ($slug[strlen($slug) - 1] === '-') {
            $slug[strlen($slug) - 1] = ' ';
        }
        $slug = trim($slug);
        return $slug;
    }

}
