<?php
require_once vendors('logcarbon/logcarbon');
require_once services('Validator');
require_once services('Pengirimmail');

use app\Models\Users;
use app\Models\Forgotlink;

class Loginmodel extends Controller {

	function loginformmodel($uri) { 
        $requestData = $_POST;

		$password = $_POST['password'];
        $username = $_POST['username']; // Menggunakan raw input karena PembangunKueri sudah melakukan escape
        $_SESSION['username_form'] = anti_injection($username); // Tetap gunakan anti_injection untuk tampilan

        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];
        if (!Validator::validate($requestData, $rules)) {
            alert('warning', 'Attention..!', Validator::getErrorsString(), $uri.'login');
            return;
        }

        // Pastikan tabel Users ada sebelum query
        $table = Users::schematable();
            
        $data = PembangunKueri::tabel($table)
                ->pilih('fullname', 'username', 'password', 'active', 'role')
                ->dimana('username', '=', $username)
                ->atauDimana('email', '=', $username)
                ->pertama();
            
        if(!$data){
            Logcarbon::carbonlog($username." :: login denied : not found","logsignin");
            
            $_SESSION['error'] = "true";
            if (isset($_COOKIE['user_language']) && $_COOKIE['user_language'] == 'id') {
                alert('warning', 'Perhatian..!', 'Masuk ditolak. Harap periksa kembali Nama Pengguna/email atau Kata Sandi yang Anda masukkan.',  $uri.'login');
            }else{
                alert('warning', 'Attention..!', 'login denied. Please double-check the username/email or password you entered', $uri.'login');
            }
            return;
        }
            
        if($data['active'] == 'N'){
            Logcarbon::carbonlog($username." :: login denied : inactive","logsignin");
            
            $_SESSION['error'] = "true";
            if (isset($_COOKIE['user_language']) && $_COOKIE['user_language'] == 'id') {
                alert('warning', 'Perhatian..!', 'Anda tidak dapat lagi masuk ke sistem ini.', $uri.'login');
            }else{
                alert('warning', 'Attention..!', 'You are no longer able to log into this system.', $uri.'login');
            }
            return;
        }
            
        if(!password_verify($password, $data['password'])){
            $_SESSION['usertrue'] = $username;

            Logcarbon::carbonlog($username." :: login denied : wrong password","logsignin");
            
            $_SESSION['error'] = "true";
            if (isset($_COOKIE['user_language']) && $_COOKIE['user_language'] == 'id') {
                alert('warning', 'Perhatian..!', 'Masuk ditolak. Harap periksa kembali Nama Pengguna/email atau Kata Sandi yang Anda masukkan.', $uri.'login');
            }else{
                alert('warning', 'Attention..!', 'Login denied. Please double-check the username/email or password you entered.', $uri.'login');
            }
            return;
        }
            
        // Login sukses
        if(ENVIRONMENT == 'maintenance' and $data['role'] != 'administrator'){
            if (isset($_COOKIE['user_language']) && $_COOKIE['user_language'] == 'id') {
                alert('warning', 'Perhatian..!', 'Masuk ditolak. Sistem sedang dalam pemeliharaan.', $uri.'login');
            }else{
                alert('warning', 'Attention..!', 'Login denied. system is under maintenance.', $uri.'login');
            }
            return;
        }else{
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);

            $_SESSION['username'] = $data['username'];
            $_SESSION['fullname'] = $data['fullname'];
            $_SESSION['accessme'] = $data['role'];

            Logcarbon::carbonlog($username." :: login success","logsignin");

            // ==========================================
            // LOGIKHA FITUR REMEMBER ME
            // ==========================================
            if (isset($_POST['ingat-saya']) && $_POST['ingat-saya'] == 'Ingat saya') {
                // Membuat token acak yang aman
                $rememberToken = bin2hex(random_bytes(32));
                $cookieValue = $data['username'] . ':' . $rememberToken;
                
                // Menentukan masa berlaku cookie (misal: 30 hari)
                $expiryTime = time() + (30 * 24 * 60 * 60); 

                // Set cookie (Disarankan menggunakan httponly dan secure jika menggunakan HTTPS)
                setcookie('remember_me', $cookieValue, $expiryTime, "/", "", false, true);

                // Opsional: Jika Anda punya kolom `remember_token` di database, simpan hash-nya di sini
                // PembangunKueri::tabel($table)->dimana('username', '=', $data['username'])->perbarui(['remember_token' => password_hash($rememberToken, PASSWORD_DEFAULT)]);
            }
            // ==========================================

            // membuat sesi timeout
            $_SESSION['timeout'] = WAKTUINI + KADALUARSA;
            $_SESSION['timelog'] = WAKTUINI + KADALUARSA + 13;
            $_SESSION['error'] = "false";
                
            // Hapus data form sementara
            unset($_SESSION['username_form']);
                
            // Cek apakah ada URL tujuan yang disimpan sebelumnya
            $redirect_url = $uri.'dashboard'; // Default ke BASEURL yang dikirim dari controller
            if(isset($_SESSION['intended_url'])){
                $redirect_url = $_SESSION['intended_url'];
                unset($_SESSION['intended_url']);
            }
                
            //alert('success', '', '<i class="bi bi-check animated flip" style="font-size:4.5em;"></i></p><p><strong>..Waiting to sign in..</strong>', $redirect_url);

            arahkan($redirect_url);
        }
	}

    function forgotformmodel($uri) { 
        if (!Validator::validate($_POST, ['email' => 'required|email'])) {
            alert('warning', 'Attention..!', Validator::getErrorsString(), BASEURL.'forgot-password');
            return;
        }

        $email = anti_injection($_POST['email']);

        $data = PembangunKueri::tabel(Users::schematable())
                ->pilih('fullname', 'email', 'username', 'password', 'active')
                ->dimana('email', '=', $email)
                ->pertama();
            
        if(!$data){
            Logcarbon::carbonlog($email." :: forgot denied : not found","logsignin");
            if (isset($_COOKIE['user_language']) && $_COOKIE['user_language'] == 'id') {
                alert('warning', 'Perhatian..!', 'Email yang Anda masukkan tidak ditemukan.', BASEURL.'forgot-password');
            }else{
                alert('warning', 'Attention..!', 'Please re-check the Email you entered, make sure the data you entered is correct.', BASEURL.'forgot-password');
            }
            return;
        }
            
        if($data['active'] == 'N'){
            Logcarbon::carbonlog($email." :: forgot denied : inactive","logsignin");
            if (isset($_COOKIE['user_language']) && $_COOKIE['user_language'] == 'id') {
                alert('warning', 'Perhatian..!', 'Akun Anda telah di-block.', BASEURL.'forgot-password');
            }else{
                alert('warning', 'Attention..!', 'You are no longer able to log into this system.', BASEURL.'forgot-password');
            }
            return;
        }

        // JIka sedang maintenance data segera lock sistem
        if(ENVIRONMENT == 'maintenance'){
            if (isset($_COOKIE['user_language']) && $_COOKIE['user_language'] == 'id') {
                alert('warning', 'Perhatian..!', 'Sistem sedang dalam pemeliharaan.', BASEURL.'forgot-password');
            }else{
                alert('warning', 'Attention..!', 'System is under maintenance.', BASEURL.'forgot-password');
            }
            return;
        }
        else{
            Logcarbon::carbonlog($email." :: send forgot link success","logsignin");

            //membuat sesi timeout
            $linkforgotbase = md5($email."-".DATEWMIN);
            $linkforgot = BASEURL."forgot-password/".$linkforgotbase;
            $endTime = date('Y-m-d H:i:s', strtotime("+15 minutes", strtotime(DATEWMIN)));
                
            PembangunKueri::tabel(Forgotlink::schematable())->sisipkan([
                'email' => $email,
                'target_link' => $linkforgotbase,
                'end_time' => $endTime,
                'created_at' => DATEWMIN,
                'updated_at' => DATEWMIN
            ]);

            $nama_user       = $data['fullname'];
            $subject         = "Permintaan perubahan kata sandi";
            $email_tujuan    = $data['email'];

            $content = "
                <p>Hai ".$nama_user."!</p>
                <p>Kami mendeteksi permintaan kata sandi baru Anda pada ".daydateandtime_indo(date('Y-m-d H:i'))." WIB.</p>
                <p>Berikut adalah link reset password Anda: <a href='".$linkforgot."'>".$linkforgot."</a></p>
                <p>Jika ini bukan Anda, segera amankan akun Anda.</p>
                <p>Salam dari Aplikasi ".MAILTITLE."</p>
            ";
            $message = Pengirimmail::sentMail($subject, $email_tujuan, $nama_user, $content);

            if (isset($_COOKIE['user_language']) && $_COOKIE['user_language'] == 'id') {
                alert('success', 'Perhatian..!', 'Perubahan kata sandi Anda telah di kirim ke email Anda. Silahkan cek email Anda.', BASEURL.'forgot-password');
            }else{
                alert('success', 'Attention..!', 'Please check your email to reset your password.'.$message, BASEURL.'forgot-password');
            }
        }
	}

    function forgotnewformmodel($uri,$s) { 
        $rules = [
            'password1' => 'required|minlength:8',
            'password2' => 'required|matches:password1'
        ];

        if (!Validator::validate($_POST, $rules)) {
            alert('warning', 'Attention..!', Validator::getErrorsString(), BASEURL.'forgot-password?s='.$s);
            return;
        }

        $pass1 = $_POST['password1'];
        $pass2 = $_POST['password2'];

        $data = PembangunKueri::tabel(Forgotlink::schematable())
                ->dimana('target_link', '=', $s)
                ->pertama();
        
        $dataUser = PembangunKueri::tabel(Users::schematable())
                ->dimana('email', '=', $data['email'])
                ->pertama();
            
        if(!$data){
            Logcarbon::carbonlog("Unknown :: forgot denied : link not found","logsignin");
            if (isset($_COOKIE['user_language']) && $_COOKIE['user_language'] == 'id') {
                alert('warning', 'Perhatian..!', 'Link reset password Anda tidak valid ditemukan.', BASEURL.'forgot-password?s='.$s);
            }else{
                alert('warning', 'Attention..!', 'Please re-check your link an email.', BASEURL.'forgot-password?s='.$s);
            }
            return;
        }
            
        // JIka sedang maintenance data segera lock sistem
        if(ENVIRONMENT == 'maintenance'){
            if (isset($_COOKIE['user_language']) && $_COOKIE['user_language'] == 'id') {
                alert('warning', 'Perhatian..!', 'Sistem sedang dalam pemeliharaan.', BASEURL.'forgot-password?s='.$s);
            }else{
                alert('warning', 'Attention..!', 'System is under maintenance.', BASEURL.'forgot-password?s='.$s);
            }
            return;
        }
        else{
            Logcarbon::carbonlog($data['email']." :: forgot success (new password)","logsignin");
                
            $hashed_password = password_hash($pass2, PASSWORD_DEFAULT);

            PembangunKueri::tabel(Users::schematable())
                ->dimana('email', '=', $data['email'])
                ->perbarui(['password' => $hashed_password]);

            $nama_user       = $dataUser['fullname'];
            $subject         = "Perubahan kata sandi";
            $email_tujuan    = $data['email'];

            $content = "
                <p>Hai ".$nama_user."!</p>
                <p>Kami mendeteksi perubahan kata sandi Anda pada ".daydateandtime_indo(date('Y-m-d H:i'))." WIB.</p>
                <p>Jika ini bukan Anda, segera amankan akun Anda.</p>
                <p>Salam dari Aplikasi ".MAILTITLE."</p>
            ";

            $message = Pengirimmail::sentMail($subject, $email_tujuan, $nama_user, $content);

            if (isset($_COOKIE['user_language']) && $_COOKIE['user_language'] == 'id') {
                alert('success', 'Perhatian..!', 'Perubahan kata sandi Anda telah di perbarui.', BASEURL);
            }else{
                alert('success', 'Attention..!', 'Password update successful.'.$message, BASEURL);
            }
        }
	}
    
    function signoutmodel(){
        $username = $_SESSION['username'] ?? 'Unknown';
        Logcarbon::carbonlog($username." :: signout success","logsignin");

        // Hapus semua session
        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();
        setcookie('remember_me', '', time() - 3600, '/');
        
        // Alihkan ke halaman login
        arahkan('/login');
    }
    
}
?>