<?php
session_start();

if (!isset($_SESSION['users'])) {
    $_SESSION['users'] = [];
}

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password =      $_POST['password'] ?? '';

    if (!$email || !$password) {
        $error = 'Email aur password dono bharain.';
    } else {
        $found = false;
        foreach ($_SESSION['users'] as $u) {
            if ($u['email'] === $email && $u['password'] === $password) {
                $found = true;
                $_SESSION['logged_in']   = true;
                $_SESSION['current_user'] = $u;
                break;
            }
        }
        if ($found) {
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Email ya password galat hai.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ur">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — NexCorp Office</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
  :root{--gold:#c9a84c;--gold-light:#e8c97a;--dark:#0d0d0f;--dark2:#16161a;--card:#1c1c22;--border:rgba(201,168,76,0.25);--text:#e8e4dc;--muted:#7a7a8a;}
  *{margin:0;padding:0;box-sizing:border-box;}
  body{background:var(--dark);color:var(--text);font-family:'DM Sans',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden;}
  body::before{content:'';position:fixed;inset:0;background:radial-gradient(ellipse 80% 60% at 80% 20%,rgba(201,168,76,0.07) 0%,transparent 60%),radial-gradient(ellipse 60% 80% at 10% 80%,rgba(201,168,76,0.05) 0%,transparent 60%);pointer-events:none;}
  .grid-bg{position:fixed;inset:0;background-image:linear-gradient(rgba(201,168,76,0.04) 1px,transparent 1px),linear-gradient(90deg,rgba(201,168,76,0.04) 1px,transparent 1px);background-size:40px 40px;pointer-events:none;}
  .logo-bar{position:fixed;top:0;left:0;right:0;padding:18px 40px;display:flex;align-items:center;gap:12px;border-bottom:1px solid var(--border);background:rgba(13,13,15,0.85);backdrop-filter:blur(12px);z-index:10;}
  .logo-icon{width:34px;height:34px;background:linear-gradient(135deg,var(--gold),var(--gold-light));border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:17px;font-weight:700;color:#0d0d0f;font-family:'Cormorant Garamond',serif;}
  .logo-text{font-family:'Cormorant Garamond',serif;font-size:21px;font-weight:700;color:var(--gold);letter-spacing:1px;}
  .container{width:100%;max-width:420px;padding:20px;position:relative;z-index:5;margin-top:80px;}
  .welcome{text-align:center;margin-bottom:26px;}
  .welcome h1{font-family:'Cormorant Garamond',serif;font-size:40px;font-weight:700;color:var(--gold);line-height:1.1;}
  .welcome p{font-size:13px;color:var(--muted);margin-top:5px;}
  .card{background:var(--card);border:1px solid var(--border);border-radius:16px;padding:36px 40px;box-shadow:0 24px 80px rgba(0,0,0,0.5);animation:fadeUp .45s ease;}
  @keyframes fadeUp{from{opacity:0;transform:translateY(18px)}to{opacity:1;transform:translateY(0)}}
  .form-group{margin-bottom:18px;}
  label{display:block;font-size:11px;font-weight:600;color:var(--muted);margin-bottom:6px;text-transform:uppercase;letter-spacing:.6px;}
  input{width:100%;background:rgba(255,255,255,0.04);border:1px solid var(--border);border-radius:8px;padding:13px 14px;color:var(--text);font-family:'DM Sans',sans-serif;font-size:14px;outline:none;transition:border-color .2s,box-shadow .2s;}
  input:focus{border-color:var(--gold);box-shadow:0 0 0 3px rgba(201,168,76,0.1);}
  .btn{width:100%;padding:13px;background:linear-gradient(135deg,var(--gold),var(--gold-light));color:#0d0d0f;font-family:'DM Sans',sans-serif;font-size:15px;font-weight:600;border:none;border-radius:8px;cursor:pointer;transition:opacity .2s,transform .1s;margin-top:4px;}
  .btn:hover{opacity:.9;transform:translateY(-1px);}
  .alert{padding:11px 14px;border-radius:8px;font-size:13px;margin-bottom:16px;}
  .alert-error{background:rgba(220,60,60,0.12);border:1px solid rgba(220,60,60,0.3);color:#f08080;}
  .switch-link{text-align:center;margin-top:18px;font-size:13px;color:var(--muted);}
  .switch-link a{color:var(--gold);text-decoration:none;font-weight:500;}
  .switch-link a:hover{text-decoration:underline;}
</style>
</head>
<body>
<div class="grid-bg"></div>
<div class="logo-bar">
  <div class="logo-icon">N</div>
  <span class="logo-text">NexCorp</span>
</div>
<div class="container">
  <div class="welcome">
    <h1>Khush Aamdeed</h1>
    <p>Apne portal mein login karein</p>
  </div>
  <div class="card">
    <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="POST">
      <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" placeholder="email@nexcorp.pk" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="••••••••">
      </div>
      <button type="submit" class="btn">→ Login Karein</button>
    </form>
    <div class="switch-link" style="margin-top:20px;">
      Naya account chahiye? <a href="signup.php">Signup karein</a>
    </div>
  </div>
</div>
</body>
</html>
