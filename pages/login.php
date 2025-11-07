<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Halaman Login</title>
  <link rel="stylesheet" href="/assets/css/login.css"> 
</head>
<body>

  <div class="login-container">
    
    <h2>Login Akun</h2>
    
    <form action="/proses-login" method="POST">
      
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
      </div>
      
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>
     
      <a href="/backend\admin\index_admin.php" class="login-button">Masuk</a>
      
      <div class="extra-links">
        <a href="#" class="forgot-pass">Lupa Password?</a>
      </div>
      
    </form>
    
  </div>

</body>
</html>