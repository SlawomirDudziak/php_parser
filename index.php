<?php

include('simplehtmldom/simple_html_dom.php');

$html = file_get_html('wo_for_parse.html');

function html_find($element, $html)
{
    return $html->find($element, 0)->plaintext;
}

$tracking_number = html_find('#wo_number', $html);
$po_number = html_find('#po_number', $html);

$date_raw = html_find('#scheduled_date', $html);
$date_replaced = preg_replace('/\s+/', ',', $date_raw);
$date_replaced2 = preg_replace('/,/', ' ', $date_replaced);
$date_created = date_create($date_replaced2);
$scheduled_date = date_format($date_created, "Y-m-d H:i");

$customer_raw = html_find('#customer', $html);
$customer = trim($customer_raw);
$trade = html_find('#trade', $html);

$nte = html_find('#nte', $html);
$nte_number = preg_replace('/[$,]/', '', $nte);
$nte_float = number_format(floatval($nte_number), 2, '.', '');

$store_id = html_find('#location_name', $html);

$address_final = [];
$address = html_find('a#location_address', $html);
$address_formatted = preg_replace('/\s+/', ',', $address);
$address_array = explode(',', $address_formatted);
foreach ($address_array as $address) {
    if ($address != "") array_push($address_final, $address);
}

$street = implode(" ", array_slice($address_final, 0, 3));
$address_length = count($address_final);
$city = $address_final[$address_length - 3];
$state = $address_final[$address_length - 2];
$post_code = $address_final[$address_length - 1];

$phone = html_find('#location_phone', $html);
$phone_number = preg_replace('/\D+/', '', $phone);
$phone_float = floatval($phone_number);

$data_to_save = array(
    $tracking_number,
    $po_number,
    $scheduled_date,
    $customer,
    $trade,
    $nte_float,
    $store_id,
    $street,
    $city,
    $state,
    $post_code,
    $phone_float
);

$file = fopen('dane.csv', 'w');
fputcsv($file, $data_to_save, ',');
fclose($file);
echo 'Success! Data saved to csv!';
