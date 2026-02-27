<?php

namespace App\Utils;

use App\Dto\Inputs\ForecastFilterDto;
use App\Entity\User;

final class UtilHash
{
    public static function generateHashForHistory(User $user, ForecastFilterDto $dto): string
    {
        return md5(implode(',', [
            $user->getId(),
            $dto->getLatitude(),
            $dto->getLongitude(),
            $dto->isHourly() ? '1' : '0',
            $dto->isWeatherCode() ? '1' : '0',
            $dto->isWindSpeed10m() ? '1' : '0',
            $dto->getWindSpeedUnit(),
        ]));
    }
}
