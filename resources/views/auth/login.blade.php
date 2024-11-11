<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .login-page {
            background-color: #fff;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            color: #233871;
            text-align: center;
            font: 700 24px Roboto, sans-serif;
        }

        .login-container {
            display: flex;
            flex-direction: column;
            position: relative;
            min-height: 720px;
            width: 100%;
            align-items: center;
            justify-content: center;
            padding: 107px 80px;
        }

        .background-image {
            position: absolute;
            inset: 0;
            height: 100%;
            width: 100%;
            object-fit: cover;
            object-position: center;
            /* z-index: -1; */
        }

        .login-form {
            position: relative;
            background-color: #fff;
            display: flex;
            width: 856px;
            max-width: 100%;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 29px 80px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .form-content {
            display: flex;
            width: 632px;
            max-width: 100%;
            flex-direction: column;
        }

        .logo {
            aspect-ratio: 4.5;
            object-fit: contain;
            object-position: center;
            width: 216px;
            align-self: center;
            max-width: 100%;
            margin-bottom: 20px;
        }

        .input-label {
            align-self: start;
            margin-top: 20px;
        }

        .input-field {
            border-radius: 20px;
            background-color: #f3f3f3;
            margin-top: 10px;
            font-size: 16px;
            color: #000;
            font-weight: 400;
            padding: 12px 15px;
            border: 1px solid #ccc;
            width: 100%;
            outline: none;
        }

        .password-container {
            border-radius: 20px;
            background-color: #f3f3f3;
            margin-top: 10px;
            font-size: 16px;
            color: #000;
            font-weight: 400;
            padding: 12px 15px;
            border: 1px solid #ccc;
            width: 100%;
            outline: none;
            display: flex;
            justify-content: space-between;
            position: relative;
        }

        .password-input {
            flex-grow: 1;
            border: none;
            outline: none;
            background: transparent;
            font-size: 16px;
            color: #000;
        }

        .password-toggle {
            width: 24px;
            height: 24px;
            cursor: pointer;
            position: absolute;
            right: 20px;
            border: none;
            top: 7px;

        }

        .remember-login {
            display: flex;
            align-items: center;
            margin-top: 16px;
            gap: 8px;
        }

        .checkbox {
            width: 16px;
            height: 16px;
            margin: auto 0;
        }

        .login-button {
            border-radius: 20px;
            background-color: #233871;
            margin-top: 40px;
            width: 100%;
            color: #fff;
            padding: 12px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
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

        @media (max-width: 991px) {
            .login-container {
                padding: 50px 20px;
            }

            .login-form {
                padding: 20px;
            }

            .login-button {
                padding: 10px 20px;
            }
        }
    </style>
</head>

<body>
    <section class="login-page">
        <div class="login-container">
            <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/1df84fe6fe73ac0294e467b7a4417f807df980fc48c7b728104587c9be37d719?placeholderIfAbsent=true&apiKey=a732e21b3afa4acd8291ecb50549d348" alt="Background" class="background-image" />
            <form class="login-form" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-content">
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif
                    <label for="username" class="input-label">Tên đăng nhập:</label>
                    <input type="text" id="username" name="username" class="input-field" placeholder="Nhập tên đăng nhập" required />

                    <label for="password" class="input-label">Mật khẩu:</label>
                    <div class="password-container">
                        <input type="password" id="password" name="password" class="password-input" placeholder="Nhập mật khẩu" required />
                        <button type="button" class="password-toggle" onclick="togglePasswordVisibility()">
                            <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/c58a786a05ea30c9c075d43dba0ac61cf559a2065fea9130e59a905935445863?placeholderIfAbsent=true&apiKey=a732e21b3afa4acd8291ecb50549d348" alt="Toggle password visibility" />
                        </button>
                    </div>

                    <div class="remember-login">
                        <input type="checkbox" id="remember" name="remember" class="checkbox" />
                        <label for="remember">Nhớ đăng nhập ?</label>
                    </div>

                    <button type="submit" class="login-button">Đăng nhập</button>
                </div>
            </form>
        </div>
    </section>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        }
    </script>

</body>

</html>