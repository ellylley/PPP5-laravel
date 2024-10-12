<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        .profile-info {
            margin-bottom: 20px;
        }
        .profile-info label {
            font-weight: bold;
        }
        .profile-img {
            display: block;
            margin: 0 auto 20px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: none;
        }
        .card-body {
            padding: 20px;
        }
        .card-title {
            font-size: 1.5em;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .form-group label {
            font-weight: 600;
        }
        .input-group {
            position: relative;
        }
        .input-group .btn {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            border-radius: 0 5px 5px 0;
        }
        .form-control {
            border-radius: 5px;
            padding-right: 40px; /* Make space for the button */
        }
        .btn-login {
            background-color: #007bff;
            color: #fff;
            font-size: 1em;
            font-weight: bold;
        }
        .btn-login:hover {
            background-color: #0056b3;
        }
        .alert {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
        }
        .alert-danger {
            color: #dc3545;
            border: 1px solid #dc3545;
            background-color: #f8d7da;
        }
        .alert-success {
            color: #28a745;
            border: 1px solid #28a745;
            background-color: #d4edda;
        }
    </style>
</head>
<body>

<!-- Error Alert -->
<div class="container">
    <div id="errorAlert" class="alert alert-danger" style="display: none;"></div>

<!-- Profile Section -->
<div class="container">
    <h1>Profile Information</h1>
    <form id="sellerForm" novalidate action="{{ route('home.aksieprofile') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <img src="{{ asset('images/' . $user->foto) }}" class="profile-img" alt="Profile Picture">
        <div class="profile-info">
            <label for="foto">Foto Profile:</label>
            <input type="file" id="foto" class="form-control" name="foto">
            <input type="hidden" name="old_foto" value="{{ $user->foto }}">
        </div>
        <div class="profile-info">
            <label for="name">Nama:</label>
            <input name="nama" type="text" class="form-control" id="nama" value="{{ $user->nama_user }}">
        </div>
        <input name="id" type="hidden" class="form-control" id="id" value="{{ $user->id_user }}">

        <button class="btn btn-warning btn-sm" id="editProfileBtn">Save Edit</button>
        <button class="btn btn-secondary btn-sm" id="changePasswordBtn">Change Password</button>
    </form>
</div>

<!-- Change Password Section -->
<div class="container card" id="changePasswordCard">
    <div class="card-body">
        <h5 class="card-title">Ganti Password</h5>
        <form novalidate method="post" action="{{ route('home.aksi_changepass') }}" class="row g-3">
            @csrf
            <div class="mb-3">
                <label for="inputOldPassword" class="form-label">Old Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="inputOldPassword" name="old">
                    <button class="btn btn-outline-secondary" type="button" id="toggleOldPassword">
                        <i class="fas fa-eye-slash" id="iconOldPassword"></i>
                    </button>
                </div>
            </div>

            <div class="mb-3">
                <label for="inputNewPassword" class="form-label">New Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="inputNewPassword" name="new">
                    <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword">
                        <i class="fas fa-eye-slash" id="iconNewPassword"></i>
                    </button>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-warning btn-sm">Confirm</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Function to toggle password visibility
    function togglePasswordVisibility(inputId, iconId) {
        var passwordField = document.getElementById(inputId);
        var icon = document.getElementById(iconId);

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            passwordField.type = 'password';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    }

    document.getElementById('toggleOldPassword').addEventListener('click', function() {
        togglePasswordVisibility('inputOldPassword', 'iconOldPassword');
    });

    document.getElementById('toggleNewPassword').addEventListener('click', function() {
        togglePasswordVisibility('inputNewPassword', 'iconNewPassword');
    });

    // Function to show/hide Change Password form
    document.getElementById('changePasswordBtn').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default action from the button
        var changePasswordCard = document.getElementById('changePasswordCard');
        if (changePasswordCard.style.display === 'none' || changePasswordCard.style.display === '') {
            changePasswordCard.style.display = 'block';
        } else {
            changePasswordCard.style.display = 'none';
        }
    });

    // Ensure Change Password form remains visible on error
    document.addEventListener('DOMContentLoaded', function() {
        @if (session('error'))
            var errorAlert = document.getElementById('errorAlert');
            errorAlert.innerText = "{{ session('error') }}";
            errorAlert.style.display = 'block';
        @endif

        @if (session('success'))
            var alert = document.createElement('div');
            alert.className = 'alert alert-success';
            alert.role = 'alert';
            alert.innerText = "{{ session('success') }}";
            document.querySelector('.container').prepend(alert);
        @endif
    });
</script>

</body>
</html>
