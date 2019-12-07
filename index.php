<?php declare(strict_types=1);


function ieee754(int $v): float
{
    assert($v >= 0, 'Value must not be negative');
    assert($v <= 0xFFFFFFFF, 'Value must be 4-byte');

    $sign = ($v & 0x80000000) > 0 ? -1 : 1;
    $exp = (($v & 0x7F800000) >> 23) - 127;
    $mantis = ($v & 0x7FFFFF);
    if ($mantis == 0 && $exp == -127) {
        return 0;
    }

    $mantis |= 0x800000;
    $pos = 0x800000;

    $res = .0;
    while ($mantis > 0) {

        if ($mantis & $pos) {
            $res += pow(2, $exp);
            $mantis -= $pos;
        }

        $pos >>= 1;
        $exp--;
    }

    return $sign * $res;
}

$hex = $_GET['v'] ?? null;
if ($hex === null) die("Use get parameter v to calc the value: <a href='/?v=447A0000'>447A0000</a>");


