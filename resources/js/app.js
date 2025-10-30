import './bootstrap'; // Ini harus mengimpor dan memulai Alpine

// Import SweetAlert2
import Swal from 'sweetalert2';
// Membuatnya global agar bisa diakses dari script Blade
window.Swal = Swal;

// --- TAMBAHKAN INI UNTUK CKEDITOR ---
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

// Buat ClassicEditor tersedia secara global agar Alpine bisa memanggilnya
window.ClassicEditor = ClassicEditor;
// --- AKHIR TAMBAHAN CKEDITOR ---
