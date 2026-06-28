<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign In</title>
<style>

  :root {
    --navy-dark: #0B1E3D;
    --navy-light: #1B3A6B;
    --white: #ffffff;
    --panel-bg: #E8F0FB;
  }

  * {
    box-sizing: border-box;
  }

  body {
    margin: 0;
    font-family: 'Segoe UI', Arial, sans-serif;
    background: var(--white);
    min-height: 100vh;
    position: relative;
    overflow-x: hidden;
  }

  /* Top header bar */
  .header {
    background: var(--navy-dark);
    height: 130px;
    display: flex;
    align-items: center;
    padding: 0 50px;
  }

  .logo {
    height: 80px;
  }

  /* Main content area */
  .main {
    position: relative;
    min-height: calc(100vh - 130px);
    overflow: hidden;
  }

  /* Sign in card */
  .signin-card {
    position: relative;
    z-index: 3;
    background: var(--panel-bg);
    width: 420px;
    max-width: 85%;
    margin: 80px 0 0 130px;
    padding: 35px 40px 45px;
    border-radius: 6px;
  }

  .signin-card h1 {
    color: var(--navy-dark);
    font-size: 34px;
    margin: 0 0 25px 0;
  }

  .signin-card label {
    display: block;
    color: var(--navy-dark);
    font-weight: 600;
    margin-bottom: 6px;
    font-size: 15px;
  }

  .signin-card input {
    width: 100%;
    padding: 10px 14px;
    margin-bottom: 20px;
    border: 1px solid var(--navy-dark);
    border-radius: 2px;
    font-size: 15px;
    background: var(--white);
  }

  .signin-card input::placeholder {
    color: #999;
  }

  .login-btn {
    width: 100%;
    background: var(--navy-dark);
    color: var(--white);
    border: none;
    padding: 14px 0;
    font-size: 17px;
    font-weight: 700;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 5px;
    transition: background 0.2s ease;
  }

  .login-btn:hover {
    background: var(--navy-light);
  }

  .links {
    text-align: center;
    margin-top: 22px;
    font-size: 14px;
    color: #333;
  }

  .links p {
    margin: 8px 0;
  }

  .links a {
    color: var(--navy-light);
    text-decoration: underline;
  }

  /* Decorative hexagon logo watermark (reuses banner icon area via background clip is not possible,
     so we just reuse the same banner image, scaled and faded as decoration) */
  .hex-decor {
    position: absolute;
    top: 130px;
    right: 260px;
    width: 480px;
    opacity: 0.22;
    z-index: 1;
    pointer-events: none;
    filter: saturate(0.6) brightness(1.3);
  }

  /* Wave shapes */
  .wave {
    position: absolute;
    left: 0;
    width: 100%;
    line-height: 0;
    z-index: 2;
  }

  .wave-back {
    bottom: -2px;
  }

  .wave-front {
    bottom: -2px;
    z-index: 2;
  }

  svg {
    display: block;
    width: 100%;
    height: auto;
  }

  .wave-corner {
    position: absolute;
    left: 0;
    bottom: -2px;
    width: 320px;
    z-index: 1;
  }

  @media (max-width: 900px) {
    .signin-card {
      margin: 60px auto 0;
      float: none;
    }
    .hex-decor {
      display: none;
    }
    .header {
      padding: 0 25px;
    }
    .logo {
      height: 60px;
    }
  }
</style>
</head>
<body>

  <div class="header">
    <img src="Banner_Transparent.png" alt="Nexora Enterprise Resource Planning" class="logo">
  </div>

  <div class="main">

    <img src="Nexora_Logo_Transparent.png" alt="" class="hex-decor">

    <div class="signin-card">
      <h1>Sign In</h1>

      <label for="username">Username</label>
      <input type="text" id="username" placeholder="Enter Username">

      <label for="password">Password</label>
      <input type="password" id="password" placeholder="Enter Password">

      <button class="login-btn">Log In</button>

      <div class="links">
        <p>Forgot Password? <a href="#">Create a Ticket</a></p>
        <p>Not registered yet? <a href="#">Contact Us</a></p>
      </div>
    </div>

    <!-- Background wave (dark navy) -->
    <svg class="wave wave-back" viewBox="0 0 1920 420" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M1920,0 L1920,420 L0,420 L0,300 C700,420 1300,420 1920,0 Z" fill="#1B6FC8"/>
    </svg>

    <!-- Front wave (lighter navy) -->
    <svg class="wave wave-front" viewBox="0 0 1920 420" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M0,420 L0,360 C500,420 1000,300 1500,150 C1700,80 1850,40 1920,0 L1920,420 Z" fill="#0B1E3D"/>
    </svg>

    <!-- Small bottom-left accent wave -->
    <svg class="wave-corner" viewBox="0 0 400 100" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M0,100 L0,40 C100,90 250,90 400,30 L400,100 Z" fill="#1B3A6B"/>
    </svg>

  </div>

</body>
</html>