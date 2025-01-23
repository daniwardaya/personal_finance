# Personal Finance

Aplikasi test untuk memenuhi persyaratan melamar kerja.

## Dokumentasi

### 1. Instruksi untuk Menjalankan Aplikasi

-    rubah (.env.example ) menjadi ( .env )
-    php artisan migrate
-    php artisan migrate --env=testing
-    php artisan key:generate
-    php artisan serve
-    php artisan test
-    php artisan l5-swagger:generate (akses di http://localhost:8000/api/documentation)

#### Persyaratan

-   PHP >= 8,1
-   Composer
-   MySQL

##### Penjelasan pendekatan desain backend dan alasan pemilihan teknologi
Pendekatan desain backend dalam pengembangan aplikasi bertujuan untuk membangun sistem yang efisien, terstruktur, dan mudah dikembangkan lebih lanjut. Beberapa prinsip dasar dalam desain backend meliputi:

Separation of Concerns (SoC): Memisahkan logika aplikasi ke dalam komponen yang berbeda untuk menjaga keterbacaan dan pemeliharaan kode.
Scalability: Sistem dirancang untuk dapat berkembang seiring dengan pertumbuhan pengguna dan beban aplikasi.
Maintainability: Memastikan kode mudah untuk diperbaiki, diperbarui, atau ditingkatkan tanpa menyebabkan masalah besar.
Security: Menggunakan teknik dan praktik yang tepat untuk melindungi data dan aplikasi dari ancaman.
Alasan memilih Laravel:

Framework MVC (Model-View-Controller): Laravel mengadopsi pola desain MVC, memisahkan logika aplikasi dan antarmuka pengguna, yang membuatnya lebih terstruktur dan mudah dipelihara.
Ekosistem lengkap: Laravel dilengkapi dengan berbagai fitur built-in seperti autentikasi, otorisasi, routing, migration database, dan lainnya, menghemat waktu pengembangan.
Keamanan: Laravel menawarkan proteksi terhadap berbagai ancaman keamanan seperti SQL injection, Cross-Site Scripting (XSS), dan Cross-Site Request Forgery (CSRF).
Eloquent ORM: Laravel menyediakan ORM yang kuat untuk interaksi dengan database menggunakan sintaksis yang bersih dan mudah dipahami.
Dukungan komunitas dan dokumentasi: Laravel memiliki komunitas yang aktif dan dokumentasi yang sangat lengkap, memudahkan pengembang untuk belajar dan mengatasi masalah.
Dengan semua keunggulan ini, Laravel cocok untuk pengembangan aplikasi backend yang kompleks, dengan struktur kode yang jelas, aman, dan dapat dengan mudah dikelola.


