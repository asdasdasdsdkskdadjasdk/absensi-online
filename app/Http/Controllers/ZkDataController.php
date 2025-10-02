<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ZkDataController extends Controller
{
    public function index()
    {
        try {
            // 1. Ambil SATU BARIS PERTAMA saja dari tabel
            $firstEmployee = DB::connection('pgsql_zkteco')
                            ->table('personnel_employee')
                            ->first(); // ->first() akan mengambil 1 baris dengan semua kolomnya

            // 2. Jika tidak ada data sama sekali
            if (is_null($firstEmployee)) {
                dd("Tabel 'personnel_employee' berhasil diakses, tetapi isinya kosong.");
            }

            // 3. Tampilkan seluruh isi dari baris pertama tersebut di layar
            dd($firstEmployee);

        } catch (\Exception $e) {
            // Jika koneksi gagal, tampilkan pesan error
            dd("Koneksi ke database ZKTeco Gagal. Error: " . $e->getMessage());
        }
    }
}
```

### Apa yang Terjadi Sekarang?

1.  **Hapus Halaman View:** Kode ini tidak lagi mencoba memuat halaman `zk_employees.blade.php`. Jadi, kita tidak perlu membuatnya dulu.
2.  **`->first()`**: Perintah ini mengambil hanya satu record pertama dari tabel, tetapi menyertakan **semua kolom** yang ada di record tersebut.
3.  **`dd($firstEmployee)`**: Ini adalah bagian kuncinya. Hasil dari pengambilan data akan ditampilkan langsung di browser Anda dalam format yang sangat mudah dibaca.

### Cara Menjalankannya

1.  Pastikan server Laravel Anda berjalan.
2.  Buka browser dan akses lagi URL: `http://127.0.0.1:8000/zk-employees`.

Anda akan melihat halaman putih dengan daftar semua kolom dan nilainya untuk satu karyawan pertama. Tampilannya akan seperti ini:

[Gambar dari output `dd()` Laravel]

```
stdClass {#1452 ▼
  +"id": 1
  +"emp_code": "1001"
  +"first_name": "Budi"
  +"last_name": "Santoso"
  +"name": "Budi Santoso"
  +"email": "budi.s@example.com"
  +"department_id": 15
  ... (dan semua kolom lainnya)
}

