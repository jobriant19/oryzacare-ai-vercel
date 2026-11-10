<?php

return [

    // Lokasi python. Disarankan python dari virtual environment di dalam project (lihat panduan).
    'python'  => env('PADI_PYTHON', 'python'),
    'script'  => base_path('python/predict.py'),
    'model'   => base_path('python/model_random_forest.pkl'),
    'timeout' => 90,

    // Production (Vercel): panggil fungsi Python lewat HTTP. Kosongkan untuk menjalankan python lokal.
    'classifier_url'   => env('CLASSIFIER_URL'),
    'classifier_token' => env('CLASSIFIER_TOKEN'),

    // Batas unggahan (KB). Vercel membatasi body request 4,5 MB.
    'max_upload_kb' => 4096,

    // Di bawah nilai ini (persen) hasil diberi peringatan "keyakinan rendah".
    'low_confidence'  => 60,
    'high_confidence' => 85,

    // Akurasi data uji dari Google Colab (hanya untuk ditampilkan).
    'model_accuracy' => 98.48,

    'diseases' => [

        'Bacterialblight' => [
            'name'    => 'Hawar Daun Bakteri (Kresek)',
            'latin'   => 'Xanthomonas oryzae pv. oryzae',
            'tone'    => 'amber',
            'risk'    => 'Tinggi',
            'summary' => 'Penyakit bakteri yang membuat daun mengering dari ujung dan tepi, tampak seperti terbakar.',
            'condition' => 'Daun terindikasi terserang hawar daun bakteri. Infeksi biasanya dimulai dari ujung atau tepi daun lalu meluas ke pangkal sehingga daun mengering. Pada serangan berat, tanaman muda dapat layu (kresek) dan hasil panen turun signifikan.',
            'symptoms' => [
                'Bercak kehijauan tampak basah pada ujung atau tepi daun.',
                'Bercak melebar bergelombang, berubah kuning lalu putih keabuan.',
                'Daun mengering mulai dari ujung ke arah pangkal.',
                'Pada tanaman muda: daun menggulung dan layu (kresek).',
            ],
            'actions' => [
                'Kurangi pupuk nitrogen (urea) yang berlebihan, gunakan pemupukan berimbang N-P-K.',
                'Atur pengairan berselang dan jangan alirkan air dari petak sakit ke petak sehat.',
                'Cabut dan musnahkan bagian tanaman yang terserang parah, bersihkan gulma dan jerami inang.',
                'Hindari melukai tanaman saat penyiangan karena luka menjadi pintu masuk bakteri.',
                'Bila serangan meluas, gunakan bakterisida sesuai label dan anjuran penyuluh pertanian (PPL) setempat.',
            ],
            'prevention' => 'Gunakan varietas tahan, benih sehat, jarak tanam tidak terlalu rapat, dan rotasi tanaman.',
        ],

        'Blast' => [
            'name'    => 'Blas (Blast)',
            'latin'   => 'Pyricularia oryzae (Magnaporthe oryzae)',
            'tone'    => 'red',
            'risk'    => 'Tinggi',
            'summary' => 'Penyakit jamur dengan bercak belah ketupat berpusat abu-abu, dapat menyerang hingga leher malai.',
            'condition' => 'Daun terindikasi terserang penyakit blas. Jamur membentuk bercak berbentuk belah ketupat dengan pusat abu-abu dan tepi coklat. Serangan dapat berlanjut ke leher malai (patah leher) sehingga gabah hampa dan hasil panen turun drastis.',
            'symptoms' => [
                'Bercak berbentuk belah ketupat (mata) dengan pusat abu-abu keputihan dan tepi coklat.',
                'Bercak saling menyatu sehingga daun mengering.',
                'Pada leher malai muncul busuk coklat hingga malai patah.',
                'Berkembang pada kelembapan tinggi dan pemupukan nitrogen berlebih.',
            ],
            'actions' => [
                'Kurangi dan bagi pemberian nitrogen secara bertahap, cukupi unsur kalium.',
                'Aplikasikan fungisida berbahan aktif yang dianjurkan (misalnya trisiklazol, isoprotiolan, atau azoksistrobin) sesuai label, terutama saat anakan maksimum dan sebelum keluar malai.',
                'Atur jarak tanam agar tidak terlalu rapat sehingga kelembapan lahan turun.',
                'Bersihkan sisa jerami dan gulma yang menjadi sumber spora.',
                'Konsultasikan dosis dan waktu penyemprotan dengan PPL setempat.',
            ],
            'prevention' => 'Gunakan varietas tahan blas, perlakuan benih, dan pemupukan berimbang.',
        ],

        'Brownspot' => [
            'name'    => 'Bercak Coklat (Brown Spot)',
            'latin'   => 'Bipolaris oryzae (Cochliobolus miyabeanus)',
            'tone'    => 'brown',
            'risk'    => 'Sedang',
            'summary' => 'Penyakit jamur dengan bercak oval coklat berhalo kuning, sering terkait kekurangan hara.',
            'condition' => 'Daun terindikasi bercak coklat. Jamur membentuk bercak oval coklat dengan lingkaran kuning di sekitarnya. Penyakit ini sering muncul pada tanaman yang kekurangan hara (terutama kalium) atau lahan yang kekurangan air; serangan berat menurunkan kualitas dan bobot gabah.',
            'symptoms' => [
                'Bercak oval berwarna coklat tua dengan halo kuning.',
                'Bercak tersebar di permukaan daun dan dapat menyatu.',
                'Daun menguning dan mengering pada serangan berat.',
                'Gabah dapat berbercak dan kurang berisi.',
            ],
            'actions' => [
                'Perbaiki kesuburan lahan dengan pemupukan berimbang dan cukupi kalium serta bahan organik.',
                'Jaga ketersediaan air, jangan biarkan lahan kekeringan.',
                'Gunakan benih sehat atau lakukan perlakuan benih sebelum tanam.',
                'Bila serangan meluas, gunakan fungisida (misalnya mankozeb atau propikonazol) sesuai label dan anjuran PPL.',
                'Bersihkan gulma dan sisa jerami yang terinfeksi.',
            ],
            'prevention' => 'Pemupukan berimbang, pengairan yang cukup, dan penggunaan benih bermutu.',
        ],

        'Tungro' => [
            'name'    => 'Tungro',
            'latin'   => 'Rice tungro bacilliform virus (RTBV) dan Rice tungro spherical virus (RTSV)',
            'tone'    => 'yellow',
            'risk'    => 'Tinggi',
            'summary' => 'Penyakit virus yang ditularkan wereng hijau, daun menguning hingga oranye dan tanaman kerdil.',
            'condition' => 'Daun terindikasi tungro. Penyakit virus ini ditularkan oleh wereng hijau; daun berubah kuning hingga oranye, tanaman kerdil, dan jumlah anakan berkurang. Tanaman yang sudah terinfeksi tidak dapat disembuhkan sehingga pengendalian difokuskan pada vektor dan pencegahan penyebaran.',
            'symptoms' => [
                'Daun berubah kuning hingga oranye, dimulai dari ujung daun.',
                'Tanaman kerdil dan anakan berkurang.',
                'Pembentukan malai terhambat atau gabah hampa.',
                'Gejala sering tampak berkelompok di petak yang sama.',
            ],
            'actions' => [
                'Cabut dan benamkan tanaman bergejala segera agar tidak menjadi sumber penularan.',
                'Kendalikan wereng hijau, misalnya dengan lampu perangkap atau insektisida sesuai anjuran PPL pada waktu yang tepat.',
                'Tanam serempak dan atur jeda tanam agar populasi wereng terputus.',
                'Pantau petak di sekitar dan laporkan ke PPL jika gejala menyebar.',
                'Pada musim tanam berikutnya gunakan varietas tahan tungro.',
            ],
            'prevention' => 'Tanam serempak, varietas tahan tungro, dan pemantauan wereng hijau sejak persemaian.',
        ],
    ],
];
