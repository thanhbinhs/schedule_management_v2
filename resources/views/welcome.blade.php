<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phenikaa Schedule</title>
    <style>
        *{
            font-size: 62.5%;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .phenikaa-schedule-container {
            background-color: #fff;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .header-wrapper {
            display: flex;
            width: 100%;
            gap: 20px;
            padding-right: 50px;
            font-family: Roboto, sans-serif;
            font-weight: 700;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        @media (max-width: 991px) {
            .header-wrapper {
                max-width: 100%;
                flex-wrap: wrap;
            }
        }
        .logo {
            aspect-ratio: 1.59;
            object-fit: contain;
            object-position: center;
            width: 161px;
            max-width: 100%;
        }
        .nav-container {
            align-self: center;
            display: flex;
            flex-direction: row;
        }
        .nav-items {
            display: flex;
            margin-left: 14px;
            align-items: center;
            gap: 30px;
        }
        @media (max-width: 991px) {
            .nav-items {
                margin-left: 10px;
            }
        }
        .nav-link {
            color: #233871;
            font-size: 24px;
            align-self: stretch;
            flex-grow: 1;
            margin: auto 0;
            text-decoration: none;
        }
        .nav-icon {
            aspect-ratio: 1.67;
            object-fit: contain;
            object-position: center;
            width: 50px;
            align-self: stretch;
            margin: auto 0;
        }
        .login-btn {
            border-radius: 20px;
            background-color: #233871;
            align-self: stretch;
            font-size: 16px;
            color: #fff;
            padding: 16px 11px;
            text-decoration: none;
            text-align: center;
        }
        .underline {
            background-color: #233871;
            display: flex;
            margin-top: 20px;
            width: 153px;
            height: 6px;
        }
        .main-content {
            background-color: #f3f0f0;
            display: flex;
            width: 100%;
            flex-direction: column;
            align-items: flex-start;
            justify-content: center;
            padding: 135px 37px;
        }
        @media (max-width: 991px) {
            .main-content {
                max-width: 100%;
                padding: 100px 20px;
            }
        }
        .content-wrapper {
            margin-bottom: -32px;
            width: 100%;
            max-width: 1148px;
        }
        @media (max-width: 991px) {
            .content-wrapper {
                max-width: 100%;
                margin-bottom: 10px;
            }
        }
        .content-columns {
            gap: 20px;
            display: flex;
        }
        @media (max-width: 991px) {
            .content-columns {
                flex-direction: column;
                align-items: stretch;
                gap: 0;
            }
        }
        .text-column {
            display: flex;
            flex-direction: column;
            line-height: normal;
            width: 50%;
            margin-left: 0;
        }
        @media (max-width: 991px) {
            .text-column {
                width: 100%;
            }
        }
        .text-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-family: Roboto, sans-serif;
            color: #233871;
            font-weight: 700;
            text-align: center;
        }
        @media (max-width: 991px) {
            .text-content {
                max-width: 100%;
                margin-top: 40px;
            }
        }
        .main-title {
            align-self: stretch;
            font: 400 48px Revalia, sans-serif;
        }
        @media (max-width: 991px) {
            .main-title {
                max-width: 100%;
                font-size: 40px;
            }
        }
        .subtitle {
            font-size: 40px;
            margin-top: 31px;
        }
        @media (max-width: 991px) {
            .subtitle {
                max-width: 100%;
            }
        }
        .start-btn {
            border-radius: 20px;
            background-color: #233871;
            margin-top: 37px;
            width: 147px;
            max-width: 100%;
            font-size: 20px;
            color: #fff;
            padding: 12px 18px;
            text-decoration: none;
            text-align: center;
        }
        .image-column {
            display: flex;
            flex-direction: column;
            line-height: normal;
            width: 50%;
            margin-left: 20px;
        }
        @media (max-width: 991px) {
            .image-column {
                width: 100%;
            }
        }
        .main-image {
            aspect-ratio: 1.47;
            object-fit: contain;
            object-position: center;
            width: 100%;
            flex-grow: 1;
        }
        @media (max-width: 991px) {
            .main-image {
                max-width: 100%;
                margin-top: 40px;
            }
        }
        .visually-hidden {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }
    </style>
</head>
<body>
    <section class="phenikaa-schedule-container">
        <header class="header-wrapper">
            <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/c369edd412a40d01822169c4edca17c4d2e2188ea5bcc35321886dcfa4d7143a?placeholderIfAbsent=true&apiKey=a732e21b3afa4acd8291ecb50549d348" alt="Phenikaa Schedule Logo" class="logo" />
            <nav class="nav-container">
                <div class="nav-items">
                    <a href="#" class="nav-link">GIỚI THIỆU</a>
                    <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/63d64c4d6b23137ccebc78b2a1b92a7fb1530af77a89b87307f3e6dffcf411f6?placeholderIfAbsent=true&apiKey=a732e21b3afa4acd8291ecb50549d348" alt="" class="nav-icon" />
                    <a href="/login" class="login-btn">Đăng nhập</a>
                </div>
            </nav>
        </header>
        
        <main class="main-content">
            <div class="content-wrapper">
                <div class="content-columns">
                    <div class="text-column">
                        <div class="text-content">
                            <h1 class="main-title">Phenikaa Schedule</h1>
                            <p class="subtitle">Ứng dụng xây dựng <br /> thời khóa biểu toàn trường</p>
                            <a href="/login" class="start-btn">BẮT ĐẦU</a>
                        </div>
                    </div>
                    <div class="image-column">
                        <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/ed0e15ff021dc117abc07c95c73bbe6257dbbb6098a02819c762a49379e9b3d6?placeholderIfAbsent=true&apiKey=a732e21b3afa4acd8291ecb50549d348" alt="Phenikaa Schedule Interface" class="main-image" />
                    </div>
                </div>
            </div>
        </main>
    </section>
</body>
</html>
