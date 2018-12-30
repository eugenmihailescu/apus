<?php
namespace Apus\Utils;

use Cocur\Slugify\Slugify;

class Slugger
{

    /**
     * Transform a post title (e.g.
     * "Hello World") into a slug (e.g. "hello-world")
     *
     * @param string $value
     * @return string
     */
    public static function slugify(string $value): string
    {
        $slugify = new Slugify();

        return $slugify->slugify($value);
    }
}
