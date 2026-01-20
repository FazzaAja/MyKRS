<?php
header('Content-Type: application/json');

// Data dummy mata_kuliah
$mata_kuliah = [
    [
        'id' => 1,
        'kode' => 'INF1',
        'nama' => 'Dasar-Dasar Pemrograman',
        'sks' => 3,
        'sem' => 1,
        'kelas' => 'A'
    ],
    [
        'id' => 2,
        'kode' => 'INF2',
        'nama' => 'Pemrograman Web Dinamis',
        'sks' => 2,
        'sem' => 6,
        'kelas' => 'A'
    ],
    [
        'id' => 3,
        'kode' => 'INF3',
        'nama' => 'Pemrograman Berorientasi Objek',
        'sks' => 2,
        'sem' => 3,
        'kelas' => 'B'
    ],
    [
        'id' => 4,
        'kode' => 'INF4',
        'nama' => 'Algoritma Pemrograman',
        'sks' => 4,
        'sem' => 2,
        'kelas' => 'C'
    ],
    [
        'id' => 5,
        'kode' => 'INF5',
        'nama' => 'Pemrograman Web',
        'sks' => 2,
        'sem' => 2,
        'kelas' => 'B'
    ]
];

echo json_encode($mata_kuliah);
