<?php

namespace App\Utils;

use App\Dto\Inputs\ForecastFilterDto;

final class UtilHash
{
    public static function generateHashForHistory(ForecastFilterDto $dto): string
    {
        return md5(implode(',', [
            $dto->getLatitude(),
            $dto->getLongitude(),
            $dto->getCityName() ?? '',
            $dto->isHourly() ? '1' : '0',
            $dto->isWeatherCode() ? '1' : '0',
            $dto->isWindSpeed10m() ? '1' : '0',
            $dto->getWindSpeedUnit(),
        ]));
    }
}
