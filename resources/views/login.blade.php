
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Login Page</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header img {
            width: 80px;
            margin-bottom: 15px;
        }
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        .btn-login {
            width: 100%;
            padding: 10px;
            font-weight: 600;
        }
        .forgot-password {
            text-align: right;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-header">
                {{-- <img src="https://via.placeholder.com/80" alt="Logo" class="img-fluid"> --}}
                <h3>Welcome Back</h3>
                <p class="text-muted">Please enter your credentials to login</p>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" placeholder="Enter your email" required>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" placeholder="Enter your password" required>
            </div>
            
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
            
            <button type="button" class="btn btn-primary btn-login" id="btn">Login</button>
            
            <div class="forgot-password">
                <a href="#" class="text-decoration-none">Forgot password?</a>
            </div>
            
            <div class="text-center mt-3">
                <p class="text-muted">Don't have an account? <a href="#" class="text-decoration-none" >Sign up</a></p>
            </div>
        </div>
    </div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
       $(document).ready(function () {
            $('#btn').click(function () {
                var email = $('#email').val();
                var ps = $('#password').val();

                $.ajax({
                    url:"/submit-login",
                    type:"post",
                    data:{
                        email:email,
                        ps : ps,
                        _token: '{{ csrf_token() }}'
                    },
                    success:function (rs) {
                        if (rs == "User Found") {
alert("User Found");
window.location = "/user-list";
}   
                        
                    },
                    error: function (e) {
                        console.error(e);
                        
                    }
                });
                
            });
       });


    </script>
</body>
</html>