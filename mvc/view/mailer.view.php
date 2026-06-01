<!DOCTYPE html>
<html lang='id' xmlns:v='urn:schemas-microsoft-com:vml' xmlns:o='urn:schemas-microsoft-com:office:office'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <!-- Metadata untuk memberi tahu aplikasi email bahwa template ini mendukung Light & Dark -->
    <meta name='color-scheme' content='light dark'>
    <meta name='supported-color-schemes' content='light dark'>
    <title><?php echo htmlspecialchars($data['subject']); ?></title>
    
    <style>
        /* Gaya Khusus Deteksi Dark Mode (Apple Mail, iOS, dll) */
        @media (prefers-color-scheme: dark) {
            body, .body-wrapper {
                background-color: #121212 !important;
            }
            .card-wrapper {
                background-color: #1e1e1e !important;
                border-color: #2d2d2d !important;
            }
            .dark-text {
                color: #f8f9fa !important;
            }
            .dark-muted {
                color: #a0aec0 !important;
            }
            .header-bg {
                background-color: #0a46a6 !important; /* Biru yang sedikit lebih redup untuk dark mode */
            }
        }

        /* Atribut Khusus untuk Outlook App */
        [data-ogsc] .body-wrapper { background-color: #121212 !important; }
        [data-ogsc] .card-wrapper { background-color: #1e1e1e !important; border-color: #2d2d2d !important; }
        [data-ogsc] .dark-text { color: #f8f9fa !important; }
        [data-ogsc] .dark-muted { color: #a0aec0 !important; }
    </style>
</head>
<body style='margin: 0; padding: 0; font-family: system-ui, -apple-system, \"Segoe UI\", Roboto, sans-serif; background-color: #f8f9fa;'>

    <!-- Body Wrapper -->
    <table class='body-wrapper' width='100%' cellpadding='0' cellspacing='0' border='0' style='background-color: #f8f9fa; padding: 40px 0;'>
        <tr>
            <td align='center'>
                
                <!-- Card Container -->
                <table class='card-wrapper' width='100%' max-width='600' cellpadding='0' cellspacing='0' border='0' style='max-width: 600px; background-color: #ffffff; border: 1px solid #dee2e6; border-radius: 0.5rem; overflow: hidden;'>
                    
                    <!-- Header (Bootstrap Primary) -->
                    <tr>
                        <td class='header-bg' style='background-color: #0d6efd; padding: 22px; text-align: left;'>
                            <h2 style='color: #ffffff; margin: 0; font-size: 1.5rem; font-weight: 600; letter-spacing: 0.5px;'><?php echo $data['title']; ?></h2>
                        </td>
                    </tr>
                    
                    <!-- Content Body -->
                    <tr>
                        <td style='padding: 40px 32px;'>
                            <?php echo $data['content']; ?>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td class='card-wrapper' style='background-color: #f8f9fa; padding: 24px; text-align: center; border-top: 1px solid #dee2e6; font-size: 0.815rem;'>
                            <p class='dark-muted' style='margin: 0 0 8px 0; color: #6c757d;'>&copy; <?php echo $data['tahun']; ?>. All Rights Reserved.</p>
                            <p class='dark-muted' style='margin: 0; color: #8c949c; font-size: 0.75rem;'>Anda menerima email ini karena terdaftar di sistem kami.</p>
                        </td>
                    </tr>
                    
                </table><!-- /Card Container -->

            </td>
        </tr>
    </table><!-- /Body Wrapper -->

</body>
</html>