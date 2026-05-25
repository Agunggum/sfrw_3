<?php
require_once vendors('logcarbon/logcarbon');

use app\Models\Users;
use app\Models\Forgotlink;
use app\Models\Accessapp;

class Loginmodel extends Controller {

	function loginformmodel($uri) { 
		$password = $_POST['password'];
        $username = $_POST['username']; // Menggunakan raw input karena PembangunKueri sudah melakukan escape
        $_SESSION['username_form'] = anti_injection($username); // Tetap gunakan anti_injection untuk tampilan

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
            alert('warning', 'Attention..!', '<i class="fa fa-lock"></i> login denied.', $uri.'login');
            return;
        }
            
        if($data['active'] == 'N'){
            Logcarbon::carbonlog($username." :: login denied : inactive","logsignin");
            
            $_SESSION['error'] = "true";
            alert('warning', 'Attention..!', '<i class="fa fa-lock"></i> You are no longer able to log into this system.', $uri.'login');
            return;
        }
            
        if(!password_verify($password, $data['password'])){
            $_SESSION['usertrue'] = $username;

            Logcarbon::carbonlog($username." :: login denied : wrong password","logsignin");
            
            $_SESSION['error'] = "true";
            alert('warning', 'Attention..!', '<i class="fa fa-lock"></i> Please re-check the Username/ email or Password you entered, make sure the data you entered is correct.', $uri.'login');
            return;
        }
            
        // Login sukses
        if(ENVIRONMENT == 'maintenance' and $data['role'] != 'administrator'){
            alert('warning', 'Attention..!', '<i class="fa fa-lock"></i> Login denied. system is under maintenance.', $uri.'login');
            return;
        }else{
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);

            $_SESSION['username'] = $data['username'];
            $_SESSION['fullname'] = $data['fullname'];
            $_SESSION['accessme'] = $data['role'];

            Logcarbon::carbonlog($username." :: login success","logsignin");

            // membuat sesi timeout
            $_SESSION['timeout'] = WAKTUINI + KADALUARSA;
            $_SESSION['timelog'] = WAKTUINI + KADALUARSA + 13;
            $_SESSION['error'] = "false";
                
            // Hapus data form sementara
            unset($_SESSION['username_form']);
                
            // Cek apakah ada URL tujuan yang disimpan sebelumnya
            $redirect_url = $uri; // Default ke BASEURL yang dikirim dari controller
            if(isset($_SESSION['intended_url'])){
                $redirect_url = $_SESSION['intended_url'];
                unset($_SESSION['intended_url']);
            }
                
            alert('success', '', '<i class="fa fa-check animated flip" style="font-size:4.5em;"></i></p><p><strong>..Waiting to sign in..</strong>', $redirect_url);
        }
	}

    function forgotformmodel($uri) { 
        $email = anti_injection($_POST['email']);

        $data = PembangunKueri::tabel(Users::schematable())
                ->pilih('fullname', 'email', 'username', 'password', 'active')
                ->dimana('email', '=', $email)
                ->pertama();
            
        if(!$data){
            Logcarbon::carbonlog($email." :: forgot denied : not found","logsignin");
            alert('warning', 'Alert forgot password', 'Please re-check the Email you entered, make sure the data you entered is correct.', BASEURL.'forgot-password');
            return;
        }
            
        if($data['active'] == 'N'){
            Logcarbon::carbonlog($email." :: forgot denied : inactive","logsignin");
            alert('warning', 'Alert forgot password', 'You are no longer able to log into this system.', BASEURL.'forgot-password');
            return;
        }

        // JIka sedang maintenance data segera lock sistem
        if(ENVIRONMENT == 'maintenance'){
            alert('warning', 'Alert forgot password', 'System is under maintenance.', BASEURL.'forgot-password');
            return;
        }
        else{
            Logcarbon::carbonlog($email." :: send forgot link success","logsignin");

            //membuat sesi timeout
            $linkforgotbase = md5($email."-".DATEWMIN);
            $linkforgot = BASEURL."forgot-password?s=".$linkforgotbase;
            $endTime = date('Y-m-d H:i:s', strtotime("+15 minutes", strtotime(DATEWMIN)));
                
            PembangunKueri::tabel(Forgotlink::schematable())->sisipkan([
                'email' => $email,
                'target_link' => $linkforgotbase,
                'end_time' => $endTime,
                'created_at' => DATEWMIN,
                'updated_at' => DATEWMIN
            ]);

            if(MAILACTIVATE == 'true'){
                //mail concept
                $subject = MAILTITLE." - Forgot Password";
                // Kirim email dalam format HTML
                $headers  = "From: no reply ".MAILTITLE." <".MAILUSER.">\r\n";
                $headers .= "Content-type: text/html\r\n";
                $mailreq = $data['email'];
                $pesan = "Dear " . $data['fullname'] . ",";
                $pesan .= "<br/><br/>Anda telah melakukan lupa password, berikut adalah link nya.<br/><br/><em>You have forgotten your password, here is the link.</em><br/><br/>Link :<br/><a href='".$linkforgot."'>".$linkforgot."</a><br/><br/><br/><br/>Thanks,<br/>System <strong>".WEBTITLE."</strong>";
                mail($mailreq,$subject,$pesan,$headers);
                //End mail
            }

            alert('success', 'Alert forgot password', 'Please check your email to reset your password.', BASEURL.'forgot-password');
        }
	}

    function forgotnewformmodel($uri,$s) { 
        $pass1 = $_POST['password1'];
        $pass2 = $_POST['password2'];

        $data = PembangunKueri::tabel(Forgotlink::schematable())
                ->dimana('target_link', '=', $s)
                ->pertama();
            
        if(!$data){
            Logcarbon::carbonlog("Unknown :: forgot denied : link not found","logsignin");
            alert('warning', 'Alert forgot password', 'Please re-check your link an email.', BASEURL.'forgot-password?s='.$s);
            return;
        }
            
        if($pass1 != $pass2){
            Logcarbon::carbonlog($data['email']." :: forgot denied : password not match","logsignin");
            alert('warning', 'Alert forgot password', 'Password does not match.', BASEURL.'forgot-password?s='.$s);
            return;
        }

        // JIka sedang maintenance data segera lock sistem
        if(ENVIRONMENT == 'maintenance'){
            alert('warning', 'Alert forgot password', 'Login denied. system is under maintenance.', BASEURL.'forgot-password?s='.$s);
            return;
        }
        else{
            Logcarbon::carbonlog($data['email']." :: forgot success (new password)","logsignin");
                
            $hashed_password = password_hash($pass2, PASSWORD_DEFAULT);

            PembangunKueri::tabel(Users::schematable())
                ->dimana('email', '=', $data['email'])
                ->perbarui(['password' => $hashed_password]);

            if(MAILACTIVATE == 'true'){
                //mail concept
                $jmail = PembangunKueri::tabel(Users::schematable())
                    ->pilih('username', 'fullname', 'email')
                    ->dimana('email', '=', $data['email'])
                    ->pertama();

                $subject = MAILTITLE." - Forgot Password";
                // Kirim email dalam format HTML
                $headers  = "From: no reply ".MAILTITLE." <".MAILUSER.">\r\n";
                $headers .= "Content-type: text/html\r\n";
                $mailreq = $jmail['email'];
                $pesan = "Dear " . $jmail['fullname'] . ",";
                $pesan .= "<br/><br/>Lupa password berhasil dilakukan, dan password sudah diubah.<br/><br/><em>Forgot password was successfully done, and the password has been changed.</em><br/><br/><br/><br/>Thanks,<br/>System <strong>".WEBTITLE."</strong>";
                mail($mailreq,$subject,$pesan,$headers);
                //End mail
            }

            alert('success', 'Alert forgot password', 'Password update successful.', BASEURL);
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
        
        // Alihkan ke halaman login
        alihkan(BASEURL . 'login');
    }
    
}
?>