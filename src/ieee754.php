<?php declare(strict_types=1);

namespace Antohaby\Ieee754;

/**
 * Decodes binary in IEEE 754 Single-precision floating point format
 * to float number
 *
 * @example binary32Decode(hexdec('40490fdb')) // 3.14159274101
 *
 * @param int $bin
 * @return float
 */
function binary32Decode(int $bin) : float
{
    assert($bin >= 0, 'Value must not be negative');
    assert($bin <= 0xFFFFFFFF, 'Value must be 4-byte');

    $sign = ($bin & 0x80000000) > 0 ? -1 : 1;
    $exp = (($bin & 0x7F800000) >> 23);
    $mantis = ($bin & 0x7FFFFF);

    if ($mantis == 0 && $exp == 0) {
        return 0;
    }
    if ($exp == 255) {
        if ($mantis == 0) return INF;
        if ($mantis != 0) return NAN;
    }

    if ($exp == 0) {
        $mantis /= 0x800000;
        return $sign * pow(2, -126) * $mantis;
    } else {
        $mantis |= 0x800000;
        $mantis /= 0x800000;
        return $sign * pow(2,$exp - 127) * $mantis;
    }

}