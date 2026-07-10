<!DOCTYPE html>
<html lang='id' xmlns:v='urn:schemas-microsoft-com:vml' xmlns:o='urn:schemas-microsoft-com:office:office'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta name='color-scheme' content='light dark'>
    <meta name='supported-color-schemes' content='light dark'>
    <title><?php echo htmlspecialchars($data['subject']); ?></title>
    
    <style>
        @media (prefers-color-scheme: dark) {
            /* Background utama menjadi gelap gulita */
            body, .body-wrapper {
                background-color: #121212 !important;
            }
            /* Card tempat konten menjadi abu-abu gelap */
            .card-wrapper {
                background-color: #1e1e1e !important;
                border-color: #2d2d2d !important;
            }
            /* Header disesuaikan agar tidak terlalu silau */
            .header-bg {
                background-color: #0a46a6 !important; 
            }
            /* MEMAKSA SEMUA TEKS DI KONTEN MENJADI PUTIH/TERANG */
            .content-body, .content-body *, .dark-text {
                color: #ffffff !important;
            }
            /* Background footer saat dark mode */
            .footer-bg {
                background-color: #151515 !important;
                border-top-color: #2d2d2d !important;
            }
            /* Teks sekunder/muted di footer */
            .dark-muted {
                color: #a0aec0 !important;
            }
        }

        [data-ogsb] .body-wrapper { background-color: #121212 !important; }
        [data-ogsb] .card-wrapper { background-color: #1e1e1e !important; border-color: #2d2d2d !important; }
        [data-ogsb] .header-bg { background-color: #0a46a6 !important; }
        [data-ogsc] .body-wrapper { background-color: #121212 !important; }
        [data-ogsc] .card-wrapper { background-color: #1e1e1e !important; border-color: #2d2d2d !important; }
        [data-ogsc] .header-bg { background-color: #0a46a6 !important; }
        [data-ogsc] .content-body, [data-ogsc] .content-body *, [data-ogsc] .dark-text { color: #ffffff !important; }
        [data-ogsc] .footer-bg { background-color: #151515 !important; border-top-color: #2d2d2d !important; }
        [data-ogsc] .dark-muted { color: #a0aec0 !important; }
    </style>
</head>
<body style='margin: 0; padding: 0; font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif; background-color: #f8f9fa;'>

    <table class='body-wrapper' width='100%' cellpadding='0' cellspacing='0' border='0' style='background-color: #f8f9fa; padding: 40px 0;'>
        <tr>
            <td align='center'>
                
                <table class='card-wrapper' width='100%' cellpadding='0' cellspacing='0' border='0' style='max-width: 600px; width: 100%; background-color: #ffffff; border: 1px solid #dee2e6; border-radius: 0.5rem; overflow: hidden;'>
                    
                    <tr>
                        <td class='header-bg' style='background-color: #0d6efd; padding: 22px; text-align: left;'>
                            <h2 style='color: #ffffff; margin: 0; font-size: 1.5rem; font-weight: 600; letter-spacing: 0.5px;'><?php echo $data['title']; ?></h2>
                        </td>
                    </tr>
                    
                    <tr>
                        <td class='content-body' style='padding: 40px 32px; color: #212529; font-size: 1rem; line-height: 1.6; text-align: left;'>
                            <?php echo $data['content']; ?>
                        </td>
                    </tr>
                    
                    <tr>
                        <td class='footer-bg' style='background-color: #f8f9fa; padding: 24px; text-align: center; border-top: 1px solid #dee2e6; font-size: 0.815rem;'>
                            <p class='dark-muted' style='margin: 0 0 8px 0; color: #6c757d;'>&copy; <?php echo $data['tahun']; ?>. All Rights Reserved.</p>
                            <p class='dark-muted' style='margin: 0; color: #8c949c; font-size: 0.75rem;'>You are receiving this email because you are registered in our system.</p>
                        </td>
                    </tr>
                    
                </table></td>
        </tr>
    </table></body>
</html>