<?php

namespace App\Helper;

use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;

class UnitHelper
{
    public static function convertTo(float $value, $existingUnit, $convertingUnit): BigDecimal
    {
        $existingUnitIndex = is_int($existingUnit) ? $existingUnit : array_search($existingUnit, config('enums.units'));
        $convertingUnitIndex = is_int($convertingUnit)? $convertingUnit : array_search($convertingUnit, config('enums.units'));

        if ($existingUnitIndex === false || $convertingUnitIndex === false) {
            throw new \Exception("Unsupported Input Unit");
        }
        $value = BigDecimal::of((string)$value); // Converted to string because floating-point numbers are not precise in nature.

        if (config('enums.unit_type_index')[$existingUnitIndex] == config('enums.unit_type_index')[$convertingUnitIndex]) {
            return $value
                ->multipliedBy(config('enums.unit_base_conversion_rate')[$existingUnitIndex])
                ->dividedBy(config('enums.unit_base_conversion_rate')[$convertingUnitIndex], 10, RoundingMode::UP)->toScale(10, RoundingMode::UP);
        } else {
            throw new \Exception();
        }
    }

    public static function getDefaultBaseUnit(string $unit): string
    {
        $unitIndex      = array_search($unit, config('enums.units'));
        $unitTypeIndex  = config('enums.unit_type_index')[$unitIndex];

        return config('enums.default_units')[$unitTypeIndex];
    }

    public static function getDefaultMassUnit(): string
    {
        return config('enums.default_units')[config('enums.default_mass_unit')];
    }

    public static function getDefaultVolumeUnit(): string
    {
        return config('enums.default_units')[config('enums.default_volume_unit')];
    }
}
