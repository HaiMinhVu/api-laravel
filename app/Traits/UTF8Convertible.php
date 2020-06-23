<?php

namespace App\Traits;

trait UTF8Convertible {

    public function utf8Convert($attribute)
    {
        return mb_convert_encoding($this->getAttribute($attribute), 'UTF-8', 'UTF-8');
    }

}
