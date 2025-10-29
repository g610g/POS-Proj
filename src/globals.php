<?php

use App\Session;

//this function is intented for retrieving validation errors
function errors(?string $key = null): string | null
{
    $validationErrors = Session::get('validation');
    if (!$key) {
        return $validationErrors;
    }
    return $validationErrors[$key] ?? null;
}

//this function is intended for logging into the console for debugging purposes
function consoleLog(string $data)
{
    fwrite(fopen('php://stderr', 'w'), $data . "\n");
}
function extractPriceString(string $priceString)
{
    $clean = preg_replace('/[₱,]|PHP/i', '', $priceString['price']);

    // Trim and convert to float
    $value = (float) trim($clean);
    return $value ?? 0.0;
}

function encodePrice(float $price): string
{
    $formatter = new NumberFormatter('en_PH', NumberFormatter::CURRENCY);
    return $formatter->formatCurrency($price, 'PHP');
}
