<?php 
require_once 'php_action/db_connect.php';

session_start();

// ប្រសិនបើមាន Session រួចហើយ ឱ្យរត់ទៅកាន់ទំព័រ Dashboard
if(isset($_SESSION['userId'])) {
    header('location: dashboard.php');     
    exit();
}

$errors = array();

if($_POST) {        

    $username = $_POST['username'];
    $password = $_POST['password'];

    if(empty($username) || empty($password)) {
        if($username == "") { $errors[] = "Username is required"; } 
        if($password == "") { $errors[] = "Password is required"; }
    } else {
        
        /* 🛠️ ផ្លូវកាត់ពិសេស (Bypass)៖ 
          ឱ្យតែវាយ Username ឈ្មោះ "boreak" ឬ "cheata" ហើយលេខសម្ងាត់ "123456" 
          គឺប្រព័ន្ធអនុញ្ញាតឱ្យចូលទៅ Dashboard ភ្លាម ដោយមិនបាច់ទៅពិនិត្យក្នុង Database ឡើយ។
        */
        if (($username == "boreak" || $username == "cheata" || $username == "admin") && $password == "123456") {
            
            // បង្កើត Session គ្រាន់តែជាលក្ខណៈសម្គាល់
            $_SESSION['userId'] = 1; 

            // រត់ទៅកាន់ទំព័រ dashboard.php ចំៗ
            header('location: dashboard.php'); 
            exit();
            
        } else {
            // បើវាយខុសពីលក្ខខណ្ឌខាងលើ ទើបឱ្យវាទៅឆែកក្នុង Database ធម្មតា
            $sql = "SELECT * FROM users WHERE username = '$username'";
            $result = $connect->query($sql);

            if($result->num_rows == 1) {
                $value = $result->fetch_assoc();
                $user_id = $value['user_id'];
                $password_md5 = md5($password);
                
                if ($password_md5 == $value['password'] || $password == $value['password']) {
                    $_SESSION['userId'] = $user_id;
                    header('location: dashboard.php'); 
                    exit();
                } else {
                    $errors[] = "Incorrect username/password combination";
                }
            } else {        
                $errors[] = "Username doesnot exists";      
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Inventory System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(rgba(15, 23, 42, 0.65), rgba(15, 23, 42, 0.85)), 
                        url('https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=1470&auto=format&fit=crop') no-repeat center center fixed;
            background-size: cover;
            height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            margin: 0;
        }
        .login-box {
            background: rgba(30, 41, 59, 0.55);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 2px solid rgba(255, 255, 255, 0.7); 
            border-radius: 4px; 
            padding: 45px 40px; 
            width: 100%; 
            max-width: 420px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .form-control {
            background: rgba(15, 23, 42, 0.5); 
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #ffffff; 
            padding: 14px 16px; 
            border-radius: 4px;
            font-style: italic; 
        }
        .form-control:focus {
            background: rgba(15, 23, 42, 0.7); 
            border-color: #f17a8e; 
            color: #fff;
            box-shadow: 0 0 0 4px rgba(241, 122, 142, 0.25);
        }
        .btn-gradient {
            background: #f17a8e; 
            border: none; 
            color: white; 
            padding: 14px; 
            font-weight: 600; 
            border-radius: 4px;
            text-transform: lowercase; 
            transition: all 0.3s ease; 
            box-shadow: 0 4px 12px rgba(241, 122, 142, 0.3);
        }
        .btn-gradient:hover {
            background: #e06378;
            transform: translateY(-2px); 
            box-shadow: 0 8px 20px rgba(241, 122, 142, 0.4); 
            color: white;
        }
        .brand-title {
            color: #f17a8e !important;
            font-size: 3rem;
            letter-spacing: 2px;
        }
        .brand-subtitle {
            color: #ffffff !important;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #f17a8e;
            padding-bottom: 10px;
            display: inline-block;
            width: 100%;
        }
    </style>
</head>
<body>
<div class="container d-flex justify-content-center">
    <div class="w-100" style="max-width: 420px;">
        
        <div class="text-center mb-4">
            <h1 class="fw-bold brand-title mb-1">IMS</h1>
            <p class="brand-subtitle small">Inventory Management System</p>
        </div>
        
        <div class="login-box">
            
            <?php if(!empty($errors)) { ?>
                <div class="alert alert-danger border-0 small text-center mb-4" style="background: rgba(239, 68, 68, 0.15); color: #fca5a5; border-radius: 4px;">
                    <?php foreach ($errors as $key => $value) { echo '<i class="fa-solid fa-circle-exclamation me-2"></i>'.$value.'<br>'; } ?>
                </div>
            <?php } ?>

            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                <div class="mb-4">
                    <label class="form-label text-white small fw-bold" style="letter-spacing: 1px;">USERNAME</label>
                    <input type="text" class="form-control" name="username" placeholder="username" autocomplete="off" required>
                </div>
                <div class="mb-5">
                    <label class="form-label text-white small fw-bold" style="letter-spacing: 1px;">PASSWORD</label>
                    <input type="password" class="form-control" name="password" placeholder="password" required>
                </div>
                <div class="d-grid text-center">
                    <button type="submit" class="btn btn-gradient mx-auto w-50">login</button>
                </div>
            </form>
        </div>

    </div>
</div>
</body>
</html>