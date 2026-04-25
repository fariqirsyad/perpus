<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-Perpus Pintar | Digital Library</title>

    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/bootstrap-icons-1.13.1/bootstrap-icons.css') ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="<?= base_url('uploads/users/eperpus1.png') ?>" >
   <style>
    :root {
        --sidebar-width: 260px;
        --sidebar-bg: #ffffff;
        --content-bg: #f8fafc; 
        --accent-teal: #06b6d4; 
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --border-color: #f1f5f9; /* Garis lebih halus */
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--content-bg);
        margin: 0;
        display: flex;
        height: 100vh;
        overflow: hidden;
        color: var(--text-dark);
    }

    /* Sidebar Clean & Slim */
    .sidebar {
        width: var(--sidebar-width);
        background: var(--sidebar-bg);
        color: var(--text-dark);
        display: flex;
        flex-direction: column;
        z-index: 10;
        border-right: 1px solid #eef2f6;
    }

    /* Area Konten Utama */
    .content-wrapper {
        flex-grow: 1;
        padding: 40px 60px; /* Padding lebih luas agar terlihat lega */
        overflow-y: auto;
    }

    /* Card Wrapper untuk Tabel (Rahasia Tampilan Elegan) */
    .table-container {
        background: white;
        border-radius: 16px;
        padding: 10px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03); /* Shadow sangat halus */
        border: 1px solid #f1f5f9;
    }

    /* Kustomisasi Tabel */
    .table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .table thead th {
        background-color: #ffffff; /* Putih saja agar bersih */
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 1px;
        border-bottom: 1px solid var(--border-color);
        padding: 20px;
    }

    .table tbody td {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
        font-size: 14px;
        color: #334155;
    }

    /* Menghilangkan border terakhir agar tidak double */
    .table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Badge Status Pastel */
    .badge {
        padding: 6px 14px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 11px;
    }

    /* Perbaikan Input Cari agar menyatu */
    .form-control {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 10px 15px;
        font-size: 14px;
    }
    
    .form-control:focus {
        border-color: var(--accent-teal);
        box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.1);
    }
</style>

<style>
    :root {
        --sidebar-width: 280px; /* Sedikit dilebarkan biar teks menu lega */
        --sidebar-bg: #ffffff;
        --content-bg: #f8fafc; 
        --accent-teal: #06b6d4; 
        --text-dark: #1e293b;
        --text-muted: #64748b;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--content-bg);
        /* Ukuran dasar kita naikkan */
        font-size: 15px; 
        color: var(--text-dark);
        display: flex;
        height: 100vh;
        overflow: hidden;
    }

    /* Ukuran Judul Halaman */
    h2 {
        font-size: 28px; /* Lebih tegas */
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    /* Ukuran Menu Sidebar */
    .nav-link-teal {
        font-size: 15px !important; /* Dari 13px ke 15px */
        padding: 14px 24px !important;
    }

    /* Kustomisasi Tabel (Data Jadi Lebih Jelas) */
    .table thead th {
        font-size: 13px; /* Header tetap agak kecil tapi bold */
        letter-spacing: 0.8px;
        padding: 18px 20px;
    }

    .table tbody td {
        font-size: 15px; /* Isi data naik ke 15px */
        padding: 20px; /* Ruang baris lebih lega */
        color: #334155;
    }

    /* Ukuran Tombol & Input */
    .btn, .form-control {
        font-size: 15px; 
        padding: 10px 20px;
    }

    /* Keterangan Role di Bawah Sidebar */
    .info-user-name {
        font-size: 15px !important;
        font-weight: 600;
    }
    .info-user-role {
        font-size: 13px !important;
    }

.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}

</style>

</head>

<body>
    <div class="sidebar">
        <div class="p-4 mb-2 text-center" style="border-bottom: 1px solid #e2e8f0;">
            <h5 class="fw-bold mb-0" style="letter-spacing: 1px; color: var(--accent-teal);">
                <i class="bi bi-book-half me-2"></i>E-PERPUS PINTAR
            </h5>
            <small style="color: var(--text-muted); font-size: 10px; text-transform: uppercase; letter-spacing: 2px;">Smart Library System</small>
        </div>

        <?php include(APPPATH . 'Views/layouts/menu.php'); ?>
    </div>

    <div class="content-wrapper">
        <?= $this->renderSection('content') ?>
    </div>

    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>