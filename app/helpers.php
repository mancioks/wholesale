<?php

use App\Models\Setting;

if (!function_exists('price_format')) {
    function price_format($price)
    {
        return number_format((float)$price, 2, '.', '');
    }
}

if (!function_exists('price_with_markup')) {
    function price_with_markup($price, $markup)
    {
        if (!(int)$markup) {
            $markup = 0;
        }

        return price_format($price * (1 + $markup / 100));
    }
}

if (!function_exists('price_with_pvm')) {
    function price_with_pvm($price)
    {
        return price_format($price * (1 + Setting::get('pvm') / 100));
    }
}

function amountToLtWords($skaicius)
{
    if ($skaicius < 0 || strlen($skaicius) > 9) return null;

    if ($skaicius == 0) return 'nulis';

    $vienetai = ['', 'vienas', 'du', 'trys', 'keturi', 'penki', 'šeši', 'septyni', 'aštuoni', 'devyni'];
    $niolikai = ['', 'vienuolika', 'dvylika', 'trylika', 'keturiolika', 'penkiolika', 'šešiolika', 'septyniolika', 'aštuoniolika', 'devyniolika'];
    $desimtys = ['', 'dešimt', 'dvidešimt', 'trisdešimt', 'keturiasdešimt', 'penkiasdešimt', 'šešiasdešimt', 'septyniasdešimt', 'aštuoniasdešimt', 'devyniasdešimt'];

    $pavadinimas = [
        ['milijonas', 'milijonai', 'milijonų'],
        ['tūkstantis', 'tūkstančiai', 'tūkstančių'],
    ];

    $skaicius = sprintf('%09d', $skaicius); // iki milijardu 10^9 (milijardu neskaiciuosim)
    $skaicius = str_split($skaicius, 3); // kertam kas tris simbolius

    $zodziais = [];

    foreach ($skaicius as $i => $tripletas) {
        $linksnis = 0;

        if ($tripletas[0] > 0) {
            $zodziais[] = $vienetai[$tripletas[0]];
            $zodziais[] = ($tripletas[0] > 1) ? 'šimtai' : 'šimtas';
        }

        $du = substr($tripletas, 1);

        if ($du > 10 && $du < 20) {
            $zodziais[] = $niolikai[$du[1]];
            $linksnis = 2;
        } else {

            if ($du[0] > 0) {
                $zodziais[] = $desimtys[$du[0]];
            }

            if ($du[1] > 0) {
                $zodziais[] = $vienetai[$du[1]];
                $linksnis = ($du[1] > 1) ? 1 : 0;
            } else {
                $linksnis = 2;
            }

        }

        if ($i < count($pavadinimas) && $tripletas != '000') {
            $zodziais[] = $pavadinimas[$i][$linksnis];
        }

    }

    return implode(' ', $zodziais);
}

if (!function_exists('setting')) {
    function setting($key)
    {
        return \App\Models\Setting::get($key);
    }
}

if (!function_exists('check_date_valid')) {
    function check_date_valid($date, $format) {
        return (date($format, strtotime($date)) == $date);
    }
}
