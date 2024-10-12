<body>
    <div id="auth">
        <div class="container">
            <div class="row">
                <div class="col-md-5 col-sm-12 mx-auto">
                    <div class="card pt-4">
                        <div class="card-body">
                            <div class="text-center mb-5">
                                <div style="text-align: center;">
                                    <img src="{{ asset('images/' . $setting->logo) }}" style="width: 120px; height: auto;">
                                </div>
                                <h5>Sign In</h5>
                            </div>
                            <form class="row g-3 needs-validation" novalidate action="{{ route('home.aksilogin') }}" method="POST" onsubmit="return validateForm();">
                                @csrf <!-- Add CSRF token for security -->
                                <div class="form-group position-relative has-icon-left">
                                    <label for="username">Nama User</label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control" id="nama" name="nama" required>
                                        <div class="form-control-icon">
                                            <i data-feather="user"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group position-relative has-icon-left">
                                    <div class="clearfix">
                                        <label for="password">Password</label>
                                    </div>
                                    <div class="position-relative">
                                        <input type="password" class="form-control" id="password" name="password" required>
                                        <span class="toggle-password position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;" onclick="togglePassword()">
                                            <i data-feather="eye"></i>
                                        </span>
                                        <div class="form-control-icon">
                                            <i data-feather="lock"></i>
                                        </div>
                                    </div>
                                    <!-- Tempat pesan error akan ditampilkan -->
                                    @if (session('error'))
                                        <div id="login-error" class="text-danger mt-2">
                                            {{ session('error') }}
                                        </div>
                                    @endif
                                </div>

                                <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                                <div id="recaptcha-container" class="g-recaptcha" data-sitekey="6LdLhiAqAAAAACazV6qYX_Y6L9bMo0aC8Q1jRJM-"></div>

                                <div id="offline-captcha" style="display:none;">
                                    <p>Please enter the characters shown below:</p>
                                    <img src="{{ route('captcha.generate') }}" alt="CAPTCHA">
                                    <input type="text" name="backup_captcha" class="form-control mt-2" placeholder="Enter CAPTCHA" required>
                                </div>

                                <br/>
                                <div class="clearfix">
                                    <button class="btn btn-primary float-end">Login</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>

    <script>
    function validateForm() {
        var isOffline = !navigator.onLine;
        var backupCaptcha = document.querySelector('input[name="backup_captcha"]').value;
        var recaptchaResponse = grecaptcha.getResponse();

        if (isOffline) {
            if (backupCaptcha.trim().length === 0) {
                alert('Please complete the offline CAPTCHA.');
                return false;
            }
        } else {
            if (recaptchaResponse.length === 0) {
                alert('Please complete the online CAPTCHA.');
                return false;
            }
        }
        return true;
    }

    window.addEventListener('load', function() {
        if (!navigator.onLine) {
            document.getElementById('recaptcha-container').style.display = 'none';
            document.getElementById('offline-captcha').style.display = 'block';
        } else {
            document.getElementById('recaptcha-container').style.display = 'block';
            document.getElementById('offline-captcha').style.display = 'none';
        }
    });

    function togglePassword() {
        var passwordField = document.getElementById('password');
        var passwordIcon = document.querySelector('.toggle-password i');

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            passwordIcon.setAttribute('data-feather', 'eye-off');
        } else {
            passwordField.type = 'password';
            passwordIcon.setAttribute('data-feather', 'eye');
        }
        feather.replace();
    }
    </script>

    <style>
    .toggle-password {
        cursor: pointer;
        color: #6c757d;
    }
    </style>
</body>
