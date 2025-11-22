<?php

/*
|--------------------------------------------------------------------------
| VERCEL BOOTSTRAP (MODIFIED)
|--------------------------------------------------------------------------
|
| Ini adalah pengganti public/index.php khusus untuk Vercel.
| Kita memindahkan storage path ke /tmp agar bisa ditulisi (Writeable).
|
*/

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

// 🔥 JURUS ANTI READ-ONLY: Pindah Storage ke /tmp 🔥
$app->useStoragePath('/tmp/storage');

// Kita harus bikin folder-foldernya manual karena /tmp itu kosong setiap kali restart
if (!is_dir(storage_path('framework/views'))) {
    mkdir(storage_path('framework/views'), 0777, true);
}
if (!is_dir(storage_path('framework/cache'))) {
    mkdir(storage_path('framework/cache'), 0777, true); // <--- INI YANG BIKIN ERROR KAMU TADI
}
if (!is_dir(storage_path('framework/sessions'))) {
    mkdir(storage_path('framework/sessions'), 0777, true);
}
if (!is_dir(storage_path('logs'))) {
    mkdir(storage_path('logs'), 0777, true);
}
// Buat jaga-jaga kalau Livewire butuh folder temp lokal
if (!is_dir(storage_path('app/livewire-tmp'))) {
    mkdir(storage_path('app/livewire-tmp'), 0777, true);
}

// --- Lanjut ke Proses Standar Laravel ---

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);