<?php declare(strict_types=1);



/**
 * @param int $v
 * @return float|string
 */
function binary32(int $v) : float
{
    assert($v >= 0, 'Value must not be negative');
    assert($v <= 0xFFFFFFFF, 'Value must be 4-byte');

    $sign = ($v & 0x80000000) > 0 ? -1 : 1;
    $exp = (($v & 0x7F800000) >> 23);
    $mantis = ($v & 0x7FFFFF);

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


$hex = $_GET['v'] ?? null;
if ($hex === null) die("Use get parameter 'v' to pass 4-byte hex value. Example: <a href='/?v=447A0000'>447A0000</a>");
assert(preg_match('/[0-9a-fA-F]{8}/', $hex), 'Parameter v must be valid hex value with 4 bytes');

print binary32(hexdec($hex));