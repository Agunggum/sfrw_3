<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Pengirimmail {

    public static function sentMail($judul, $emailPenerima, $namaPenerima, $isiPesan) {
        $tahun_sekarang = date('Y');

        ob_start();
        include tampilan('mailer', [
            $data['subject'] = $judul,
            $data['title'] = WEBTITLETOP,
            $data['content'] = $isiPesan,
            $data['tahun'] = $tahun_sekarang,
        ]);
        $html_email = ob_get_clean();

         if(MAILACTIVATE == 'true'){
            if(MAILSECURE == 'true'){
                $mail = new PHPMailer(true);
                try {
                    $mail->IsSMTP();
                    $mail->SMTPSecure = '';
                    $mail->Host = MAILHOST;
                    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                    $mail->Port = MAILPORT;
                    $mail->SMTPAuth = SMTPAUTH;
                    $mail->Username = MAILUSER;
                    $mail->Password = MAILPASS;
                    $mail->SetFrom(MAILSENT, MAILTITLE);
                    $mail->AddAddress($emailPenerima, $namaPenerima);
                    // --- KONTEN EMAIL ---
                    $mail->isHTML(true); // Set format email ke HTML
                    $mail->Subject = $judul." - ".MAILTITLE;
                    $mail->Body    = $html_email;
                    $mail->Send();
                    $message = "<br><br>Mail sent successfully";
                } catch (Exception $e) {
                    $message = "<br><br>Mail could not be sent.";
                    Logcarbon::carbonlog("{$mail->ErrorInfo}", "error");
                }
            }else{
                $subjectMail = $judul." - ".MAILTITLE;
                // Kirim email dalam format HTML
                $headersMail  = "From: ".MAILTITLE." <".MAILSENT.">\r\n";
                $headersMail .= "Content-type: text/html\r\n";
                mail($emailPenerima, $subjectMail, $html_email, $headersMail);
                $message = "<br><br>Mail sent successfully";
            }
        }else{
            $message = "<br><em>Notifikasi belum aktif!</em>";
        }

        return $message;
    }
    
}