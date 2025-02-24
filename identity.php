<?php session_start();?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Select Identity</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="login.css">
    <link rel="icon" href="image/logo.png">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="script.js"></script>
    <style>
        .form-title {
            font-size: 2em; 
        }
        .user-input-box label {
            font-size: 1.2em;
        }
        .user-input-box select {
            font-size: 1.2em; 
        }
        .form-submit-btn input {
            font-size: 1.2em; 
        }
        body{
    background-image: url("image/background.JPG");
    
    background-size: cover;
}


    .user-input-box
    {
       display: flex;
       flex-flow: column nowrap;    
       width: 100%;
      padding-bottom: 15px;
    }
    </style>
</head>
<body>
    <main>
        <div class="container">
            <h1 class="form-title">Select Your Identity</h1>
            <form method="POST" action="login.php">
                <div class="main-user-info">
                    <div class="user-input-box">
                        <label for="identity">Identity</label>
                        <select id="identity" name="identity" required>
                            <option value="admin">Admin</option>
                            <option value="supplier">Supplier</option>
                        </select>
                    </div>
                </div>
                <div class="form-submit-btn">
                    <input type="submit" name="submit_identity" value="Proceed">
                </div>
            </form>
        </div>
    </main>
</body>
</html>