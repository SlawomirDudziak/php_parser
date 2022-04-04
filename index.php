<?php

include('simplehtmldom/simple_html_dom.php');

$html = file_get_html('wo_for_parse.html');

$tracking_number = $html->find('#wo_number', 0)->plaintext;

echo $tracking_number;