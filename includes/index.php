<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Expenditure - Login</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.1.0/mdb.min.css" rel="stylesheet"/>
<link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet"/>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.1.0/mdb.min.js"></script>
<script src="js/auth.js"></script>
</head>
<body>

<section class="vh-100">
  <div class="container-fluid h-custom">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-md-9 col-lg-6 col-xl-5 d-none d-md-block">
        <img src="images/Wavy_Tech-28_Single-10.jpg" class="img-fluid" alt="Sample image">
      </div>
      <div class="col-12 col-md-8 col-lg-6 col-xl-4 offset-xl-1">
        <form id="loginForm" class="login-form-container">
          <h2 class="fw-bold mb-2 text-center text-uppercase">Login</h2>
          <p class="text-black-50 text-center mb-4">Please enter your login and password!</p>
          <p id="error-msg" style="font-size:16px; color:red" class="text-center"></p>
          <p id="success-msg" style="font-size:16px; color:green" class="text-center"></p>
          
          <div class="form-outline mb-4">
            <input type="email" id="email" name="email" class="form-control form-control-lg" required/>
            <label class="form-label" for="email">Email address</label>
          </div>

          <div class="form-outline mb-3 position-relative">
            <input type="password" id="password" name="password" class="form-control form-control-lg" required/>
            <label class="form-label" for="password">Password</label>
            <i class="bx bx-hide show-hide"></i>
          </div>

          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check mb-0">
              <input class="form-check-input me-2" type="checkbox" value="" id="rememberMe"/>
              <label class="form-check-label" for="rememberMe">Remember me</label>
            </div>
            <a href="#!" class="text-body">Forgot password?</a>
          </div>

          <div class="text-center text-lg-start mt-4 pt-2">
            <button type="submit" id="loginBtn" class="btn btn-primary btn-lg w-100 w-md-auto" style="padding-left: 2.5rem; padding-right: 2.5rem;">
              <span id="loginText">Login</span>
              <span id="loginSpinner" class="spinner-border spinner-border-sm" role="status" style="display:none;"></span>
            </button>
            <p class="small fw-bold mt-3 pt-1 mb-0 text-center text-md-start">Don't have an account? <a href="signup.php" class="link-danger">Create account</a></p>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof mdb !== 'undefined') {
        document.querySelectorAll('.form-outline').forEach((formOutline) => {
            new mdb.Input(formOutline).init();
        });
    }
    
    // Keep label raised when input has value
    document.querySelectorAll('.form-control').forEach((input) => {
        input.addEventListener('blur', function() {
            if (this.value.trim() !== '') {
                this.classList.add('active');
            }
        });
        
        // Check on page load if input has value
        if (input.value.trim() !== '') {
            input.classList.add('active');
        }
    });
    
    if (typeof AuthManager !== 'undefined' && AuthManager.isAuthenticated()) {
        window.location.href = 'home.php';
    }
});

document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;
    var errorMsg = document.getElementById('error-msg');
    var successMsg = document.getElementById('success-msg');
    var loginBtn = document.getElementById('loginBtn');
    var loginText = document.getElementById('loginText');
    var loginSpinner = document.getElementById('loginSpinner');
    
    errorMsg.textContent = '';
    successMsg.textContent = '';
    
    loginBtn.disabled = true;
    loginText.style.display = 'none';
    loginSpinner.style.display = 'inline-block';
    
    $.ajax({
        url: 'api/login.php',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            email: email,
            password: password
        }),
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                localStorage.setItem('access_token', response.access_token);
                localStorage.setItem('user_data', JSON.stringify(response.user));
                
                successMsg.textContent = 'Login successful! Redirecting...';
                
                setTimeout(function() {
                    window.location.href = 'home.php';
                }, 500);
            } else {
                errorMsg.textContent = response.message || 'Invalid credentials';
                loginBtn.disabled = false;
                loginText.style.display = 'inline';
                loginSpinner.style.display = 'none';
            }
        },
        error: function(xhr) {
            var message = 'An error occurred. Please try again.';
            try {
                var response = JSON.parse(xhr.responseText);
                message = response.message || message;
            } catch(e) {}
            errorMsg.textContent = message;
            loginBtn.disabled = false;
            loginText.style.display = 'inline';
            loginSpinner.style.display = 'none';
        }
    });
});

const eyeIcons = document.querySelectorAll(".show-hide");
eyeIcons.forEach((eyeIcon) => {
    eyeIcon.addEventListener("click", () => {
        const pInput = eyeIcon.parentElement.querySelector("input");
        if (pInput.type === "password") {
            eyeIcon.classList.replace("bx-hide", "bx-show");
            return (pInput.type = "text");
        }
        eyeIcon.classList.replace("bx-show", "bx-hide");
        pInput.type = "password";
    });
});
</script>

<style>
.divider:after,
.divider:before {
    content: "";
    flex: 1;
    height: 1px;
    background: #eee;
}

body {
    overflow-x: hidden;
}

.show-hide {
    position: absolute;
    right: 13px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 18px;
    color: #919191;
    cursor: pointer;
    padding: 3px;
    z-index: 10;
}

/* Fix input borders for MDB form-outline */
.form-outline .form-control {
    border: 1px solid #bdbdbd;
    border-radius: 4px;
    background-color: #fff;
}

.form-outline .form-control:focus {
    border-color: #1266f1;
    box-shadow: inset 0 0 0 1px #1266f1;
}

.form-outline .form-label {
    background-color: #fff;
    padding: 0 4px;
}

.form-outline .form-control:focus ~ .form-label,
.form-outline .form-control.active ~ .form-label {
    background-color: #fff;
}

/* Mobile Responsive Styles */
@media (max-width: 767px) {
    body {
        overflow-y: auto;
    }
    
    section.vh-100 {
        min-height: 100vh;
        height: auto;
    }
    
    .h-custom {
        height: auto !important;
        min-height: 100vh;
    }
    
    .login-form-container {
        padding: 2rem 1rem;
    }
    
    h2 {
        font-size: 1.5rem;
    }
    
    .form-control-lg {
        font-size: 1rem;
        padding: 0.5rem 0.75rem;
    }
    
    .btn-lg {
        padding: 0.75rem 1.5rem !important;
        width: 100%;
    }
}

@media (min-width: 768px) and (max-width: 991px) {
    .login-form-container {
        padding: 1rem;
    }
}

/* Ensure proper spacing on all devices */
.row {
    margin: 0;
}

.h-custom {
    padding: 1rem;
}

@media (min-width: 768px) {
    .h-custom {
        padding: 2rem;
    }
}

/* Fix for very small screens */
@media (max-width: 375px) {
    .login-form-container {
        padding: 1rem 0.5rem;
    }
    
    h2 {
        font-size: 1.25rem;
    }
    
    .form-check-label,
    .text-body {
        font-size: 0.875rem;
    }
}
</style>
</body>
</html>