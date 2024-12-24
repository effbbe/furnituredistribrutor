<?php

namespace App\Traits;

trait HasRedirectIndex
{
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
