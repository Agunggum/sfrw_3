<?php
require_once vendors('logcarbon/logcarbon');

use app\Models\Users;
use app\Models\Forgotlink;
use app\Models\Accessapp;

class Loginmodel extends Controller {

	function loginformmodel($uri) { 
		if(isset($_POST['login']) and $_POST['login']=="MASUK"){

            $password = $_POST['password'];
            $username = anti_injection($_POST['username']);
            $_SESSION['username_form'] = $username;

            $data = PembangunKueri::tabel(Users::schematable())
                        ->pilih('fullname', 'username', 'password', 'active', 'role')
                        ->dimana('username', '=', $username)
                        ->pertama();
            
            if(!$data){
                Logcarbon::carbonlog($username." :: login denied : not found","logsignin");
            
                $_SESSION['error'] = "true";
                alert('warning', 'Attention..!', '<i class="fa fa-clock-o"></i> login denied.', $uri);
            }
            elseif($data['active'] == 'N'){
                Logcarbon::carbonlog($username." :: login denied : inactive","logsignin");
            
                $_SESSION['error'] = "true";
                alert('warning', 'Attention..!', '<i class="fa fa-clock-o"></i> You are no longer able to log into this system.', $uri);
            }
            elseif(!password_verify($password, $data['password'])){
                $_SESSION['usertrue'] = $username;

                Logcarbon::carbonlog($username." :: login denied : wrong password","logsignin");
            
                $_SESSION['error'] = "true";
                alert('warning', 'Attention..!', '<i class="fa fa-clock-o"></i> Please re-check the Username/ email or Password you entered, make sure the data you entered is correct.', $uri);
            }
            else{
                
                $_SESSION['error'] = "true";
                if(ENVIRONMENT == 'maintenance' and $data['role'] != 'administrator'){
                    alert('warning', 'Attention..!', '<i class="fa fa-clock-o"></i> Login denied. system is under maintenance.', $uri);
                }else{
                    // Regenerate session ID to prevent session fixation
                    session_regenerate_id(true);

                    $_SESSION['username'] = $data['username'];
                    $_SESSION['fullname'] = $data['fullname'];
                    $_SESSION['accessme'] = $data['role'];

                    Logcarbon::carbonlog($username." :: login success","logsignin");

                    //membuat sesi timeout
                    $_SESSION['timeout']=WAKTUINI+KADALUARSA;
                    $_SESSION['timelog']=WAKTUINI+KADALUARSA+13;
                    $_SESSION['error'] = "false";
                    alert('success', '', '<i class="fa fa-check animated flip" style="font-size:4.5em;"></i></p><p><strong>..Waiting to sign in..</strong>', $uri);
                }
            }

        }
	}

    function forgotformmodel($uri) { 
		if(isset($_POST['login']) and $_POST['login']=="MASUK"){

            $email = anti_injection($_POST['email']);

            $data = PembangunKueri::tabel(Users::schematable())
                        ->pilih('fullname', 'email', 'username', 'password', 'active')
                        ->dimana('email', '=', $email)
                        ->pertama();
            
            if(!$data){
                Logcarbon::carbonlog($email." :: forgot denied : not found","logsignin");

                alert('warning', 'Alert forgot password', 'Please re-check the Email you entered, make sure the data you entered is correct.', BASEURL.'forgot-password');
            }
            elseif($data['active'] == 'N'){
                Logcarbon::carbonlog($email." :: forgot denied : inactive","logsignin");

                alert('warning', 'Alert forgot password', 'You are no longer able to log into this system.', BASEURL.'forgot-password');
            }
            else{

                // JIka sedang maintenance data segera lock sistem
                if(ENVIRONMENT == 'maintenance'){
                    alert('warning', 'Alert forgot password', 'System is under maintenance.', BASEURL.'forgot-password');
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
                        $jmail = PembangunKueri::tabel(Users::schematable())
                                     ->pilih('email', 'fullname')
                                     ->dimana('email', '=', $email)
                                     ->pertama();

                        $subject = MAILTITLE." - Forgot Password";
                        // Kirim email dalam format HTML
                        $headers  = "From: no reply ".MAILTITLE." <".MAILUSER.">\r\n";
                        $headers .= "Content-type: text/html\r\n";
                        $mailreq = "$jmail[email]";
                        $pesan = "Dear $jmail[fullname],";
                        $pesan .= "<br/><br/>Anda telah melakukan lupa password, berikut adalah link nya.<br/><br/><em>You have forgotten your password, here is the link.</em><br/><br/>Link :<br/><a href='".$linkforgot."'>".$linkforgot."</a><br/><br/><br/><br/>Thanks,<br/>System <strong>".WEBTITLE."</strong>";
                        mail($mailreq,$subject,$pesan,$headers);
                        //End mail
                    }

                    alert('success', 'Alert forgot password', 'Please check your email to reset your password.', BASEURL.'forgot-password');
                }
            }
        }
	}

    function forgotnewformmodel($uri,$s) { 
		if(isset($_POST['login']) and $_POST['login']=="MASUK"){

            $pass1 = $_POST['password1'];
            $pass2 = $_POST['password2'];

            $data = PembangunKueri::tabel(Forgotlink::schematable())
                        ->dimana('target_link', '=', $s)
                        ->pertama();
            
            if(!$data){
                Logcarbon::carbonlog("Unknown :: forgot denied : link not found","logsignin");

                alert('warning', 'Alert forgot password', 'Please re-check your link an email.', BASEURL.'forgot-password?s='.$s);
            }
            elseif($pass1 != $pass2){
                Logcarbon::carbonlog($data['email']." :: forgot denied : password not match","logsignin");

                alert('warning', 'Alert forgot password', 'Password does not match.', BASEURL.'forgot-password?s='.$s);
            }
            else{

                // JIka sedang maintenance data segera lock sistem
                if(ENVIRONMENT == 'maintenance'){
                    alert('warning', 'Alert forgot password', 'Login denied. system is under maintenance.', BASEURL.'forgot-password?s='.$s);
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
                        $mailreq = "$jmail[email]";
                        $pesan = "Dear $jmail[fullname],";
                        $pesan .= "<br/><br/>Lupa password berhasil dilakukan, dan password sudah diubah.<br/><br/><em>Forgot password was successfully done, and the password has been changed.</em><br/><br/><br/><br/>Thanks,<br/>System <strong>".WEBTITLE."</strong>";
                        mail($mailreq,$subject,$pesan,$headers);
                        //End mail
                    }

                    alert('success', 'Alert forgot password', 'Password update successful.', BASEURL);
                }
            }
        }
	}
    
    function signoutmodel($nik,$log){
        //jika waktu sekarang kurang dari sesi timeout
        if(BASESESSION!="" and $log > $_SESSION['timeout']){
            Logcarbon::carbonlog($nik." :: signout","logsignin");

            session_destroy();
            require_once view('pagelogout');
            echo "<script>setTimeout(function () { window.location.href = '".BASEURL."signout?log=".$_SESSION['timeout']."'; }, 2000);</script>";
        }else{
            require_once view('pagelogin');
            echo "<script>setTimeout(function () { window.location.href = '".BASEURL."'; }, 2000);</script>";
        }
    }
    
}
?>