<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Welcome Back - Login</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background: linear-gradient(135deg, #fff8f2, #ffe8d6);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 20px;
    }

    .container {
      display: grid;
      grid-template-columns: 1fr 1fr;
      max-width: 1100px;
      width: 100%;
      background: #fff;
      border-radius: 25px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
      overflow: hidden;
    }

    .login-section {
      padding: 60px 50px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .welcome-title {
      font-size: 2.8rem;
      font-weight: 700;
      color: #ff7b00;
      margin-bottom: 40px;
      text-shadow: 1px 1px 3px rgba(255, 123, 0, 0.2);
    }

    .form-group {
      margin-bottom: 25px;
    }

    .input-wrapper {
      position: relative;
    }

    .form-input {
      width: 100%;
      padding: 15px 50px 15px 45px;
      border: 2px solid #e0dfdf;
      border-radius: 50px;
      font-size: 1rem;
      background: #fdfdfd;
      transition: all 0.3s ease;
    }

    .form-input:focus {
      outline: none;
      border-color: #ff9500;
      box-shadow: 0 0 10px rgba(255, 123, 0, 0.2);
      background: #fff;
    }

    .input-icon {
      position: absolute;
      left: 18px;
      top: 50%;
      transform: translateY(-50%);
      color: #aaa;
      font-size: 1rem;
    }

    .password-toggle {
      position: absolute;
      right: 18px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: #aaa;
      cursor: pointer;
      font-size: 1rem;
    }

    .form-label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: #555;
    }

    .forgot-password {
      text-align: right;
      font-size: 0.9rem;
      margin-top: 5px;
      margin-bottom: 30px;
    }

    .forgot-password a {
      text-decoration: none;
      color: #ff7b00;
      font-weight: 600;
    }

    .forgot-password a:hover {
      color: #ff9500;
    }

    .login-btn {
      width: 100%;
      padding: 15px;
      background: linear-gradient(135deg, #ff9500, #ff7b00);
      color: white;
      border: none;
      border-radius: 50px;
      font-size: 1.1rem;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s ease;
      margin-bottom: 30px;
      box-shadow: 0 8px 20px rgba(255, 123, 0, 0.3);
    }

    .login-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 25px rgba(255, 123, 0, 0.4);
    }

    .divider {
      text-align: center;
      color: #999;
      margin-bottom: 30px;
    }

    .social-login {
      display: flex;
      gap: 20px;
      justify-content: center;
      margin-bottom: 30px;
    }

    .social-btn {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      border: 2px solid #e0e0e0;
      display: flex;
      justify-content: center;
      align-items: center;
      color: #555;
      text-decoration: none;
      font-size: 1.2rem;
      transition: 0.3s;
      background: white;
    }

    .social-btn:hover {
      transform: translateY(-3px) scale(1.05);
      box-shadow: 0 5px 15px rgba(255, 123, 0, 0.2);
    }

    .signup-link {
      text-align: center;
      font-size: 0.9rem;
      color: #555;
    }

    .signup-link a {
      color: #ff9500;
      font-weight: bold;
      text-decoration: none;
    }

    .signup-link a:hover {
      text-decoration: underline;
    }

    .illustration-section {
      background: linear-gradient(135deg, #ff9500 0%, #ff7b00 25%, #ff6b00 50%, #ff5722 75%, #e91e63 100%);
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 40px;
      position: relative;
      overflow: hidden;
    }

    /* Animated background elements */
    .illustration-section::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background:
        radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.15) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, rgba(255, 193, 7, 0.2) 0%, transparent 70%);
      animation: backgroundMove 20s linear infinite;
    }

    .illustration-section::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background:
        linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.05) 50%, transparent 70%),
        radial-gradient(circle at 60% 30%, rgba(255, 152, 0, 0.3) 0%, transparent 50%);
      animation: shimmer 8s ease-in-out infinite;
    }

    @keyframes backgroundMove {
      0% {
        transform: rotate(0deg) scale(1);
      }

      50% {
        transform: rotate(180deg) scale(1.1);
      }

      100% {
        transform: rotate(360deg) scale(1);
      }
    }

    @keyframes shimmer {

      0%,
      100% {
        opacity: 0.3;
      }

      50% {
        opacity: 0.8;
      }
    }

    .rounded-bg {
      background:
        radial-gradient(circle at 30% 20%, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.4) 40%, transparent 70%),
        radial-gradient(circle at 70% 80%, rgba(255, 215, 0, 0.3) 0%, transparent 50%),
        linear-gradient(135deg, rgba(255, 255, 255, 0.8) 0%, rgba(255, 255, 255, 0.2) 100%);
      width: 100%;
      max-width: 480px;
      height: 480px;
      border-radius: 50%;
      box-shadow:
        0 25px 50px rgba(255, 123, 0, 0.4),
        inset 0 0 50px rgba(255, 255, 255, 0.2),
        0 0 0 1px rgba(255, 255, 255, 0.3);
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 40px;
      position: relative;
      backdrop-filter: blur(10px);
      animation: float 6s ease-in-out infinite;
    }

    @keyframes float {

      0%,
      100% {
        transform: translateY(0px) scale(1);
      }

      50% {
        transform: translateY(-15px) scale(1.02);
      }
    }

    /* Decorative elements around the circle */
    .decorative-dots {
      position: absolute;
      width: 100%;
      height: 100%;
      pointer-events: none;
    }

    .dot {
      position: absolute;
      width: 8px;
      height: 8px;
      background: rgba(255, 255, 255, 0.6);
      border-radius: 50%;
      animation: twinkle 3s infinite;
    }

    .dot:nth-child(1) {
      top: 10%;
      left: 15%;
      animation-delay: 0s;
    }

    .dot:nth-child(2) {
      top: 20%;
      right: 10%;
      animation-delay: 0.5s;
    }

    .dot:nth-child(3) {
      bottom: 25%;
      left: 20%;
      animation-delay: 1s;
    }

    .dot:nth-child(4) {
      bottom: 15%;
      right: 15%;
      animation-delay: 1.5s;
    }

    .dot:nth-child(5) {
      top: 50%;
      left: 5%;
      animation-delay: 2s;
    }

    .dot:nth-child(6) {
      top: 50%;
      right: 5%;
      animation-delay: 2.5s;
    }

    @keyframes twinkle {

      0%,
      100% {
        opacity: 0.3;
        transform: scale(1);
      }

      50% {
        opacity: 1;
        transform: scale(1.5);
      }
    }

    .lottie-animation {
      width: 90%;
      height: auto;
      position: relative;
      z-index: 10;
      filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.1));
    }

    /* Glowing ring effect */
    .rounded-bg::before {
      content: '';
      position: absolute;
      top: -5px;
      left: -5px;
      right: -5px;
      bottom: -5px;
      border-radius: 50%;
      background: linear-gradient(45deg, #ff9500, #ff7b00, #ff6b00, #ff9500);
      animation: rotate 8s linear infinite;
      z-index: -1;
      filter: blur(10px);
      opacity: 0.7;
    }

    @keyframes rotate {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }

    @media (max-width:768px) {
      .container {
        grid-template-columns: 1fr;
        max-width: 400px;
      }

      .illustration-section {
        display: none;
      }

      .login-section {
        padding: 40px 30px;
      }

      .welcome-title {
        text-align: center;
      }
    }
  </style>
</head>

<body>

  <div class="container">
    <div class="login-section">
      <h1 class="welcome-title">Welcome Back!!</h1>

      <form id="loginForm">
        <div class="form-group">
          <label class="form-label">Email</label>
          <div class="input-wrapper">
            <i class="fas fa-envelope input-icon"></i>
            <input type="email" id="email" class="form-input" placeholder="email@gmail.com" required />
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Password</label>
          <div class="input-wrapper">
            <i class="fas fa-lock input-icon"></i>
            <input type="password" class="form-input" id="password" placeholder="Enter your password" required />
            <button type="button" class="password-toggle" onclick="togglePassword()">
              <i class="fas fa-eye-slash"></i>
            </button>
          </div>
        </div>

        <div class="forgot-password">
          <a href="#" onclick="forgotPassword()">Forgot Password?</a>
        </div>

        <button type="submit" class="login-btn">Login</button>
      </form>

      <div class="divider">- or -</div>

      <div class="social-login">
        <a href="#" class="social-btn" title="Google"><i class="fab fa-google"></i></a>
        <a href="#" class="social-btn" title="Facebook"><i class="fab fa-facebook-f"></i></a>
        <a href="#" class="social-btn" title="Apple"><i class="fab fa-apple"></i></a>
      </div>

      <div class="signup-link">
        Don't have an account? <a href="signup.html">Sign up</a>
      </div>
    </div>

    <div class="illustration-section">
      <div class="decorative-dots">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
      </div>

      <div class="rounded-bg">
        <!-- Animasi Lottie -->
        <lottie-player src="https://assets4.lottiefiles.com/packages/lf20_2glqweqs.json" background="transparent"
          speed="1" class="lottie-animation" loop autoplay>
        </lottie-player>
      </div>
    </div>
  </div>

  <!-- Script Lottie -->
  <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

  <script>
    function togglePassword() {
      const input = document.getElementById('password');
      const icon = document.querySelector('.password-toggle i');
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
      } else {
        input.type = 'password';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
      }
    }

    function forgotPassword() {
      const email = prompt("Masukkan email Anda:");
      if (email) alert(`Link reset password dikirim ke ${email}`);
    }

    document.getElementById('loginForm').addEventListener('submit', function (e) {
      e.preventDefault();
      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;

      // Demo credentials - replace with actual authentication
      if (email && password) {
        alert("Login berhasil! (Demo mode)");
        // In real implementation, redirect to dashboard
        // window.location.href = 'beranda.html';
      } else {
        alert("Mohon isi email dan password!");
      }
    });
  </script>

</body>

</html>