<?php

function getPostcodeCoordinates($postcode) {
    $api_url = "https://api.postcodes.io/postcodes/";

    $ch = curl_init();
    $options = array(
        CURLOPT_URL => $api_url . $postcode,
        CURLOPT_RETURNTRANSFER => 1,
    );
    curl_setopt_array($ch, $options);

    $response = json_decode(curl_exec($ch), true);
    $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);
    
    if($response_code !== 200 || $response['status'] !== 200) {
        return false;
    }

    return array(
        'lat' => $response['result']['latitude'],
        'long' => $response['result']['longitude']
    );
}

function distanceBetweenPostcodes($fromcode, $tocode) {
    $R = 6371; // Radius of earth = 6371km

    $fromCoords = getPostcodeCoordinates($fromcode);
    $toCoords = getPostcodeCoordinates($tocode);


    if($fromCoords === false || $toCoords === false) return false;

    $dLat = deg2rad($fromCoords['lat'] - $toCoords['lat']);
    $dLong = deg2rad($fromCoords['long'] - $toCoords['long']);

    // hav(x) = sin^2(x/2)
    // angle in great circle (in radians) = d/r 
    // h = hav(O) = hav(dLat) + cos(lat1)cos(lat2)hav(dLong)
    // d = r archav(h) = 2r arcsin(sqrt h)

    $h = sin($dLat/2)*sin($dLat/2) + 
        cos(deg2rad($fromCoords['lat']))*cos(deg2rad($toCoords['lat']))
        *sin($dLong/2)*sin($dLong/2);
    $d = 2*$R * asin(sqrt($h));

    return $d;
}
add_filter('climatestrike_postcode_distance', 'distanceBetweenPostcodes', 10, 2); // Priority 10, accepted args 2
