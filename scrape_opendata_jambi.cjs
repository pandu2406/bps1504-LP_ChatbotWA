const https = require('https');
const fs = require('fs');
const path = require('path');

// Function to fetch HTML content
function fetchPage(url) {
    return new Promise((resolve, reject) => {
        https.get(url, (res) => {
            let data = '';
            res.on('data', chunk => data += chunk);
            res.on('end', () => resolve(data));
        }).on('error', reject);
    });
}

// Function to extract dataset links from HTML
function extractDatasets(html) {
    const datasets = [];

    // Regex to find dataset links
    const linkRegex = /href="(https:\/\/opendata\.jambiprov\.go\.id\/web-datasets\/[^"]+)"/g;
    const titleRegex = /\[([^\]]+)\]\(https:\/\/opendata\.jambiprov\.go\.id\/web-datasets\/[^)]+\)/g;

    let match;
    const links = new Set();

    // Extract all unique dataset URLs
    while ((match = linkRegex.exec(html)) !== null) {
        const url = match[1];
        if (url.includes('/web-datasets/') && !url.includes('?')) {
            links.add(url);
        }
    }

    // Extract titles
    const titles = [];
    while ((match = titleRegex.exec(html)) !== null) {
        titles.push(match[1]);
    }

    // Combine
    const linksArray = Array.from(links);
    linksArray.forEach((url, idx) => {
        const title = titles[idx] || 'Unknown';
        const id = url.split('/')[4];
        const slug = url.split('/')[5];

        datasets.push({
            id,
            title,
            url,
            slug
        });
    });

    return datasets;
}

// Main scraper
async function scrapeOpenDataJambi() {
    console.log('🚀 Starting Open Data Jambi scraper...\n');

    const allDatasets = [];
    const categories = [
        { id: 3, name: 'Kebencanaan' },
        { id: 4, name: 'Pariwisata' },
        { id: 5, name: 'Kesehatan' },
        { id: 6, name: 'Pendidikan' },
        { id: 8, name: 'Sosial dan Kemasyarakatan' },
        { id: 9, name: 'Ekonomi' },
        { id: 10, name: 'Lingkungan Hidup' },
        { id: 12, name: 'Kepegawaian' },
        { id: 13, name: 'Potensi Sumber Daya Alam' },
        { id: 15, name: 'Indeks Desa Membangun' },
        { id: 16, name: 'Perizinan' },
        { id: 18, name: 'Teknologi Informasi' },
        { id: 19, name: 'Pembangunan' },
        { id: 21, name: 'Pemerintahan dan Administrasi Publik' },
        { id: 24, name: 'Transportasi' },
        { id: 25, name: 'Hukum' },
        { id: 26, name: 'Pengembangan SDM' },
        { id: 27, name: 'Kesatuan Bangsa dan Politik' },
        { id: 28, name: 'Olahraga' },
        { id: 29, name: 'Perpustakaan dan Kearsipan' },
        { id: 30, name: 'Ketenagakerjaan' },
        { id: 31, name: 'Pembangunan Keluarga' }
    ];

    for (const category of categories) {
        console.log(`📂 Scraping: ${category.name}...`);

        try {
            const url = `https://opendata.jambiprov.go.id/web-datasets?sektor=${category.id}`;
            const html = await fetchPage(url);
            const datasets = extractDatasets(html);

            datasets.forEach(ds => {
                ds.category = category.name;
                ds.category_id = category.id;
            });

            allDatasets.push(...datasets);
            console.log(`   ✅ Found ${datasets.length} datasets`);

            // Delay to be polite
            await new Promise(r => setTimeout(r, 500));

        } catch (error) {
            console.log(`   ❌ Error: ${error.message}`);
        }
    }

    // Remove duplicates
    const uniqueDatasets = Array.from(
        new Map(allDatasets.map(ds => [ds.id, ds])).values()
    );

    console.log(`\n💾 Total unique datasets: ${uniqueDatasets.length}`);

    // Filter Batang Hari specific
    const batangHariDatasets = uniqueDatasets.filter(ds =>
        ds.title.toLowerCase().includes('batang hari') ||
        ds.slug.includes('batang-hari')
    );

    console.log(`🎯 Batang Hari specific: ${batangHariDatasets.length}`);

    // Generate PHP config
    let phpContent = "<?php\n\n";
    phpContent += "// Open Data Jambi - Metadata Index\n";
    phpContent += "// Total Datasets: " + uniqueDatasets.length + "\n";
    phpContent += "// Batang Hari Specific: " + batangHariDatasets.length + "\n\n";
    phpContent += "return [\n";
    phpContent += "    'batang_hari' => [\n";

    batangHariDatasets.forEach((ds, idx) => {
        phpContent += "        [\n";
        phpContent += `            'title' => '${ds.title.replace(/'/g, "\\'")}',\n`;
        phpContent += `            'url' => '${ds.url}',\n`;
        phpContent += `            'category' => '${ds.category}'\n`;
        phpContent += "        ]" + (idx < batangHariDatasets.length - 1 ? "," : "") + "\n";
    });

    phpContent += "    ],\n";
    phpContent += "    'all' => [\n";

    uniqueDatasets.forEach((ds, idx) => {
        phpContent += "        [\n";
        phpContent += `            'title' => '${ds.title.replace(/'/g, "\\'")}',\n`;
        phpContent += `            'url' => '${ds.url}',\n`;
        phpContent += `            'category' => '${ds.category}'\n`;
        phpContent += "        ]" + (idx < uniqueDatasets.length - 1 ? "," : "") + "\n";
    });

    phpContent += "    ]\n";
    phpContent += "];\n";

    // Save
    const outputPath = path.join(__dirname, 'config', 'opendata_jambi_index.php');
    fs.writeFileSync(outputPath, phpContent);

    console.log(`\n✅ Saved to: ${outputPath}`);
}

// Run
scrapeOpenDataJambi().catch(console.error);
