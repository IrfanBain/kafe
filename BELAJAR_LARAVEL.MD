# 📘 Catatan Lengkap Belajar Laravel 12 Dasar

---

## 📌 Daftar Isi

-   [📘 Catatan Lengkap Belajar Laravel 12 Dasar](#-catatan-lengkap-belajar-laravel-12-dasar)
    -   [📌 Daftar Isi](#-daftar-isi)
    -   [1. Pengenalan Laravel](#1-pengenalan-laravel)
        -   [Keunggulan Laravel:](#keunggulan-laravel)
    -   [2. Instalasi Laravel 12](#2-instalasi-laravel-12)
        -   [Syarat Sistem:](#syarat-sistem)
        -   [Perintah Instalasi:](#perintah-instalasi)
        -   [Jalankan Server:](#jalankan-server)
    -   [3. Struktur Direktori Laravel](#3-struktur-direktori-laravel)
    -   [4. Routing Dasar](#4-routing-dasar)
    -   [5. Controller](#5-controller)
        -   [Membuat Controller:](#membuat-controller)
        -   [Contoh Controller:](#contoh-controller)
    -   [6. View (Blade Template)](#6-view-blade-template)
        -   [File View:](#file-view)
        -   [Layout View:](#layout-view)
    -   [7. Model dan Database (Eloquent)](#7-model-dan-database-eloquent)
        -   [Konfigurasi `.env`:](#konfigurasi-env)
        -   [Membuat Model + Migrasi:](#membuat-model--migrasi)
        -   [Jalankan Migrasi:](#jalankan-migrasi)
    -   [8. Validasi Form](#8-validasi-form)
    -   [9. CRUD (Create, Read, Update, Delete)](#9-crud-create-read-update-delete)
    -   [10. Artisan CLI](#10-artisan-cli)
    -   [11. Environment dan Konfigurasi](#11-environment-dan-konfigurasi)
    -   [12. Laravel Mix dan Vite](#12-laravel-mix-dan-vite)
        -   [Jalankan Vite:](#jalankan-vite)
    -   [13. Autentikasi Sederhana (Laravel Breeze)](#13-autentikasi-sederhana-laravel-breeze)
    -   [14. Seeder, Factory dan Faker](#14-seeder-factory-dan-faker)
    -   [15. Middleware](#15-middleware)
    -   [16. Request \& Response](#16-request--response)
    -   [17. File Upload dan Storage](#17-file-upload-dan-storage)
    -   [18. Relasi Database](#18-relasi-database)
    -   [19. Debugging dan Error Handling](#19-debugging-dan-error-handling)
    -   [20. Tips Lanjut Belajar Laravel](#20-tips-lanjut-belajar-laravel)

---

## 1. Pengenalan Laravel

Laravel adalah framework PHP modern berbasis MVC (Model View Controller). Laravel menyediakan sintaks elegan untuk mengembangkan aplikasi web cepat dan aman.

### Keunggulan Laravel:

-   Clean syntax
-   Routing dan controller terpisah
-   ORM Eloquent
-   Blade templating engine
-   Fitur Artisan CLI
-   Mudah diintegrasi dengan autentikasi, middleware, broadcasting

---

## 2. Instalasi Laravel 12

### Syarat Sistem:

-   PHP >= 8.2
-   Composer
-   Node.js & NPM
-   MySQL / PostgreSQL / SQLite

### Perintah Instalasi:

```bash
composer create-project laravel/laravel:^12.0 nama-proyek
```

### Jalankan Server:

```bash
cd nama-proyek
php artisan serve
```

---

## 3. Struktur Direktori Laravel

| Folder             | Fungsi                                         |
| ------------------ | ---------------------------------------------- |
| `app/`             | Logic aplikasi (model, controller, middleware) |
| `routes/`          | Routing aplikasi (`web.php`, `api.php`)        |
| `resources/views/` | Template Blade                                 |
| `public/`          | Root akses via web (index.php, asset publik)   |
| `database/`        | Migrasi, seeder, factory                       |
| `config/`          | File konfigurasi                               |
| `.env`             | Environment variabel                           |

---

## 4. Routing Dasar

```php
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/halo', function () {
    return 'Halo Laravel!';
});
```

Routing ke controller:

```php
use App\Http\Controllers\HomeController;

Route::get('/home', [HomeController::class, 'index']);
```

---

## 5. Controller

### Membuat Controller:

```bash
php artisan make:controller HomeController
```

### Contoh Controller:

```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        return view('home');
    }
}
```

---

## 6. View (Blade Template)

### File View:

`resources/views/home.blade.php`

```blade
@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<h1>Selamat datang!</h1>
@endsection
```

### Layout View:

`resources/views/layouts/app.blade.php`

```blade
<html>
<head>
    <title>@yield('title')</title>
</head>
<body>
    <div class="container">
        @yield('content')
    </div>
</body>
</html>
```

---

## 7. Model dan Database (Eloquent)

### Konfigurasi `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12
DB_USERNAME=root
DB_PASSWORD=
```

### Membuat Model + Migrasi:

```bash
php artisan make:model Post -m
```

Isi file migrasi:

```php
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->string('judul');
    $table->text('konten');
    $table->timestamps();
});
```

### Jalankan Migrasi:

```bash
php artisan migrate
```

---

## 8. Validasi Form

```php
public function store(Request $request)
{
    $validated = $request->validate([
        'judul' => 'required|max:255',
        'konten' => 'required'
    ]);

    Post::create($validated);
}
```

---

## 9. CRUD (Create, Read, Update, Delete)

**Create:**

```php
Post::create(['judul' => 'Judul', 'konten' => 'Isi']);
```

**Read:**

```php
$posts = Post::all();
```

**Update:**

```php
$post = Post::find($id);
$post->update(['judul' => 'Update']);
```

**Delete:**

```php
Post::destroy($id);
```

---

## 10. Artisan CLI

| Perintah                      | Fungsi                 |
| ----------------------------- | ---------------------- |
| `php artisan make:model`      | Membuat model          |
| `php artisan make:controller` | Membuat controller     |
| `php artisan make:migration`  | Membuat file migrasi   |
| `php artisan migrate`         | Menjalankan migrasi    |
| `php artisan route:list`      | Melihat daftar routing |

---

## 11. Environment dan Konfigurasi

Gunakan file `.env` untuk konfigurasi:

```env
APP_NAME=Laravel
APP_DEBUG=true
APP_URL=http://localhost:8000
```

Ambil nilai dengan `config('app.name')`.

---

## 12. Laravel Mix dan Vite

### Jalankan Vite:

```bash
npm install
npm run dev
```

Gunakan `@vite` di Blade:

```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

---

## 13. Autentikasi Sederhana (Laravel Breeze)

```bash
composer require laravel/breeze --dev
php artisan breeze:install
npm install && npm run dev
php artisan migrate
```

---

## 14. Seeder, Factory dan Faker

**Buat seeder:**

```bash
php artisan make:seeder PostSeeder
```

**Buat factory:**

```bash
php artisan make:factory PostFactory --model=Post
```

**Generate data dummy:**

```php
Post::factory()->count(10)->create();
```

---

## 15. Middleware

Middleware memfilter request sebelum masuk controller.

**Buat Middleware:**

```bash
php artisan make:middleware CekRole
```

---

## 16. Request & Response

```php
public function simpan(Request $request)
{
    $nama = $request->input('nama');
    return response()->json(['sukses' => true]);
}
```

---

## 17. File Upload dan Storage

```php
$file = $request->file('foto');
$path = $file->store('public/foto');
```

Gunakan:

```bash
php artisan storage:link
```

---

## 18. Relasi Database

-   `hasOne`, `hasMany`, `belongsTo`, `belongsToMany`

Contoh:

```php
class Post extends Model {
  public function user() {
    return $this->belongsTo(User::class);
  }
}
```

---

## 19. Debugging dan Error Handling

-   Gunakan `dd()`, `dump()`
-   Cek file log di `storage/logs/`
-   Pastikan `.env` `APP_DEBUG=true`

---

## 20. Tips Lanjut Belajar Laravel

-   Gunakan Laravel Tinker
-   Coba Laravel Livewire/Inertia untuk SPA
-   Pelajari Service Container, Events, Queue, Notification
-   Ikuti [Laravel From Scratch - Laracasts](https://laracasts.com/series/laravel-10-from-scratch)

---

> Disusun oleh: Daniel Ferryal
> Untuk pelatihan Laravel 12 bersama teman 🌱
