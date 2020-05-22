<?php

namespace App\Services;

class Saver
{

    public function save($content)
    {
        file_put_contents(SAVE_TO, $content);
    }
}