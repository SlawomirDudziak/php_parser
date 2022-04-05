<?php

include('simplehtmldom/simple_html_dom.php');

$html = file_get_html('wo_for_parse.html');
$new_line_char = '<br/>';

$tracking_number = $html->find('#wo_number', 0)->plaintext;
$po_number = $html->find('#po_number', 0)->plaintext;

$date_raw = $html->find('#scheduled_date', 0)->plaintext;
$date_replaced = preg_replace('/\s+/', ',', $date_raw);
$date_replaced2 = preg_replace('/,/', ' ', $date_replaced);
$date_created = date_create($date_replaced2);
$scheduled_date = date_format($date_created, "Y-m-d H:i");

$customer = $html->find('#customer', 0)->plaintext;
$trade = $html->find('#trade', 0)->plaintext;

$nte = $html->find('#nte', 0)->plaintext;
$nte_number = preg_replace('/[$,]/', '', $nte);
$nte_float = number_format(floatval($nte_number), 2, '.', '');

$store_id = $html->find('#location_name', 0)->plaintext;

$address_final = [];
$address = $html->find('a#location_address', 0)->plaintext;
$address_formatted = preg_replace('/\s+/', ',', $address);
$address_array = explode(',', $address_formatted);
foreach ($address_array as $address) {
    if ($address != "") array_push($address_final, $address);
}

$street = implode(" ", array_slice($address_final, 0, 3));
$city = $address_final[count($address_final)-3];
$state = $address_final[count($address_final)-2];
$post_code = $address_final[count($address_final)-1];

$phone = $html->find('#location_phone', 0)->plaintext;
$phone_number = preg_replace('/\D+/', '', $phone);
$phone_float = floatval($phone_number);

echo '<b>Tracking Number</b>: ' . $tracking_number . $new_line_char;
echo '<b>PO Number</b>: ' . $po_number . $new_line_char;
echo '<b>Data Scheduled w formacie daty i godziny (Y-m-d H:i)</b>: ' . $scheduled_date . $new_line_char;
echo '<b>Customer</b>: ' . $customer . $new_line_char;
echo '<b>Trade</b>: ' . $trade . $new_line_char;
echo '<b>NTE (jako liczba float - bez formatowania)</b>: ' . $nte_float . $new_line_char;
echo '<b>Store ID</b>: ' . $store_id . $new_line_char;
echo '<b>Address z rozbiciem na ulica</b>: ' .  $street . $new_line_char;
echo '<b>miasto</b>: ' . $city . $new_line_char;
echo '<b>stan (2 litery)</b>: ' . $state . $new_line_char;
echo '<b>kod pocztowy</b>: ' . $post_code . $new_line_char;
echo '<b>Telefon (jako liczba float - bez formatowania)</b>: ' . $phone_float . $new_line_char;