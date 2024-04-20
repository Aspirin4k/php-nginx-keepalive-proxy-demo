<?php

$requests = [
    'https://animechan.xyz/api/random',
    'http://nginx:8083/animechan.xyz/api/random',
    'https://whentheycry.ru/api/post/2',
    'http://nginx:8083/whentheycry.ru/api/post/2',
];
$iterations = 10;

foreach ($requests as $request) {
    echo "Running $request\n";

    $start = microtime(true);
    for ($i = 0; $i < $iterations; $i++) {
        $curl = curl_init();

        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => $request,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ]
        );

        $response = curl_exec($curl);

        $connectTime = curl_getinfo($curl, CURLINFO_CONNECT_TIME);
        $appconnectTime = curl_getinfo($curl, CURLINFO_APPCONNECT_TIME);
        $totalTime = curl_getinfo($curl, CURLINFO_TOTAL_TIME);
        echo "Executed request #{$i}: tcp = {$connectTime}, tls = {$appconnectTime}, total = {$totalTime}\n";

        // We execute HTTP requests to remote API within single request
        // For simplicity just close curl handler here
        // (similar how it will be closed after PHP-FPM worker finishes processing of the request)
        curl_close($curl);
    }

    echo "Finished all requests in " . (microtime(true) - $start) . " seconds\n";
    echo "========================\n";
}
