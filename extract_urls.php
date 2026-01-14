<?php

$file = __DIR__ . '/bps_kependudukandanmigrasi.html';
$content = file_get_contents($file);

// Kita cari pola link tabel statistik
// Pola: statistics-table?subject=519&...
// Kami akan mengambil window sekitar 200 karakter dari setiap kecocokan untuk melihat strukturnya.

echo "Menganalisis filse seukuran " . strlen($content) . " bytes...\n";

// Pola regex kasar untuk menangkap URL
preg_match_all('/statistics-table\?subject=[^"\'\s&]+/', $content, $matches);

if (empty($matches[0])) {
    echo "Tidak ditemukan link sederhana. Mencoba pola encoded...\n";
    // Coba cari yang mungkin ter-escape
    preg_match_all('/statistics-table\\\\?subject=[^"\'\s&]+/', $content, $matches);
}

$urls = array_unique($matches[0]);
$results = [];

foreach ($urls as $url) {
    // Bersihkan URL
    $cleanUrl = str_replace('\\', '', $url);
    $fullUrl = "https://batangharikab.bps.go.id/id/" . $cleanUrl;
    
    $results[] = [
        'url' => $fullUrl,
        'title' => 'Tabel Statistik (Title to be fetched)' // Title is hard to extract from minified blob without DOM parser
    ];
}

echo "Ditemukan " . count($results) . " potensi link tabel.\n";
if (count($results) > 0) {
    print_r(array_slice($results, 0, 5)); // Show first 5
}

// Simpan ke file sementara
file_put_contents(__DIR__ . '/extracted_urls_php.json', json_encode($results, JSON_PRETTY_PRINT));
