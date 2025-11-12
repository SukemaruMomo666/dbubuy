<?php
session_start();
require '../config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {

    $action = $_POST['action'];

    // Login
    if ($action == 'login') {

        $no_whatsapp = $_POST['no_whatsapp'];
        $password_input = $_POST['password'];


        if (empty($no_whatsapp) || empty($password_input)) {
            header("Location: /pages/login.php?error=kolom_kosong");
            exit;
        }

        $sql = "SELECT * FROM users WHERE no_whatsapp = ?";
        $stmt = $koneksi->prepare($sql);
        
        if ($stmt === false) {
            die("Error preparing statement: " . $koneksi->error);
        }

        $stmt->bind_param("s", $no_whatsapp);
        $stmt->execute();
        $result = $stmt->get_result();

        // Cek Hasil Query
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Verifikasi Password
            if (password_verify($password_input, $user['password'])) {
                session_regenerate_id(true);

                // Buat Session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nama'] = $user['nama_lengkap'];
                $_SESSION['is_login'] = true;

                // Cek kategori user 
                if ($user['kategori'] == 'admin') {
                    $_SESSION['kategori'] = 'admin';
                    $_SESSION['is_admin'] = true;
                    header("Location: /backend/admin/index_admin.php");
                } else {
                    $_SESSION['kategori'] = 'customer';
                    $_SESSION['is_admin'] = false;
                    header("Location: /index.php"); 
                }
                exit;

            } else {
                header("Location: /pages/login.php?error=password_salah");
                exit;
            }

        } else {
            header("Location: /pages/login.php?error=user_tidak_ditemukan");
            exit;
        }

        $stmt->close();
    
    // REGISTRASI
    } elseif ($action == 'register') {

        // Ambil data dari form registrasi
        $nama_lengkap   = $_POST['nama_lengkap'];
        $no_whatsapp    = $_POST['no_whatsapp'];
        $password_input = $_POST['password'];
        $password_repeat = $_POST['password_repeat'];
        
        //set 'kategori' default sebagai 'customer'
        $kategori_default = 'customer'; 

        // Validasi Input
        if (empty($nama_lengkap) || empty($no_whatsapp) || empty($password_input) || empty($password_repeat)) {
            // PERBAIKAN: Gunakan absolute path
            header("Location: /register.php?error=kolom_kosong");
            exit;
        }

        if ($password_input !== $password_repeat) {
            header("Location: /register.php?error=password_tidak_cocok");
            exit;
        }
        
        if (strlen($password_input) < 8) {
            header("Location: /register.php?error=password_terlalu_pendek");
            exit;
        }

        // Cek Duplikasi Nomor WhatsApp
        $sql_check = "SELECT id FROM users WHERE no_whatsapp = ?";
        $stmt_check = $koneksi->prepare($sql_check);
        $stmt_check->bind_param("s", $no_whatsapp);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            header("Location: /register.php?error=no_wa_terdaftar");
            exit;
        }
        $stmt_check->close();

        $hashed_password = password_hash($password_input, PASSWORD_BCRYPT); //semua pw yg di input akan di hash menggunakan bycript

        $sql_insert = "INSERT INTO users (nama_lengkap, no_whatsapp, password, kategori) VALUES (?, ?, ?, ?)";
        
        $stmt_insert = $koneksi->prepare($sql_insert);
        if ($stmt_insert === false) {
            die("Error preparing statement: " . $koneksi->error);
        }

        $stmt_insert->bind_param("ssss", $nama_lengkap, $no_whatsapp, $hashed_password, $kategori_default);

        if ($stmt_insert->execute()) {
            header("Location: /pages/login.php?status=registrasi_sukses");
            exit;
        } else {
            // register gagal
            header("Location: /register.php?error=gagal_database");
            exit;
        }
        
        $stmt_insert->close();
    
    } else {
        header("Location: /pages/login.php?error=invalid_action");
        exit;
    }
    $koneksi->close();

} else {
    header("Location: /pages/login.php");
    exit;
}
?>