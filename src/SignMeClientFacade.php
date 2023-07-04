<?php

namespace Maxtereshin\SignMeClient;

use Illuminate\Support\Facades\Facade;

class SignMeClientFacade extends Facade
{

    protected static function getFacadeAccessor(): string
    {
        return Client::class;
    }

}
