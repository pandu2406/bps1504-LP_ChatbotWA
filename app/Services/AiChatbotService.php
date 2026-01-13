<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiChatbotService
{
    protected $aiApiKey;
    protected $kbliApiUrl;

    public function __construct()
    {
        $this->aiApiKey = env('OPENROUTER_API_KEY');
        $this->kbliApiUrl = 'http://localhost:8011';
    }

    /**
     * Process the user message and generate a response.
     * 
     * @param string $userMessage
     * @return string
     */
    public function generateResponse($userMessage)
    {
        // 1. Check if user is asking about KBLI (Business Classification)
        if ($this->isKbliQuery($userMessage)) {
            $kbliResult = $this->queryKbliLocal($userMessage);
            if ($kbliResult) {
                return $this->formatKbliResponse($kbliResult);
            }
        }

        // 2. Fallback to General AI (Xiaomi via OpenRouter)
        if ($this->aiApiKey) {
            return $this->askAI($userMessage);
        }

        // 3. Default Response
        return "Halo! Saya adalah asisten virtual BPS Batang Hari. \n\n" .
            "Saat ini fitur AI belum dikonfigurasi sepenuhnya. \n" .
            "Silakan hubungi admin untuk info lebih lanjut.";
    }

    protected function isKbliQuery($text)
    {
        $keywords = ['kbli', 'kode', 'klasifikasi', 'usaha', 'dagang', 'tani'];
        foreach ($keywords as $keyword) {
            if (stripos($text, $keyword) !== false)
                return true;
        }
        return false;
    }

    protected function queryKbliLocal($text)
    {
        try {
            $response = Http::post("{$this->kbliApiUrl}/classify", [
                'text' => $text
            ]);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            // Log error
        }
        return null;
    }

    protected function formatKbliResponse($data)
    {
        $results = $data['results'] ?? [];
        if (empty($results))
            return "Maaf, saya tidak menemukan kode KBLI yang cocok.";

        $reply = "📌 *Rekomendasi KBLI 2025*:\n\n";
        foreach (array_slice($results, 0, 3) as $item) {
            $reply .= "*{$item['kbli']}* - {$item['judul']}\n";
            $reply .= "_{$item['deskripsi']}..._\n";
            $reply .= "(Kecocokan: {$item['similarity']}%)\n\n";
        }
        return $reply;
    }

    protected function askAI($text)
    {
        // Using OpenRouter (Xiaomi Model)
        $apiKey = $this->aiApiKey;
        $model = env('OPENROUTER_MODEL', 'xiaomi/mimo-v2-flash');
        $apiUrl = "https://openrouter.ai/api/v1/chat/completions";

        if (!$apiKey) {
            Log::error("OpenRouter Error: API Key is missing in .env");
            return "Maaf, API Key AI belum dikonfigurasi.";
        }

        try {
            $response = Http::withoutVerifying()
                ->withOptions(['force_ip_resolve' => 'v4'])
                ->withHeaders([
                    'Authorization' => "Bearer {$apiKey}",
                    'HTTP-Referer' => env('APP_URL'), // Best practice for OpenRouter
                    'X-Title' => env('APP_NAME'), // Best practice for OpenRouter
                ])->post($apiUrl, [
                        'model' => $model,
                        'messages' => [
                            [
                                'role' => 'system',
                                'content' => $this->buildSystemPrompt($text)
                            ],
                            [
                                'role' => 'user',
                                'content' => $text
                            ]
                        ]
                    ]);

            $responseData = $response->json();

            // LOGGING FOR DEBUGGING
            Log::info("OpenRouter Request Text: " . $text);
            Log::info("OpenRouter Raw Response: " . json_encode($responseData));

            if (isset($responseData['error'])) {
                return "Maaf, ada error dari AI: " . ($responseData['error']['message'] ?? 'Unknown Error');
            }

            return $responseData['choices'][0]['message']['content'] ?? "Maaf, saya tidak mengerti (Format Respons tidak sesuai).";

        } catch (\Exception $e) {
            Log::error("OpenRouter AI Exception: " . $e->getMessage());
            return "Maaf, fitur AI sedang gangguan: " . $e->getMessage();
        }
    }

    protected function buildSystemPrompt($userText = '')
    {
        $context = config('ai_context');

        $prompt = "Anda adalah asisten AI profesional untuk BPS Kabupaten Batang Hari. \n";
        $prompt .= "Jawablah dengan sopan, informatif, dan ringkas. Gunakan Bahasa Indonesia yang baik.\n\n";

        if ($context) {
            $prompt .= "Gunakan informasi berikut sebagai PENGETAHUAN untuk menjawab pertanyaan user:\n";
            $prompt .= "=== DATA IDENTITAS ===\n";
            $prompt .= "Nama: " . $context['identity']['name'] . "\n";
            $prompt .= "Alamat: " . $context['identity']['address'] . "\n";
            $prompt .= "Telepon: " . $context['identity']['phone'] . "\n";
            $prompt .= "Kepala Kantor: " . $context['identity']['head_of_office'] . "\n";
            $prompt .= "Jam Operasional: " . $context['identity']['operating_hours'] . "\n\n";

            $prompt .= "=== VISI & MISI ===\n";
            $prompt .= "Visi: " . $context['vision'] . "\n";
            $prompt .= "Misi: " . $context['mission'] . "\n\n";

            $prompt .= "=== TUGAS & LAYANAN ===\n";
            $prompt .= $context['tasks'] . "\n";
            $prompt .= $context['services'] . "\n\n";

            $prompt .= "=== PANDUAN TABEL DINAMIS ===\n";
            $prompt .= $context['dynamic_tables'] . "\n\n";

            $prompt .= "=== DAFTAR SUBJEK STATISTIK (UNTUK PENCARIAN) ===\n";
            $prompt .= "Gunakan daftar ini untuk merekomendasikan kata kunci pencarian jika user bertanya tentang data:\n";
            foreach ($context['statistics_subjects'] as $category => $items) {
                $prompt .= "- **$category**: " . implode(', ', $items) . "\n";
            }
            $prompt .= "\n";

            // Smart Search for Publications
            $relevantPubs = $this->findRelevantPublications($userText);
            if (!empty($relevantPubs)) {
                $prompt .= "=== REKOMENDASI PUBLIKASI TERKAIT ===\n";
                $prompt .= "Berdasarkan pertanyaan user, berikut adalah publikasi yang mungkin relevan:\n";
                foreach ($relevantPubs as $pub) {
                    $prompt .= "- **{$pub['title']}**: {$pub['desc']}\n";
                }
                $prompt .= "\n";
            }

            // Smart Search for News
            $relevantNews = $this->findRelevantNews($userText);
            if (!empty($relevantNews)) {
                $prompt .= "=== BERITA TERBARU TERKAIT ===\n";
                $prompt .= "Berikut adalah berita BPS Batang Hari yang relevan:\n";
                foreach ($relevantNews as $item) {
                    $prompt .= "- [{$item['date']}] **{$item['title']}**: {$item['desc']}\n";
                }
                $prompt .= "\n";
            }

            // Smart Search for Dynamic Knowledge Base (Database)
            $dbKnowledge = $this->findRelevantKnowledge($userText);
            if (!empty($dbKnowledge)) {
                $prompt .= "=== JAWABAN KHUSUS DARI DATABASE ===\n";
                $prompt .= "Gunakan informasi berikut jika relevan dengan pertanyaan user:\n";
                foreach ($dbKnowledge as $kb) {
                    $prompt .= "- **Tanya**: {$kb->question}\n";
                    $prompt .= "  **Jawab**: {$kb->answer}\n";
                }
                $prompt .= "\n";
            }

            // Smart Search for Demography Tables (New)
            $relevantDemo = $this->findRelevantDemography($userText);
            if (!empty($relevantDemo)) {
                $prompt .= "=== TABEL STATISTIK KEPENDUDUKAN & MIGRASI TERSEDIA ===\n";
                $prompt .= "User mungkin menanyakan data ini. Anda TAHU bahwa tabel ini ada, tetapi Anda TIDAK TAHU angka pastinya (karena belum membaca Excel-nya).\n";
                $prompt .= "Jika user bertanya angka spesifik, JANGAN MENGARANG. Jawablah: 'Data tersedia pada tabel [JUDUL], silakan cek di website kami'.\n";
                $prompt .= "Daftar Tabel Relevan:\n";
                foreach ($relevantDemo as $item) {
                    $prompt .= "- **{$item['title']}** (Update: {$item['updated_at']}) - Link: {$item['url']}\n";
                }
                $prompt .= "\n";
            }

            // Add detailed population data if query is about population
            $populationData = config('bps_population_by_district');
            if ($populationData && (stripos($userText, 'penduduk') !== false || stripos($userText, 'populasi') !== false || stripos($userText, 'kecamatan') !== false)) {
                $prompt .= "=== DATA DETAIL: JUMLAH PENDUDUK PER KECAMATAN ===\n";
                $prompt .= "Anda MEMILIKI data lengkap jumlah penduduk per kecamatan tahun 2010-2024.\n";
                $prompt .= "Gunakan data berikut untuk menjawab pertanyaan spesifik:\n\n";
                
                // Add latest year data (2024) for quick reference
                foreach ($populationData['data'] as $kecamatan => $data) {
                    $jumlah2024 = $data['jumlah']['2024'] ?? 0;
                    $lakiLaki2024 = $data['laki_laki']['2024'] ?? 0;
                    $perempuan2024 = $data['perempuan']['2024'] ?? 0;
                    $prompt .= "**{$kecamatan}** (2024): Total " . number_format($jumlah2024) . " jiwa (Laki-laki: " . number_format($lakiLaki2024) . ", Perempuan: " . number_format($perempuan2024) . ")\n";
                }
                
                $prompt .= "\nCatatan: Data lengkap tersedia untuk semua tahun 2010-2024. Jika user menanyakan tahun lain, beritahu bahwa data tersedia dan tanyakan tahun spesifik yang diinginkan.\n\n";
            }

            // Add population density if relevant
            $densityData = config('bps_population_density');
            if ($densityData && (stripos($userText, 'kepadatan') !== false || stripos($userText, 'penduduk') !== false || stripos($userText, 'padat') !== false)) {
                $prompt .= "=== DATA KEPADATAN PENDUDUK ===\n";
                $prompt .= "Kepadatan Penduduk per Kecamatan (Jiwa/Km2):\n\n";
                
                foreach ($densityData['data'] as $kecamatan => $data) {
                    $kepadatan2024 = $data['2024'] ?? 0;
                    $prompt .= "**{$kecamatan}**: {$kepadatan2024} jiwa/km2 (2024)\n";
                }
                
                $prompt .= "\nKecamatan terpadat: Muara Bulian (173 jiwa/km2)\n";
                $prompt .= "Kecamatan terjarang: Batin XXIV (37 jiwa/km2)\n\n";
            }

            // Add population by age group if relevant
            $popAge = config('bps_population_age');
            if ($popAge && (stripos($userText, 'umur') !== false || stripos($userText, 'usia') !== false || stripos($userText, 'penduduk') !== false || stripos($userText, 'demografi') !== false)) {
                $prompt .= "=== DATA PENDUDUK MENURUT KELOMPOK UMUR ===\n";
                $prompt .= "Struktur Penduduk Batang Hari 2024:\n\n";
                
                $total2024 = $popAge['summary']['total']['jumlah']['2024'] ?? 0;
                $usia_produktif = ($popAge['data']['15-19']['jumlah']['2024'] ?? 0) + 
                                  ($popAge['data']['20-24']['jumlah']['2024'] ?? 0) +
                                  ($popAge['data']['25-29']['jumlah']['2024'] ?? 0) +
                                  ($popAge['data']['30-34']['jumlah']['2024'] ?? 0) +
                                  ($popAge['data']['35-39']['jumlah']['2024'] ?? 0) +
                                  ($popAge['data']['40-44']['jumlah']['2024'] ?? 0) +
                                  ($popAge['data']['45-49']['jumlah']['2024'] ?? 0) +
                                  ($popAge['data']['50-54']['jumlah']['2024'] ?? 0) +
                                  ($popAge['data']['55-59']['jumlah']['2024'] ?? 0) +
                                  ($popAge['data']['60-64']['jumlah']['2024'] ?? 0);
                
                $persen_produktif = ($usia_produktif / $total2024) * 100;
                
                $prompt .= "**Total Penduduk**: " . number_format($total2024) . " jiwa\n";
                $prompt .= "**Usia Produktif (15-64 tahun)**: " . number_format($usia_produktif) . " jiwa (" . number_format($persen_produktif, 1) . "%)\n\n";
                
                $prompt .= "**Kelompok Umur Terbesar:**\n";
                $prompt .= "- 0-4 tahun: " . number_format($popAge['data']['0-4']['jumlah']['2024']) . " jiwa\n";
                $prompt .= "- 5-9 tahun: " . number_format($popAge['data']['5-9']['jumlah']['2024']) . " jiwa\n";
                $prompt .= "- 10-14 tahun: " . number_format($popAge['data']['10-14']['jumlah']['2024']) . " jiwa\n\n";
                
                $prompt .= "Catatan: Batang Hari memiliki bonus demografi dengan mayoritas penduduk usia produktif.\n\n";
            }

            // Add marriage statistics if relevant
            $marriageData = config('bps_marriage');
            if ($marriageData && (stripos($userText, 'nikah') !== false || stripos($userText, 'pernikahan') !== false || stripos($userText, 'menikah') !== false || stripos($userText, 'kawin') !== false)) {
                $prompt .= "=== DATA PERNIKAHAN ===\n";
                $prompt .= "Banyaknya Surat Nikah yang Dikeluarkan:\n\n";
                
                $total2024 = 0;
                foreach ($marriageData['data'] as $kecamatan => $data) {
                    $nikah2024 = $data['2024'] ?? 0;
                    $total2024 += $nikah2024;
                }
                
                $prompt .= "**Total Kabupaten Batang Hari 2024**: " . number_format($total2024) . " surat nikah\n\n";
                $prompt .= "**Per Kecamatan (2024):**\n";
                $prompt .= "- Batang Hari: " . number_format($marriageData['data']['Batang Hari']['2024']) . " (terbanyak)\n";
                $prompt .= "- Muara Bulian: " . number_format($marriageData['data']['Muara Bulian']['2024']) . "\n";
                $prompt .= "- Maro Sebo Ilir: " . number_format($marriageData['data']['Maro Sebo Ilir']['2024']) . " (tersedikit)\n\n";
                
                $prompt .= "Catatan: Data tersedia 2010-2024. Trend fluktuatif dengan penurunan saat pandemi 2020.\n\n";
            }

            // Add trade (export/import) data if relevant
            $tradeData = config('bps_trade');
            if ($tradeData && (stripos($userText, 'ekspor') !== false || stripos($userText, 'impor') !== false || stripos($userText, 'perdagangan') !== false || stripos($userText, 'ekonomi') !== false)) {
                $prompt .= "=== DATA EKSPOR & IMPOR ===\n";
                $prompt .= "Volume Ekspor dan Impor Batang Hari (Ton):\n\n";
                
                $ekspor2019 = $tradeData['data']['Ekspor']['2019'] ?? 0;
                $impor2019 = $tradeData['data']['Impor']['2019'] ?? 0;
                $neraca = $ekspor2019 - $impor2019;
                
                $prompt .= "**Tahun 2019:**\n";
                $prompt .= "- Ekspor: " . number_format($ekspor2019, 2, ',', '.') . " ton\n";
                $prompt .= "- Impor: " . number_format($impor2019, 2, ',', '.') . " ton\n";
                $prompt .= "- Neraca Perdagangan: " . number_format($neraca, 2, ',', '.') . " ton (surplus)\n\n";
                
                $prompt .= "Catatan: Batang Hari memiliki surplus perdagangan yang besar. Ekspor didominasi komoditas perkebunan (sawit, karet).\n\n";
            }

            // Add vehicle data if relevant
            $vehicleData = config('bps_vehicles');
            if ($vehicleData && (stripos($userText, 'kendaraan') !== false || stripos($userText, 'motor') !== false || stripos($userText, 'mobil') !== false || stripos($userText, 'transportasi') !== false)) {
                $prompt .= "=== DATA KENDARAAN BERMOTOR ===\n";
                $prompt .= "Jumlah Kendaraan Bermotor Terdaftar:\n\n";
                
                $roda2_2018 = $vehicleData['data']['Batang Hari']['roda_dua']['2018'] ?? 0;
                $roda4_2018 = $vehicleData['data']['Batang Hari']['roda_empat']['2018'] ?? 0;
                $total_2018 = $vehicleData['data']['Batang Hari']['jumlah']['2018'] ?? 0;
                
                $prompt .= "**Batang Hari 2018:**\n";
                $prompt .= "- Roda Dua: " . number_format($roda2_2018) . " unit\n";
                $prompt .= "- Roda Empat: " . number_format($roda4_2018) . " unit\n";
                $prompt .= "- Total: " . number_format($total_2018) . " unit\n\n";
                
                $prompt .= "Catatan: Kendaraan roda dua mendominasi (70% dari total). Pertumbuhan signifikan dari 2014-2018.\n\n";
            }

            // Add Open Data Jambi datasets if relevant
            $openDataIndex = config('opendata_jambi_index');
            if ($openDataIndex && (stripos($userText, 'stunting') !== false || stripos($userText, 'keluarga') !== false || stripos($userText, 'kesehatan') !== false || stripos($userText, 'jambi') !== false || stripos($userText, 'pendidikan') !== false || stripos($userText, 'sekolah') !== false || stripos($userText, 'guru') !== false || stripos($userText, 'siswa') !== false)) {
                $prompt .= "=== DATA OPEN DATA PROVINSI JAMBI ===\n";
                $prompt .= "Sumber data tambahan dari Portal Open Data Provinsi Jambi:\n\n";
                
                // Show priority datasets
                foreach ($openDataIndex['priority'] as $dataset) {
                    $prompt .= "- **{$dataset['title']}**\n";
                    $prompt .= "  Kategori: {$dataset['category']}\n";
                    $prompt .= "  Link: {$dataset['url']}\n\n";
                }
                
                $prompt .= "Catatan: Jika user menanyakan data spesifik dari dataset ini, arahkan mereka ke link yang tersedia. Data lengkap dapat diakses di portal Open Data Jambi.\n\n";
            }

            // Add unemployment rate data if relevant
            $unemploymentData = config('bps_unemployment_rate');
            $laborParticipation = config('bps_labor_participation');
            
            if (($unemploymentData || $laborParticipation) && (stripos($userText, 'pengangguran') !== false || stripos($userText, 'tpt') !== false || stripos($userText, 'tpak') !== false || stripos($userText, 'kerja') !== false || stripos($userText, 'ekonomi') !== false || stripos($userText, 'angkatan kerja') !== false)) {
                
                // TPT Data
                if ($unemploymentData) {
                    $prompt .= "=== DATA TINGKAT PENGANGGURAN TERBUKA (TPT) ===\n";
                    $prompt .= "Data TPT Provinsi Jambi per Kabupaten/Kota (2008-2025):\n\n";
                    
                    // Show latest data (2025) for quick reference
                    foreach ($unemploymentData['data'] as $kabupaten => $data) {
                        $tpt2025 = $data['2025'] ?? null;
                        $tpt2024 = $data['2024'] ?? null;
                        
                        if ($tpt2025 !== null) {
                            $trend = '';
                            if ($tpt2024 !== null) {
                                $change = $tpt2025 - $tpt2024;
                                $trend = $change < 0 ? " (↓ turun " . abs(round($change, 2)) . "%)" : " (↑ naik " . round($change, 2) . "%)";
                            }
                            $prompt .= "**{$kabupaten}** (2025): {$tpt2025}%{$trend}\n";
                        }
                    }
                    
                    $prompt .= "\nCatatan: Data tersedia dari tahun 2008-2025. TPT Batang Hari 2025 adalah 4,21%.\n\n";
                }
                
                // TPAK Data
                if ($laborParticipation) {
                    $prompt .= "=== DATA TINGKAT PARTISIPASI ANGKATAN KERJA (TPAK) ===\n";
                    $prompt .= "Data TPAK Provinsi Jambi per Kabupaten/Kota (2008-2025):\n\n";
                    
                    foreach ($laborParticipation['data'] as $kabupaten => $data) {
                        $tpak2025 = $data['2025'] ?? null;
                        if ($tpak2025 !== null) {
                            $prompt .= "**{$kabupaten}** (2025): {$tpak2025}%\n";
                        }
                    }
                    
                    $prompt .= "\nCatatan: TPAK Batang Hari 2025 adalah 66,44%. TPAK tertinggi: Muaro Jambi (73,82%).\n\n";
                }
            }

            // Add road length data if relevant
            $roadData = config('bps_road_length');
            if ($roadData && (stripos($userText, 'jalan') !== false || stripos($userText, 'infrastruktur') !== false || stripos($userText, 'panjang') !== false)) {
                $prompt .= "=== DATA PANJANG JALAN KABUPATEN ===\n";
                $prompt .= "Panjang Jalan Kabupaten per Kecamatan di Batang Hari (2014-2023):\n\n";
                
                // Show latest data (2023)
                $totalJalan = 0;
                foreach ($roadData['data'] as $kecamatan => $data) {
                    $panjang2023 = $data['2023'] ?? 0;
                    $totalJalan += $panjang2023;
                    $prompt .= "**{$kecamatan}**: {$panjang2023} km\n";
                }
                
                $prompt .= "\n**Total Panjang Jalan Kabupaten**: " . number_format($totalJalan, 2) . " km (2023)\n";
                $prompt .= "Kecamatan dengan jalan terpanjang: Batang Hari (1.011,8 km)\n\n";
            }

            // Add vital statistics if relevant
            $vitalStats = config('bps_vital_statistics');
            if ($vitalStats && (stripos($userText, 'kelahiran') !== false || stripos($userText, 'kematian') !== false || stripos($userText, 'bayi') !== false || stripos($userText, 'ibu') !== false || stripos($userText, 'kesehatan') !== false)) {
                $prompt .= "=== DATA KELAHIRAN DAN KEMATIAN ===\n";
                $prompt .= "Statistik Vital Kabupaten Batang Hari (2006-2024):\n\n";
                
                // Show latest data (2024)
                $kelahiran2024 = $vitalStats['data']['Kelahiran']['2024'] ?? 0;
                $kematianIbu2024 = $vitalStats['data']['Kematian Ibu']['2024'] ?? 0;
                $kematianBayi2024 = $vitalStats['data']['Kematian Bayi']['2024'] ?? 0;
                
                $prompt .= "**Tahun 2024:**\n";
                $prompt .= "- Kelahiran: " . number_format($kelahiran2024) . " jiwa\n";
                $prompt .= "- Kematian Ibu: {$kematianIbu2024} jiwa\n";
                $prompt .= "- Kematian Bayi: {$kematianBayi2024} jiwa\n\n";
                
                $prompt .= "Catatan: Data tersedia dari tahun 2006-2024. Trend kelahiran menurun dari 5.580 (2010) menjadi 4.423 (2024).\n\n";
            }

            // Add expenditure data if relevant
            $expenditureData = config('bps_expenditure');
            if ($expenditureData && (stripos($userText, 'pengeluaran') !== false || stripos($userText, 'ekonomi') !== false || stripos($userText, 'kesejahteraan') !== false || stripos($userText, 'pendapatan') !== false)) {
                $prompt .= "=== DATA PENGELUARAN PER KAPITA ===\n";
                $prompt .= "Distribusi Penduduk Menurut Golongan Pengeluaran (2016-2020):\n\n";
                
                // Show 2020 data
                $prompt .= "**Tahun 2020:**\n";
                foreach ($expenditureData['data'] as $golongan => $data) {
                    $persen2020 = $data['2020'] ?? 0;
                    if ($persen2020 > 0) {
                        $prompt .= "- Rp {$golongan}: {$persen2020}%\n";
                    }
                }
                
                $prompt .= "\nCatatan: Mayoritas penduduk (31,88%) berada di golongan pengeluaran Rp 500.000-749.999. Tren menunjukkan peningkatan penduduk di golongan pengeluaran tinggi (>Rp 1,5 juta) dari 5,51% (2016) menjadi 16,45% (2020).\n\n";
            }

            // Add crime statistics if relevant
            $crimeData = config('bps_crime_statistics');
            if ($crimeData && (stripos($userText, 'kriminal') !== false || stripos($userText, 'kejahatan') !== false || stripos($userText, 'pidana') !== false || stripos($userText, 'keamanan') !== false || stripos($userText, 'polisi') !== false)) {
                $prompt .= "=== DATA TINDAK PIDANA ===\n";
                $prompt .= "Jumlah Tindak Pidana per Polsek di Batang Hari (2014-2024):\n\n";
                
                // Show 2024 data and calculate total
                $total2024 = 0;
                $prompt .= "**Tahun 2024:**\n";
                foreach ($crimeData['data'] as $polsek => $data) {
                    $kasus2024 = $data['2024'] ?? 0;
                    $total2024 += $kasus2024;
                    $prompt .= "- {$polsek}: {$kasus2024} kasus\n";
                }
                
                $prompt .= "\n**Total Tindak Pidana 2024**: {$total2024} kasus\n";
                $prompt .= "Polsek dengan kasus tertinggi: Batang Hari (292 kasus)\n";
                $prompt .= "Polsek dengan kasus terendah: Maro Sebo Ilir (10 kasus)\n\n";
            }

            // Add PDRB macroeconomic data if relevant
            $pdrbData = config('bps_pdrb');
            if ($pdrbData && (stripos($userText, 'pdrb') !== false || stripos($userText, 'ekonomi') !== false || stripos($userText, 'pertumbuhan') !== false || stripos($userText, 'gdp') !== false || stripos($userText, 'produk domestik') !== false)) {
                $prompt .= "=== DATA PDRB (PRODUK DOMESTIK REGIONAL BRUTO) ===\n";
                $prompt .= "PDRB Batang Hari Atas Dasar Harga Konstan 2010 (Milyar Rupiah):\n\n";
                
                // Show 2024 data
                $pdrb2024 = $pdrbData['data']['Produk Domestik Regional Bruto']['2024'] ?? 0;
                $pdrb2023 = $pdrbData['data']['Produk Domestik Regional Bruto']['2023'] ?? 0;
                $pertumbuhan = $pdrb2023 > 0 ? (($pdrb2024 - $pdrb2023) / $pdrb2023 * 100) : 0;
                
                $prompt .= "**PDRB 2024**: Rp " . number_format($pdrb2024, 2) . " Milyar\n";
                $prompt .= "**Pertumbuhan**: " . number_format($pertumbuhan, 2) . "%\n\n";
                
                $prompt .= "**Komponen PDRB 2024:**\n";
                $prompt .= "- Konsumsi Rumah Tangga: Rp " . number_format($pdrbData['data']['Pengeluaran Konsumsi Rumah Tangga']['2024'], 2) . " M\n";
                $prompt .= "- Konsumsi Pemerintah: Rp " . number_format($pdrbData['data']['Pengeluaran Konsumsi Pemerintah']['2024'], 2) . " M\n";
                $prompt .= "- Investasi (PMTB): Rp " . number_format($pdrbData['data']['Pembentukan Modal Tetap Bruto']['2024'], 2) . " M\n";
                $prompt .= "- Net Ekspor: Rp " . number_format($pdrbData['data']['Net Ekspor']['2024'], 2) . " M\n\n";
                
                $prompt .= "Catatan: PDRB tumbuh dari Rp 6.840,50 M (2010) menjadi Rp 14.663,02 M (2024), menunjukkan pertumbuhan ekonomi yang konsisten.\n\n";
            }

            // Add poverty index data if relevant
            $povertyData = config('bps_poverty_index');
            if ($povertyData && (stripos($userText, 'kemiskinan') !== false || stripos($userText, 'miskin') !== false || stripos($userText, 'kesejahteraan') !== false)) {
                $prompt .= "=== DATA INDEKS KEMISKINAN ===\n";
                $prompt .= "Indeks Kedalaman (P1) dan Keparahan (P2) Kemiskinan:\n\n";
                
                // Show Batang Hari data
                $p1_2025 = $povertyData['p1']['Batang Hari']['2025'] ?? null;
                $p1_2024 = $povertyData['p1']['Batang Hari']['2024'] ?? null;
                $p2_2025 = $povertyData['p2']['Batang Hari']['2025'] ?? null;
                
                $prompt .= "**Batang Hari 2025:**\n";
                $prompt .= "- Indeks Kedalaman (P1): {$p1_2025}\n";
                $prompt .= "- Indeks Keparahan (P2): {$p2_2025}\n";
                
                // Add poverty line
                $garisKemiskinan2025 = $povertyData['garis_kemiskinan']['Batang Hari']['2025'] ?? null;
                if ($garisKemiskinan2025) {
                    $prompt .= "- Garis Kemiskinan: Rp " . number_format($garisKemiskinan2025, 0, ',', '.') . "/kapita/bulan\n";
                }
                $prompt .= "\n";
                
                $prompt .= "**Provinsi Jambi 2025:**\n";
                $prompt .= "- P1: " . ($povertyData['p1']['JAMBI']['2025'] ?? 'N/A') . "\n";
                $prompt .= "- P2: " . ($povertyData['p2']['JAMBI']['2025'] ?? 'N/A') . "\n\n";
                
                $prompt .= "Catatan: P1 mengukur kedalaman kemiskinan (seberapa jauh dari garis kemiskinan). P2 mengukur keparahan (ketimpangan antar penduduk miskin). Semakin rendah nilainya, semakin baik. Trend Batang Hari: P1 turun dari 2,60 (2002) ke 1,32 (2025).\n\n";
            }

            // Add education index if relevant
            $educationData = config('bps_education_index');
            if ($educationData && (stripos($userText, 'pendidikan') !== false || stripos($userText, 'sekolah') !== false || stripos($userText, 'indeks pendidikan') !== false)) {
                $prompt .= "=== DATA INDEKS PENDIDIKAN ===\n";
                $prompt .= "Indeks Pendidikan (Metode Baru) 2010-2024:\n\n";
                
                // Show Batang Hari data
                $edu2024 = $educationData['data']['Batang Hari']['2024'] ?? null;
                $edu2010 = $educationData['data']['Batang Hari']['2010'] ?? null;
                
                $prompt .= "**Batang Hari 2024**: {$edu2024}\n";
                $prompt .= "**Provinsi Jambi 2024**: " . ($educationData['data']['Provinsi Jambi']['2024'] ?? 'N/A') . "\n";
                $prompt .= "**Tertinggi**: Kota Jambi (81,56)\n";
                $prompt .= "**Terendah**: Tanjab Timur (59,58)\n\n";
                
                $prompt .= "Catatan: Indeks Pendidikan Batang Hari meningkat dari {$edu2010} (2010) menjadi {$edu2024} (2024). Semakin tinggi nilainya, semakin baik kualitas pendidikan.\n\n";
            }

            // Add average years of schooling if relevant
            $schoolingYears = config('bps_schooling_years');
            if ($schoolingYears && (stripos($userText, 'lama sekolah') !== false || stripos($userText, 'pendidikan') !== false || stripos($userText, 'sekolah') !== false)) {
                $prompt .= "=== DATA RATA-RATA LAMA SEKOLAH ===\n";
                $prompt .= "Rata-rata Lama Sekolah (Tahun):\n\n";
                
                $rls2025 = $schoolingYears['data']['Batang Hari']['jumlah']['2025'] ?? null;
                $rls2024 = $schoolingYears['data']['Batang Hari']['jumlah']['2024'] ?? null;
                $rlsLaki2024 = $schoolingYears['data']['Batang Hari']['laki_laki']['2024'] ?? null;
                $rlsPerempuan2024 = $schoolingYears['data']['Batang Hari']['perempuan']['2024'] ?? null;
                
                $prompt .= "**Batang Hari 2025**: {$rls2025} tahun\n";
                $prompt .= "- Laki-laki (2024): {$rlsLaki2024} tahun\n";
                $prompt .= "- Perempuan (2024): {$rlsPerempuan2024} tahun\n\n";
                
                $prompt .= "**Provinsi Jambi 2025**: " . ($schoolingYears['data']['Provinsi Jambi']['jumlah']['2025'] ?? 'N/A') . " tahun\n\n";
                
                $prompt .= "Catatan: Rata-rata lama sekolah Batang Hari meningkat dari 6,56 tahun (2010) ke 8,41 tahun (2025). Ini menunjukkan peningkatan akses dan partisipasi pendidikan.\n\n";
            }

            // Add life expectancy (IPM component) if relevant
            $lifeExpectancy = config('bps_life_expectancy');
            if ($lifeExpectancy && (stripos($userText, 'harapan hidup') !== false || stripos($userText, 'ipm') !== false || stripos($userText, 'umur') !== false || stripos($userText, 'kesehatan') !== false)) {
                $prompt .= "=== DATA UMUR HARAPAN HIDUP (IPM) ===\n";
                $prompt .= "Umur Harapan Hidup (Tahun) - Komponen IPM:\n\n";
                
                // Show Batang Hari data
                $uhh2025 = $lifeExpectancy['jumlah']['Batang Hari']['2025'] ?? null;
                $uhh2020 = $lifeExpectancy['jumlah']['Batang Hari']['2020'] ?? null;
                $uhhLaki2024 = $lifeExpectancy['laki_laki']['Batang Hari']['2024'] ?? null;
                $uhhPerempuan2024 = $lifeExpectancy['perempuan']['Batang Hari']['2024'] ?? null;
                
                $prompt .= "**Batang Hari 2025**: {$uhh2025} tahun\n";
                $prompt .= "- Laki-laki (2024): {$uhhLaki2024} tahun\n";
                $prompt .= "- Perempuan (2024): {$uhhPerempuan2024} tahun\n\n";
                
                $prompt .= "**Provinsi Jambi 2025**: " . ($lifeExpectancy['jumlah']['Provinsi Jambi']['2025'] ?? 'N/A') . " tahun\n";
                $prompt .= "**Tertinggi**: Kota Jambi (82,32 tahun)\n";
                $prompt .= "**Terendah**: Tanjab Timur (71,28 tahun)\n\n";
                
                $prompt .= "Catatan: Umur Harapan Hidup Batang Hari meningkat dari {$uhh2020} (2020) menjadi {$uhh2025} (2025). Ini adalah salah satu komponen Indeks Pembangunan Manusia (IPM).\n\n";
            }

            // Add health index if relevant
            $healthIndex = config('bps_health_index');
            if ($healthIndex && (stripos($userText, 'kesehatan') !== false || stripos($userText, 'ipm') !== false || stripos($userText, 'indeks kesehatan') !== false)) {
                $prompt .= "=== DATA INDEKS KESEHATAN (IPM) ===\n";
                $prompt .= "Indeks Kesehatan 2010-2023 (Komponen IPM):\n\n";
                
                $health2023 = $healthIndex['data']['Batang Hari']['2023'] ?? null;
                $health2010 = $healthIndex['data']['Batang Hari']['2010'] ?? null;
                
                $prompt .= "**Batang Hari 2023**: {$health2023}\n";
                $prompt .= "**Provinsi Jambi 2023**: " . ($healthIndex['data']['Provinsi Jambi']['2023'] ?? 'N/A') . "\n";
                $prompt .= "**Tertinggi**: Kota Jambi (81,98)\n";
                $prompt .= "**Terendah**: Tanjab Timur (72,28)\n\n";
                
                $prompt .= "Catatan: Indeks Kesehatan Batang Hari meningkat dari {$health2010} (2010) ke {$health2023} (2023). Indeks ini mengukur derajat kesehatan masyarakat berdasarkan umur harapan hidup.\n\n";
            }

            // Add palm oil agriculture data if relevant
            $palmOil = config('bps_palm_oil');
            if ($palmOil && (stripos($userText, 'sawit') !== false || stripos($userText, 'kelapa sawit') !== false || stripos($userText, 'karet') !== false || stripos($userText, 'pertanian') !== false || stripos($userText, 'perkebunan') !== false || stripos($userText, 'produksi') !== false)) {
                $prompt .= "=== DATA PERKEBUNAN (KELAPA SAWIT & KARET) ===\n";
                $prompt .= "Produksi dan Luas Tanam Perkebunan Batang Hari:\n\n";
                
                // Kelapa Sawit
                $sawit_prod2023 = $palmOil['kelapa_sawit']['produksi']['data']['2023'] ?? 0;
                $sawit_luas2023 = $palmOil['kelapa_sawit']['luas_tanam']['data']['2023'] ?? 0;
                
                $prompt .= "**KELAPA SAWIT (2023):**\n";
                $prompt .= "- Produksi: " . number_format($sawit_prod2023, 0, ',', '.') . " ton\n";
                $prompt .= "- Luas Tanam: " . number_format($sawit_luas2023, 0, ',', '.') . " hektar\n\n";
                
                // Karet
                $karet_prod2023 = $palmOil['karet']['produksi']['data']['2023'] ?? 0;
                $karet_luas2023 = $palmOil['karet']['luas_tanam']['data']['2023'] ?? 0;
                
                $prompt .= "**KARET (2023):**\n";
                $prompt .= "- Produksi: " . number_format($karet_prod2023, 0, ',', '.') . " ton\n";
                $prompt .= "- Luas Tanam: " . number_format($karet_luas2023, 0, ',', '.') . " hektar\n\n";
                
                $prompt .= "Catatan: Kelapa sawit dan karet adalah 2 komoditas perkebunan utama Batang Hari. Sawit mengalami lonjakan 2021, sementara karet mengalami penurunan 2022.\n\n";
            }
        }

        return $prompt;
    }

    protected function findRelevantKnowledge($text)
    {
        if (empty($text))
            return [];

        // 1. Split text into keywords
        $keywords = explode(' ', strtolower($text));
        $ignored = ['yang', 'dan', 'di', 'ke', 'dari', 'ini', 'itu', 'adalah', 'saya', 'mau', 'minta', 'punya', 'ada', 'tentang', 'apa', 'kapan', 'berita', 'apakah', 'bagaimana'];
        $keywords = array_filter($keywords, function ($k) use ($ignored) {
            return strlen($k) > 3 && !in_array($k, $ignored);
        });

        if (empty($keywords))
            return [];

        // 2. Query Database using LIKE for simplicity (or Full Text Search if MySQL supported/configured)
        $query = \App\Models\AiKnowledgeBase::where('is_active', true);

        $query->where(function ($q) use ($keywords) {
            foreach ($keywords as $word) {
                $q->orWhere('question', 'LIKE', "%{$word}%")
                    ->orWhere('keywords', 'LIKE', "%{$word}%");
            }
        });

        // 3. Get results
        return $query->take(3)->get();
    }

    protected function findRelevantNews($text)
    {
        if (empty($text))
            return [];

        $news = config('news', []);
        $matches = [];
        $keywords = explode(' ', strtolower($text));

        // Filter keywords
        $ignored = ['yang', 'dan', 'di', 'ke', 'dari', 'ini', 'itu', 'adalah', 'saya', 'mau', 'minta', 'punya', 'ada', 'tentang', 'apa', 'kapan', 'berita'];
        $keywords = array_filter($keywords, function ($k) use ($ignored) {
            return strlen($k) > 3 && !in_array($k, $ignored);
        });

        foreach ($news as $item) {
            $score = 0;
            $newsText = strtolower($item['title'] . ' ' . $item['desc']);

            foreach ($keywords as $word) {
                if (strpos($newsText, $word) !== false) {
                    $score++;
                }
            }

            if ($score > 0) {
                $matches[] = ['news' => $item, 'score' => $score];
            }
        }

        // Sort by score (desc) then date (desc)
        usort($matches, function ($a, $b) {
            if ($a['score'] == $b['score']) {
                return strcmp($b['news']['date'], $a['news']['date']); // Newest first
            }
            return $b['score'] <=> $a['score'];
        });

        return array_map(function ($m) {
            return $m['news'];
        }, array_slice($matches, 0, 5));
    }

    protected function findRelevantPublications($text)
    {
        if (empty($text))
            return [];

        $publications = config('publications', []);
        $matches = [];
        $keywords = explode(' ', strtolower($text));

        // Filter keywords to ignore common words
        $ignored = ['yang', 'dan', 'di', 'ke', 'dari', 'ini', 'itu', 'adalah', 'saya', 'mau', 'minta', 'punya', 'ada'];
        $keywords = array_filter($keywords, function ($k) use ($ignored) {
            return strlen($k) > 3 && !in_array($k, $ignored);
        });

        foreach ($publications as $pub) {
            $score = 0;
            $pubText = strtolower($pub['title'] . ' ' . $pub['category']);

            foreach ($keywords as $word) {
                if (strpos($pubText, $word) !== false) {
                    $score++;
                }
            }

            if ($score > 0) {
                $matches[] = ['pub' => $pub, 'score' => $score];
            }
        }

        // Sort by score and take top 5
        usort($matches, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        return array_map(function ($m) {
            return $m['pub'];
        }, array_slice($matches, 0, 5));
    }

    protected function findRelevantDemography($text)
    {
        if (empty($text))
            return [];

        $data = config('bps_demography', []);
        $matches = [];
        $keywords = explode(' ', strtolower($text));

        // Filter keywords
        $ignored = ['yang', 'dan', 'di', 'ke', 'dari', 'ini', 'itu', 'adalah', 'saya', 'mau', 'minta', 'punya', 'ada', 'data', 'statistik'];
        $keywords = array_filter($keywords, function ($k) use ($ignored) {
            return strlen($k) > 3 && !in_array($k, $ignored);
        });

        if (empty($keywords)) {
            // If query is generic like "data kependudukan", return top 5 latest
            return array_slice($data, 0, 5);
        }

        foreach ($data as $item) {
            $score = 0;
            $itemText = strtolower($item['title'] . ' ' . $item['category']);

            foreach ($keywords as $word) {
                if (strpos($itemText, $word) !== false) {
                    $score++;
                }
            }

            if ($score > 0) {
                $matches[] = ['item' => $item, 'score' => $score];
            }
        }

        // Sort by score
        usort($matches, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        return array_map(function ($m) {
            return $m['item'];
        }, array_slice($matches, 0, 5));
    }
}
