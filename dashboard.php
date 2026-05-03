<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: signup.php');
    exit;
}

$user       = $_SESSION['current_user'];
$allUsers   = $_SESSION['users'] ?? [];
$totalStaff = count($allUsers);

$stats = [
    ['icon' => '👥', 'label' => 'Total Staff',     'value' => $totalStaff, 'color' => '#c9a84c'],
    ['icon' => '📋', 'label' => 'Active Projects', 'value' => '12',        'color' => '#5b9cf6'],
    ['icon' => '✅', 'label' => 'Tasks Complete',  'value' => '84',        'color' => '#5de09e'],
    ['icon' => '📅', 'label' => 'Meetings Today',  'value' => '3',         'color' => '#e07a5f'],
];

$announcements = [
    ['date' => 'Apr 18', 'title' => 'Q2 Review Meeting',    'desc' => 'Tamam managers ko 3pm ki meeting yaad dilai jaati hai — Conference Room B.'],
    ['date' => 'Apr 16', 'title' => 'New HR Policy Update', 'desc' => 'Leave policy mein tabdiliyaan aayi hain. HR se rabita karein details ke liye.'],
    ['date' => 'Apr 14', 'title' => 'Office Renovation',   'desc' => '2nd floor ka renovation Jumma se shuru hoga. Mutasirra departments neeche shift ho jayein.'],
];

$quickLinks = [
    ['icon' => '📁', 'label' => 'Documents'],
    ['icon' => '📊', 'label' => 'Reports'],
    ['icon' => '🗓️', 'label' => 'Calendar'],
    ['icon' => '💬', 'label' => 'Messages'],
    ['icon' => '⚙️', 'label' => 'Settings'],
    ['icon' => '📞', 'label' => 'Directory'],
];
?>
<!DOCTYPE html>
<html lang="ur">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard — NexCorp Office</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
  :root{--gold:#c9a84c;--gold-light:#e8c97a;--dark:#0d0d0f;--dark2:#16161a;--card:#1c1c22;--card2:#202028;--border:rgba(201,168,76,0.2);--border2:rgba(255,255,255,0.06);--text:#e8e4dc;--muted:#7a7a8a;--sidebar-w:240px;}
  *{margin:0;padding:0;box-sizing:border-box;}
  body{background:var(--dark);color:var(--text);font-family:'DM Sans',sans-serif;min-height:100vh;display:flex;}

  .sidebar{width:var(--sidebar-w);background:var(--dark2);border-right:1px solid var(--border);display:flex;flex-direction:column;position:fixed;top:0;left:0;bottom:0;z-index:20;}
  .sidebar-logo{padding:22px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:10px;}
  .logo-icon{width:34px;height:34px;background:linear-gradient(135deg,var(--gold),var(--gold-light));border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:700;color:#0d0d0f;font-family:'Cormorant Garamond',serif;flex-shrink:0;}
  .logo-text{font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--gold);}
  .sidebar-user{padding:18px 20px;border-bottom:1px solid var(--border2);}
  .user-avatar{width:42px;height:42px;background:linear-gradient(135deg,var(--gold),var(--gold-light));border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:17px;font-weight:700;color:#0d0d0f;font-family:'Cormorant Garamond',serif;margin-bottom:9px;}
  .user-name{font-size:14px;font-weight:500;}
  .user-role{font-size:11px;color:var(--gold);background:rgba(201,168,76,0.1);border:1px solid rgba(201,168,76,0.2);border-radius:20px;padding:2px 8px;display:inline-block;margin-top:4px;font-weight:500;}
  .sidebar-nav{padding:14px 12px;flex:1;}
  .nav-label{font-size:10px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:1px;padding:0 8px;margin-bottom:8px;}
  .nav-item{display:flex;align-items:center;gap:10px;padding:10px;border-radius:8px;font-size:14px;color:var(--muted);cursor:pointer;transition:all .15s;margin-bottom:2px;text-decoration:none;}
  .nav-item:hover{background:rgba(255,255,255,0.04);color:var(--text);}
  .nav-item.active{background:rgba(201,168,76,0.1);color:var(--gold);}
  .nav-icon{font-size:16px;width:20px;text-align:center;}
  .sidebar-bottom{padding:14px;border-top:1px solid var(--border2);}
  .logout-btn{display:flex;align-items:center;gap:10px;padding:10px;border-radius:8px;font-size:14px;color:#e07a5f;text-decoration:none;transition:background .15s;background:none;border:none;width:100%;font-family:'DM Sans',sans-serif;cursor:pointer;}
  .logout-btn:hover{background:rgba(224,122,95,0.08);}

  .main{margin-left:var(--sidebar-w);flex:1;padding:30px 34px;max-width:calc(100vw - var(--sidebar-w));}
  .topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:30px;}
  .topbar h1{font-family:'Cormorant Garamond',serif;font-size:32px;font-weight:700;}
  .topbar-date{font-size:13px;color:var(--muted);}
  .greeting-badge{background:rgba(201,168,76,0.1);border:1px solid var(--border);border-radius:8px;padding:6px 14px;font-size:13px;color:var(--gold);}

  .stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:15px;margin-bottom:26px;}
  .stat-card{background:var(--card);border:1px solid var(--border2);border-radius:12px;padding:20px;transition:border-color .2s,transform .2s;animation:fadeUp .4s ease both;}
  .stat-card:hover{border-color:var(--border);transform:translateY(-2px);}
  .stat-card:nth-child(1){animation-delay:.05s}.stat-card:nth-child(2){animation-delay:.1s}.stat-card:nth-child(3){animation-delay:.15s}.stat-card:nth-child(4){animation-delay:.2s}
  @keyframes fadeUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:translateY(0)}}
  .stat-icon{font-size:22px;margin-bottom:10px;}
  .stat-value{font-family:'Cormorant Garamond',serif;font-size:34px;font-weight:700;line-height:1;margin-bottom:3px;}
  .stat-label{font-size:12px;color:var(--muted);font-weight:500;}

  .content-grid{display:grid;grid-template-columns:1fr 295px;gap:18px;}
  .section-title{font-family:'Cormorant Garamond',serif;font-size:21px;font-weight:700;margin-bottom:13px;display:flex;align-items:center;gap:7px;}
  .section-title span{font-size:13px;color:var(--muted);font-family:'DM Sans',sans-serif;font-weight:400;}

  .ann-card{background:var(--card);border:1px solid var(--border2);border-radius:11px;padding:16px 18px;margin-bottom:11px;transition:border-color .2s;animation:fadeUp .4s ease .28s both;}
  .ann-card:hover{border-color:var(--border);}
  .ann-date{font-size:11px;color:var(--gold);font-weight:600;margin-bottom:3px;letter-spacing:.4px;}
  .ann-title{font-size:14px;font-weight:600;margin-bottom:4px;}
  .ann-desc{font-size:13px;color:var(--muted);line-height:1.5;}

  .quick-grid{display:grid;grid-template-columns:1fr 1fr;gap:9px;margin-bottom:18px;}
  .quick-link{background:var(--card);border:1px solid var(--border2);border-radius:10px;padding:15px;text-align:center;text-decoration:none;color:var(--text);transition:all .15s;animation:fadeUp .4s ease .32s both;cursor:pointer;}
  .quick-link:hover{border-color:var(--border);background:var(--card2);transform:translateY(-1px);}
  .quick-link-icon{font-size:20px;margin-bottom:5px;}
  .quick-link-label{font-size:12px;color:var(--muted);font-weight:500;}

  .team-card{background:var(--card);border:1px solid var(--border2);border-radius:11px;padding:16px;animation:fadeUp .4s ease .36s both;}
  .team-member{display:flex;align-items:center;gap:9px;padding:7px 0;border-bottom:1px solid var(--border2);}
  .team-member:last-child{border-bottom:none;}
  .mem-av{width:30px;height:30px;background:linear-gradient(135deg,#5b9cf6,#7eb8fc);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#0d0d0f;flex-shrink:0;}
  .mem-name{font-size:13px;font-weight:500;}
  .mem-role{font-size:11px;color:var(--muted);}
  .mem-badge{margin-left:auto;font-size:10px;padding:2px 7px;border-radius:20px;font-weight:500;background:rgba(93,224,158,0.1);color:#5de09e;border:1px solid rgba(93,224,158,0.2);}
  .no-members{font-size:13px;color:var(--muted);text-align:center;padding:12px 0;}

  @media(max-width:900px){.stats-grid{grid-template-columns:repeat(2,1fr)}.content-grid{grid-template-columns:1fr}}
</style>
</head>
<body>

<div class="sidebar">
  <div class="sidebar-logo">
    <div class="logo-icon">N</div>
    <span class="logo-text">NexCorp</span>
  </div>
  <div class="sidebar-user">
    <div class="user-avatar"><?= strtoupper(substr($user['name'],0,1)) ?></div>
    <div class="user-name"><?= htmlspecialchars($user['name']) ?></div>
    <span class="user-role"><?= htmlspecialchars($user['role']) ?></span>
  </div>
  <nav class="sidebar-nav">
    <div class="nav-label">Main Menu</div>
    <a href="#" class="nav-item active"><span class="nav-icon">🏠</span> Dashboard</a>
    <a href="#" class="nav-item"><span class="nav-icon">📋</span> Projects</a>
    <a href="#" class="nav-item"><span class="nav-icon">✅</span> Tasks</a>
    <a href="#" class="nav-item"><span class="nav-icon">📅</span> Calendar</a>
    <a href="#" class="nav-item"><span class="nav-icon">💬</span> Messages</a>
    <br>
    <div class="nav-label">Office</div>
    <a href="#" class="nav-item"><span class="nav-icon">👥</span> Team</a>
    <a href="#" class="nav-item"><span class="nav-icon">📁</span> Documents</a>
    <a href="#" class="nav-item"><span class="nav-icon">📊</span> Reports</a>
  </nav>
  <div class="sidebar-bottom">
    <a href="logout.php" class="logout-btn">🚪 Logout</a>
  </div>
</div>

<main class="main">
  <div class="topbar">
    <div>
      <h1>Good Day, <?= htmlspecialchars(explode(' ',$user['name'])[0]) ?>!</h1>
      <div class="topbar-date"><?= date('l, d F Y') ?></div>
    </div>
    <div class="greeting-badge">✦ <?= htmlspecialchars($user['role']) ?> Portal</div>
  </div>

  <div class="stats-grid">
    <?php foreach ($stats as $s): ?>
    <div class="stat-card">
      <div class="stat-icon"><?= $s['icon'] ?></div>
      <div class="stat-value" style="color:<?= $s['color'] ?>"><?= $s['value'] ?></div>
      <div class="stat-label"><?= $s['label'] ?></div>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="content-grid">
    <div>
      <div class="section-title">📢 Announcements <span>Latest updates</span></div>
      <?php foreach ($announcements as $a): ?>
      <div class="ann-card">
        <div class="ann-date"><?= $a['date'] ?></div>
        <div class="ann-title"><?= $a['title'] ?></div>
        <div class="ann-desc"><?= $a['desc'] ?></div>
      </div>
      <?php endforeach; ?>
    </div>
    <div>
      <div class="section-title">⚡ Quick Access</div>
      <div class="quick-grid">
        <?php foreach ($quickLinks as $ql): ?>
        <a href="#" class="quick-link">
          <div class="quick-link-icon"><?= $ql['icon'] ?></div>
          <div class="quick-link-label"><?= $ql['label'] ?></div>
        </a>
        <?php endforeach; ?>
      </div>
      <div class="section-title">👥 Registered Users</div>
      <div class="team-card">
        <?php if (empty($allUsers)): ?>
          <p class="no-members">Koi registered user nahi</p>
        <?php else: foreach (array_slice($allUsers,0,6) as $m): ?>
        <div class="team-member">
          <div class="mem-av"><?= strtoupper(substr($m['name'],0,1)) ?></div>
          <div>
            <div class="mem-name"><?= htmlspecialchars($m['name']) ?></div>
            <div class="mem-role"><?= htmlspecialchars($m['role']) ?></div>
          </div>
          <span class="mem-badge">Active</span>
        </div>
        <?php endforeach; endif; ?>
      </div>
    </div>
  </div>
</main>
</body>
</html>
