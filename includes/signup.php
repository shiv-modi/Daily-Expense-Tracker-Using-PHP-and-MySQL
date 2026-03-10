<head>
    <!-- Font Awesome -->
<link
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
  rel="stylesheet"
/>
<!-- Google Fonts -->
<link
  href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
  rel="stylesheet"
/>
<!-- MDB -->
<link

  href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.1.0/mdb.min.css"
  rel="stylesheet"
  
/>
  <!-- Boxicons CSS -->
  <link
      href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css"
      rel="stylesheet"
    />
<link href="/assets/libs/frontend/MDB-UI-KIT-Pro-Essential-1.0.0/css/mdb.min.css" type="text/css" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script
  type="text/javascript"
  src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.1.0/mdb.min.js"
></script>
</head>
<section >
  <div class="container h-100">
  
      <br>
      <br>

          <div class="card-body p-md-5">
            <div class="row justify-content-center">
              <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">

                <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Sign up</p>

                <form class="mx-1 mx-md-4" id="signupForm">
                  <p id="error-msg" style="font-size:16px; color:red" class="text-center"></p>
                  <p id="success-msg" style="font-size:16px; color:green" class="text-center"></p>

                  <div class="d-flex flex-row align-items-center mb-4">
                    <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                    <div class="form-outline flex-fill mb-0">
                    <input type="text" class="form-control" name="name" id="name" required>
                      <label class="form-label" for="name">Your Name</label>
                    </div>
                  </div>

                  <div class="d-flex flex-row align-items-center mb-4">
                    <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                    <div class="form-outline flex-fill mb-0">
                      <input type="email" name="email" id="email" class="form-control" required/>
                      <label class="form-label" for="email">Your Email</label>
                    </div>
                  </div>

                  <div class="d-flex flex-row align-items-center mb-4">
                    <i class="fas fa-phone fa-lg me-3 fa-fw"></i>
                    <div class="form-outline flex-fill mb-0">
                      <input type="text" name="phone" id="phone" class="form-control" required/>
                      <label class="form-label" for="phone">Mobile Number</label>
                    </div>
                  </div>

                  <div class="d-flex flex-row align-items-center mb-4">
                    <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                    <div class="form-outline flex-fill mb-0 position-relative">
                    <input type="password" class="form-control" name="password" id="password" required>
                      <label class="form-label" for="password">Password</label>
                      <i class="bx bx-hide show-hide"></i>
                    </div>
                  </div>

                  <div class="d-flex flex-row align-items-center mb-4">
                    <i class="fas fa-key fa-lg me-3 fa-fw"></i>
                    <div class="form-outline flex-fill mb-0 position-relative">
                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
                      <label class="form-label" for="confirm_password">Confirm Password</label>
                      <i class="bx bx-hide show-hide"></i>
                    </div>
                  </div>

                  <div class="form-check d-flex justify-content-center mb-3">
                    <input class="form-check-input me-2" type="checkbox" value="" id="form2Example3c" required/>
                    <label class="form-check-label" for="form2Example3">
                      I agree all statements in <a href="#!">Terms of service</a>
                    </label>
                  </div>

                  <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                    <button type="submit" id="signupBtn" class="btn btn-primary btn-lg">
                      <span id="signupText">Create Account</span>
                      <span id="signupSpinner" class="spinner-border spinner-border-sm" role="status" style="display:none;"></span>
                    </button>
                  </div>

                <p class="text-center text-muted ">Have already an account? <a href="index.php"
                    class="fw-bold text-body link-danger"><u>Login here</u></a></p>

                </form>
                
              </div>
              <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">

                <img src="images/draw1.webp"
                  class="img-fluid" alt="Sample image">

              </div>
            </div>
          </div>
        </div>


</section>

<style>
.show-hide {
  position: absolute;
  right: 13px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 18px;
  color: #919191;
  cursor: pointer;
  padding: 3px;
}

.mx-md-4 {
    margin-right: 1.5rem!important;
    margin-left: 1.5rem!important;
    margin-top: -1.5rem;
}

.divider:after,
.divider:before {
content: "";
flex: 1;
height: 1px;
background: #eee;
}
body {
  overflow-y: hidden; /* Hide vertical scrollbar */
  overflow-x: hidden; /* Hide horizontal scrollbar */
}

element.style {
    background-color: #eee;
}
</style>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.form-outline').forEach((formOutline) => {
      new mdb.Input(formOutline).init();
    });
  });
// Hide and show password
const eyeIcons = document.querySelectorAll(".show-hide");

eyeIcons.forEach((eyeIcon) => {
  eyeIcon.addEventListener("click", () => {
    const pInput = eyeIcon.parentElement.querySelector("input"); //getting parent element of eye icon and selecting the password input
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
.show-hide {
  position: absolute;
  right: 13px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 18px;
  color: #919191;
  cursor: pointer;
  padding: 3px;
}

.mx-md-4 {
    margin-right: 1.5rem!important;
    margin-left: 1.5rem!important;
    margin-top: -1.5rem;
}

.divider:after,
.divider:before {
content: "";
flex: 1;
height: 1px;
background: #eee;
}
body {
  overflow-y: hidden; /* Hide vertical scrollbar */
  overflow-x: hidden; /* Hide horizontal scrollbar */
}

element.style {
    background-color: #eee;
}
</style>
<script>
document.querySelector("#signupForm").addEventListener("submit", function(e) {
    e.preventDefault();

    var name = document.querySelector("#name").value;
    var email = document.querySelector("#email").value;
    var phone = document.querySelector("#phone").value;
    var password = document.getElementById("password").value;
    var confirm_password = document.getElementById("confirm_password").value;
    var signupBtn = document.getElementById("signupBtn");
    var signupText = document.getElementById("signupText");
    var signupSpinner = document.getElementById("signupSpinner");
    var errorMsg = document.getElementById("error-msg");
    var successMsg = document.getElementById("success-msg");

    errorMsg.textContent = "";
    successMsg.textContent = "";

    signupBtn.disabled = true;
    signupText.style.display = "none";
    signupSpinner.style.display = "inline-block";

    fetch("api/signup.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            name: name,
            email: email,
            phone: phone,
            password: password,
            confirm_password: confirm_password
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            successMsg.textContent = data.message;
            setTimeout(function() {
                window.location.href = "index.php";
            }, 1500);
        } else {
            errorMsg.textContent = data.message || "An error occurred";
            signupBtn.disabled = false;
            signupText.style.display = "inline";
            signupSpinner.style.display = "none";
        }
    })
    .catch(error => {
        errorMsg.textContent = "A network error occurred. Please try again.";
        signupBtn.disabled = false;
        signupText.style.display = "inline";
        signupSpinner.style.display = "none";
    });
});
</script>
