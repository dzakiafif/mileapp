# Laravel MongoDB MileApp

## Cara Menjalankan Project
```bash
- salin .env.example menjadi .env
- ubah *DB_CONNECTION* dari mysql ke mongodb
- edit DB_MONGO_* dan sesuaikan dengan local anda
- jalankan *composer install* tunggu sampai selesai
- jalankan *php artisan serve* untuk projectnya 
```

## Seeder
```bash
jalankan *php artisan db:seed* untuk location seeder 
```

## Dokumentasi
```bash
untuk dokumentasi api terletak di dalam folder postman di root projectnya
```

## Note
```bash
untuk perbedaan PUT dan PATCH adalah terletak di resourcenya. misal ada object {first_name, last_name} ketika pakai PUT, lalu saya kirim cuman last_name: afif maka resourcenya keluarnya hanya {last_name} sedangkan ketika pakai PATCH, lalu saya kirimkan hal yg sama yaitu last_name: afif maka resourcenya akan tetap jadi {first_name, last_name}
```