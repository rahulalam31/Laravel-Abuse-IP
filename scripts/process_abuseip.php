<?php

// URL of the text file
$url = 'https://raw.githubusercontent.com/rahulalam31/Laravel-Abuse-IP/main/abuseip.json';

// Path to save the cleaned and processed JSON file
$saveFilePath = base_path('abuseip.json');

// Fetch the content of the text file
$textContent = file_get_contents($url);

if ($textContent === false) {
    die('Error fetching the text file.');
}

// Split the content into lines
$lines = explode(PHP_EOL, $textContent);

// Filter lines to keep only valid IP addresses
$filteredIps = array_filter($lines, function($line) {
    return filter_var($line, FILTER_VALIDATE_IP);
});

// Convert the filtered IPs to ip2long format
$processedIps = array_map('ip2long', $filteredIps);

// Save the cleaned and processed IP addresses to a JSON file
file_put_contents($saveFilePath, json_encode($processedIps, JSON_PRETTY_PRINT));

echo "Cleaned and processed IPs saved to $saveFilePath.";
