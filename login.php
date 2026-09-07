<?php
require_once __DIR__ . '/config.php';
if (isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}
$csrfToken = generateCsrfToken();
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Habit Tracker Pro</title>
<link rel="icon" type="image/svg+xml" href="favicon.svg">
<link rel="manifest" href="manifest.json">
<meta name="theme-color" content="#6366f1">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
:root{
--bg:#f8f7ff;--card:#ffffff;--text:#1a1035;--sub:#6b6494;--muted:#a09bbe;
--accent:#6366f1;--accent-light:#818cf8;--accent-glow:rgba(99,102,241,0.25);
--success:#10b981;--danger:#ef4444;
--border:rgba(99,102,241,0.08);--shadow:0 1px 3px rgba(0,0,0,0.04),0 8px 32px rgba(99,102,241,0.08);
--radius:16px;--radius-sm:10px;--radius-xs:8px;
--gradient:linear-gradient(135deg,#6366f1,#8b5cf6,#a78bfa);
}
[data-theme="dark"]{
--bg:#0c0817;--card:#16102b;--text:#ece9f7;--sub:#9d96bb;--muted:#5e5785;
--accent:#818cf8;--accent-light:#a5b4fc;--accent-glow:rgba(129,140,248,0.2);
--success:#34d399;--danger:#f87171;
--border:rgba(129,140,248,0.1);--shadow:0 1px 3px rgba(0,0,0,0.2),0 8px 32px rgba(0,0,0,0.3);
}
body{font-family:'Inter',system-ui,-apple-system,sans-serif;background:var(--bg);color:var(--text);min-height:100vh;display:flex;transition:background 0.4s,color 0.4s}

/* LEFT PANEL */
.panel-left{flex:1;display:flex;flex-direction:column;justify-content:center;align-items:center;padding:40px;position:relative;overflow:hidden;background:var(--gradient);min-height:100vh}
.panel-left::before{content:'';position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat}
.brand{position:relative;z-index:1;text-align:center;color:#fff}
.brand-icon{width:80px;height:80px;border-radius:24px;background:rgba(255,255,255,0.15);backdrop-filter:blur(10px);display:flex;align-items:center;justify-content:center;font-size:40px;margin:0 auto 24px;border:1px solid rgba(255,255,255,0.2);box-shadow:0 8px 32px rgba(0,0,0,0.1)}
.brand h1{font-size:32px;font-weight:900;letter-spacing:-0.5px;margin-bottom:8px}
.brand p{font-size:15px;opacity:0.85;max-width:320px;line-height:1.6}
.features-mini{margin-top:40px;display:flex;flex-direction:column;gap:14px;position:relative;z-index:1}
.feature-item{display:flex;align-items:center;gap:12px;padding:12px 20px;background:rgba(255,255,255,0.1);backdrop-filter:blur(10px);border-radius:12px;border:1px solid rgba(255,255,255,0.12);color:#fff;font-size:14px;font-weight:500}
.feature-item span{font-size:20px}

/* RIGHT PANEL */
.panel-right{flex:1;display:flex;align-items:center;justify-content:center;padding:40px;position:relative}
.auth-card{width:100%;max-width:400px;animation:fadeUp 0.6s ease-out}
@keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
.auth-header{margin-bottom:32px}
.auth-header .mobile-logo{display:none;font-size:32px;margin-bottom:12px}
.auth-header h2{font-size:26px;font-weight:800;letter-spacing:-0.3px;margin-bottom:6px}
.auth-header p{color:var(--sub);font-size:14px;line-height:1.5}
.form-group{margin-bottom:18px}
.form-group label{display:flex;align-items:center;gap:6px;font-size:12px;font-weight:700;margin-bottom:7px;color:var(--sub);text-transform:uppercase;letter-spacing:0.6px}
.input-wrap{position:relative}
.input-wrap svg{position:absolute;left:14px;top:50%;transform:translateY(-50%);width:18px;height:18px;color:var(--muted);pointer-events:none;transition:color 0.2s}
.input-wrap input:focus~svg{color:var(--accent)}
.form-group input[type="text"],.form-group input[type="password"],.form-group input[type="email"]{width:100%;padding:13px 14px 13px 42px;border:1.5px solid var(--border);border-radius:var(--radius-xs);font-size:14px;background:var(--bg);color:var(--text);transition:all 0.2s;font-family:inherit}
.form-group input:focus{outline:none;border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-glow)}
.form-group input::placeholder{color:var(--muted)}
.pw-toggle{position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;padding:4px;color:var(--muted);transition:color 0.2s;line-height:1}
.pw-toggle:hover{color:var(--accent)}
.form-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px}
.remember{display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;color:var(--sub);user-select:none}
.remember input{width:16px;height:16px;accent-color:var(--accent);cursor:pointer;border-radius:4px}
.forgot{font-size:13px;color:var(--accent);text-decoration:none;font-weight:600;transition:opacity 0.2s}
.forgot:hover{opacity:0.8;text-decoration:underline}
.btn-submit{width:100%;padding:14px;border:none;border-radius:var(--radius-sm);font-size:15px;font-weight:700;cursor:pointer;transition:all 0.25s;font-family:inherit;display:flex;align-items:center;justify-content:center;gap:8px;background:var(--gradient);color:#fff;box-shadow:0 4px 16px var(--accent-glow);position:relative;overflow:hidden}
.btn-submit:hover{transform:translateY(-1px);box-shadow:0 8px 24px var(--accent-glow)}
.btn-submit:active{transform:translateY(0)}
.btn-submit:disabled{opacity:0.6;cursor:not-allowed;transform:none}
.btn-submit .spinner{display:none;width:18px;height:18px;border:2.5px solid rgba(255,255,255,0.3);border-top-color:#fff;border-radius:50%;animation:spin 0.6s linear infinite}
.btn-submit.loading .spinner{display:inline-block}
.btn-submit.loading .btn-label{display:none}
@keyframes spin{to{transform:rotate(360deg)}}
.divider{display:flex;align-items:center;gap:14px;margin:24px 0}
.divider::before,.divider::after{content:'';flex:1;height:1px;background:var(--border)}
.divider span{font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:1px}
.signup-link{text-align:center;font-size:14px;color:var(--sub)}
.signup-link a{color:var(--accent);text-decoration:none;font-weight:700}
.signup-link a:hover{text-decoration:underline}
.social-proof{display:flex;align-items:center;justify-content:center;gap:10px;margin-top:24px;font-size:12px;color:var(--muted)}
.avatar-stack{display:flex}
.avatar-stack i{width:28px;height:28px;border-radius:50%;border:2px solid var(--card);margin-left:-8px;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;font-style:normal;color:#fff}
.avatar-stack i:nth-child(1){background:#6366f1}
.avatar-stack i:nth-child(2){background:#ec4899}
.avatar-stack i:nth-child(3){background:#f59e0b}
.avatar-stack i:nth-child(4){background:#10b981}
.avatar-stack i:first-child{margin-left:0}
.error-msg{background:rgba(239,68,68,0.08);color:var(--danger);padding:12px 16px;border-radius:var(--radius-xs);font-size:13px;font-weight:600;margin-bottom:16px;display:none;align-items:center;gap:8px;border:1px solid rgba(239,68,68,0.12)}
.error-msg.visible{display:flex;animation:shake 0.4s ease}
@keyframes shake{0%,100%{transform:translateX(0)}25%{transform:translateX(-5px)}75%{transform:translateX(5px)}}
.success-msg{background:rgba(16,185,129,0.08);color:var(--success);padding:12px 16px;border-radius:var(--radius-xs);font-size:13px;font-weight:600;margin-bottom:16px;display:none;align-items:center;gap:8px;border:1px solid rgba(16,185,129,0.12)}
.success-msg.visible{display:flex}
.theme-btn{position:fixed;top:20px;right:20px;background:var(--card);border:1.5px solid var(--border);border-radius:50%;width:40px;height:40px;cursor:pointer;font-size:16px;display:flex;align-items:center;justify-content:center;transition:all 0.2s;box-shadow:var(--shadow);z-index:10}
.theme-btn:hover{transform:scale(1.08);border-color:var(--accent)}
.back-link{position:fixed;top:20px;left:20px;display:flex;align-items:center;gap:5px;padding:8px 14px;background:var(--card);border:1.5px solid var(--border);border-radius:var(--radius-sm);color:var(--sub);font-size:13px;font-weight:600;text-decoration:none;transition:all 0.2s;box-shadow:var(--shadow);z-index:10}
.back-link:hover{border-color:var(--accent);color:var(--accent)}
@media(max-width:768px){
body{flex-direction:column}
.panel-left{display:none}
.panel-right{padding:24px 16px;min-height:100vh}
.auth-header .mobile-logo{display:block}
.auth-card{max-width:100%}
.back-link{top:auto;bottom:20px;left:50%;transform:translateX(-50%);font-size:12px}
.theme-btn{top:auto;bottom:20px;right:20px}
}
@media(max-width:400px){
.panel-right{padding:16px 12px}
.auth-header h2{font-size:22px}
}
</style>
</head>
<body>
<a href="landing.php" class="back-link">← Home</a>
<button class="theme-btn" onclick="toggleTheme()" title="Toggle theme">🌙</button>

<div class="panel-left">
<div class="brand">
<div class="brand-icon">🎯</div>
<h1>Habit Tracker Pro</h1>
<p>Build better habits, track streaks, and become the best version of yourself.</p>
<div class="features-mini">
<div class="feature-item"><span>📊</span> Smart analytics & weekly reports</div>
<div class="feature-item"><span>🔥</span> Streak tracking & freeze shields</div>
<div class="feature-item"><span>🤖</span> AI-powered insights & tips</div>
<div class="feature-item"><span>🤝</span> Partner accountability</div>
</div>
</div>
</div>

<div class="panel-right">
<div class="auth-card">
<div class="auth-header">
<div class="mobile-logo">🎯</div>
<h2>Welcome back</h2>
<p>Enter your credentials to access your dashboard</p>
</div>

<div class="error-msg" id="errorMsg"></div>
<div class="success-msg" id="successMsg"></div>

<form id="loginForm" onsubmit="return handleLogin(event)">
<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">

<div class="form-group">
<label>Username or email</label>
<div class="input-wrap">
<input type="text" id="username" name="username" placeholder="you@example.com" required autocomplete="username">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
</div>
</div>

<div class="form-group">
<label>Password</label>
<div class="input-wrap">
<input type="password" id="password" name="password" placeholder="Enter your password" required autocomplete="current-password">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="pointer-events:none"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
<button type="button" class="pw-toggle" onclick="togglePw()" title="Show/hide password">
<svg id="pwShow" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
<svg id="pwHide" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
</button>
</div>
</div>

<div class="form-row">
<label class="remember"><input type="checkbox" id="remember" name="remember"> Remember me</label>
<a href="#" class="forgot" onclick="showForgot(event)">Forgot password?</a>
</div>

<button type="submit" class="btn-submit" id="loginBtn">
<span class="btn-label">Sign In</span>
<span class="spinner"></span>
</button>
</form>

<div class="divider"><span>or</span></div>

<p class="signup-link">Don't have an account? <a href="signup.php">Create one free</a></p>

<div class="social-proof">
<div class="avatar-stack"><i>A</i><i>S</i><i>M</i><i>R</i></div>
<span>Join 500+ users building better habits</span>
</div>
</div>
</div>

<script>
function toggleTheme(){var t=document.documentElement.getAttribute('data-theme'),n=t==='dark'?'light':'dark';document.documentElement.setAttribute('data-theme',n);localStorage.setItem('authTheme',n);document.querySelector('.theme-btn').textContent=n==='dark'?'☀️':'🌙';}
(function(){var s=localStorage.getItem('authTheme');if(s){document.documentElement.setAttribute('data-theme',s);document.querySelector('.theme-btn').textContent=s==='dark'?'☀️':'🌙';}else if(matchMedia('(prefers-color-scheme:dark)').matches){document.documentElement.setAttribute('data-theme','dark');document.querySelector('.theme-btn').textContent='☀️';}})();
function togglePw(){var p=document.getElementById('password'),s=document.getElementById('pwShow'),h=document.getElementById('pwHide');if(p.type==='password'){p.type='text';s.style.display='none';h.style.display='block';}else{p.type='password';s.style.display='block';h.style.display='none';}}
function showError(m){var e=document.getElementById('errorMsg');e.textContent=m;e.classList.add('visible');document.getElementById('successMsg').classList.remove('visible');}
function hideError(){document.getElementById('errorMsg').classList.remove('visible');}
function showSuccess(m){var e=document.getElementById('successMsg');e.textContent=m;e.classList.add('visible');document.getElementById('errorMsg').classList.remove('visible');}
function showForgot(e){e.preventDefault();showError('Password reset is not yet available. Contact support for help.');}
function handleLogin(e){
e.preventDefault();hideError();
var b=document.getElementById('loginBtn');b.disabled=true;b.classList.add('loading');
var fd=new FormData(document.getElementById('loginForm'));fd.append('action','login');
fetch('auth.php',{method:'POST',body:fd}).then(function(r){return r.json()}).then(function(d){
if(d.success){showSuccess('Login successful! Redirecting...');setTimeout(function(){location.href=d.redirect||'index.php';},600);}
else{showError(d.error||'Login failed');b.disabled=false;b.classList.remove('loading');}
}).catch(function(){showError('Network error. Please try again.');b.disabled=false;b.classList.remove('loading');});
return false;
}
</script>
</body>
</html>
