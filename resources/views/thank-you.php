<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $thank_you_info['page_title'] ?? 'নিডাস- ধন্যবাদ'; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Meta Pixel Code if available -->
    <?php if (!empty($meta_pixel_code)): ?>
        <?php echo $meta_pixel_code; ?>
    <?php endif; ?>
    <style>
        body {
            background-color: #f8e6d8;
            font-family: 'Hind Siliguri', sans-serif;
            padding: 0;
            margin: 0;
            width: 100%;
            overflow-x: hidden;
        }
        .container {
            width: 90%;
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 15px;
            box-sizing: border-box;
        }
        .header-banner {
            background-color: #f8e6d8;
            padding: 20px 0;
            width: 100%;
        }
        .main-title {
            font-family: 'Hind Siliguri', sans-serif;
            font-size: 30px;
            font-weight: 800;
            line-height: 1.6em;
            color: #007A11;
            text-align: center;
            margin-bottom: 15px;
        }
        .main-title span {
            color: #EE5E11;
        }
        .thank-you-section {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 20px 15px;
            margin-bottom: 30px;
        }
        .thank-you-card {
            border: 2px dashed #007A11;
            border-radius: 10px;
            background-color: #fff;
            overflow: visible;
            width: 100%;
            display: block;
            min-height: 100px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 20px 15px;
        }
        .success-icon {
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
        }
        .success-icon svg {
            color: #007A11;
        }
        .thank-you-title {
            font-family: 'Hind Siliguri', sans-serif;
            font-size: 24px;
            font-weight: 800;
            line-height: 1.3em;
            color: #007A11;
            margin-bottom: 20px;
            text-align: center;
        }
        .thank-you-text {
            font-size: 18px;
            line-height: 1.5;
            margin-bottom: 25px;
            text-align: center;
        }
        .home-button {
            background-color: #03D629;
            color: #000;
            font-family: 'Hind Siliguri', sans-serif;
            font-size: 33px;
            font-weight: 700;
            border-radius: 10px;
            padding: 25px 35px;
            line-height: 1.5;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
            white-space: normal;
            text-align: center;
            margin: 0 auto;
        }
        .home-button:hover {
            background-color: #00b322;
            color: #000;
            text-decoration: none;
        }
        .home-button svg {
            margin-left: 10px;
            flex-shrink: 0;
        }
        .contact-section {
            margin-bottom: 30px;
        }
        .contact-card {
            border: 2px dashed #007A11;
            border-radius: 10px;
            background-color: #fff;
            overflow: visible;
            width: 100%;
            display: block;
            min-height: 100px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 20px 15px;
        }
        .contact-title {
            font-family: 'Hind Siliguri', sans-serif;
            font-size: 24px;
            font-weight: 800;
            line-height: 1.3em;
            color: #007A11;
            margin-bottom: 20px;
            text-align: center;
        }
        .contact-details {
            padding: 10px;
            text-align: center;
        }
        .contact-details p {
            margin-bottom: 10px;
            font-size: 16px;
            line-height: 1.5;
        }
        .contact-details a {
            color: #007A11;
            font-weight: bold;
            text-decoration: none;
        }
        .contact-details a:hover {
            text-decoration: underline;
        }
        .button-container {
            text-align: center;
            margin-top: 20px;
        }
        footer {
            color: #666;
            font-size: 14px;
            padding: 15px 0;
            background-color: #f8e6d8;
            text-align: center;
        }
        @media (max-width: 768px) {
            .container {
                width: 100%;
                padding: 0 10px;
            }
            .main-title {
                font-size: 22px;
                line-height: 1.4em;
            }
            .thank-you-title, .contact-title {
                font-size: 20px;
            }
            .thank-you-text {
                font-size: 16px;
            }
            .home-button {
                font-size: 22px;
                padding: 15px 25px;
                width: 100%;
                max-width: 300px;
            }
            .contact-details p {
                font-size: 14px;
            }
            .thank-you-section, .contact-section {
                padding: 15px 10px;
            }
            .thank-you-card, .contact-card {
                padding: 15px 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="py-3 text-center">
            <div class="header-banner">
                <h1 class="main-title"><?php echo $thank_you_info['main_heading'] ?? '<span>নিডাস</span> - অর্ডার সম্পন্ন হয়েছে'; ?></h1>
            </div>
        </header>

        <section class="thank-you-section py-5">
            <div class="thank-you-card">
                <div class="success-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </svg>
                </div>
                <h2 class="thank-you-title"><?php echo $thank_you_info['success_message'] ?? 'আপনার অর্ডার সফলভাবে গ্রহণ করা হয়েছে!'; ?></h2>
                <p class="thank-you-text"><?php echo $thank_you_info['thank_you_message'] ?? 'আমাদের একজন প্রতিনিধি শীঘ্রই আপনার সাথে যোগাযোগ করবেন। আপনার সহযোগিতার জন্য ধন্যবাদ।'; ?></p>
                <div class="button-container">
                    <a href="<?php echo $config['base_url']; ?>" class="home-button"><?php echo $thank_you_info['home_button_text'] ?? 'হোম পেজে ফিরে যান'; ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="8 12 12 16 16 12"></polyline>
                            <line x1="12" y1="8" x2="12" y2="16"></line>
                        </svg>
                    </a>
                </div>
            </div>
        </section>

        <section class="contact-section py-4">
            <div class="contact-card">
                <h3 class="contact-title"><?php echo $thank_you_info['contact_section_title'] ?? 'যেকোনো প্রয়োজনে যোগাযোগ করুন'; ?></h3>
                <div class="contact-details">
                    <p><strong>WhatsApp:</strong> <a href="https://wa.me/<?php echo str_replace(' ', '', $thank_you_info['whatsapp_number'] ?? '01990888222'); ?>"><?php echo $thank_you_info['whatsapp_number'] ?? '01990888222'; ?></a> 
                    <p><strong>Phone:</strong> <a href="tel:<?php echo $thank_you_info['phone_number'] ?? '0 1990-888222'; ?>"><?php echo $thank_you_info['phone_number'] ?? '0 1990-888222'; ?></a></p>
                </div>
            </div>
        </section>

        <footer class="py-3 text-center">
            <p>&copy; <?php echo date('Y'); ?> 
            <a href="<?php echo $thank_you_info['footer_link'] ?? 'https://www.prachinebangla.com'; ?>" target="_blank" rel="noopener noreferrer">
            <?php echo $thank_you_info['footer_text'] ?? 'Prachin Bangla Limited'; ?>
            </a>
            </p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo $config['base_url']; ?>/js/script.js"></script>
</body>
</html>
