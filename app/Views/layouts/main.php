<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'UTEVS | Transparent E-Voting') ?></title>
    <!-- Modern Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/MIME-VOTE/public/css/style.css">
</head>
<body>

    <nav class="glass-nav">
        <a href="/MIME-VOTE/" class="nav-brand">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
            </svg>
            UTEVS
        </a>
        <div class="nav-links">
            <a href="/MIME-VOTE/observer">Observer Dashboard</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <?php if(isset($_SESSION['role_id']) && in_array((int)$_SESSION['role_id'], [1, 2], true)): ?>
                    <a href="/MIME-VOTE/admin/dashboard" style="color: #f59e0b; font-weight: 600;">⚙️ Admin Panel</a>
                <?php endif; ?>
                <a href="/MIME-VOTE/dashboard">My Elections</a>
                <a href="/MIME-VOTE/logout" class="btn btn-primary" style="margin-left:1.5rem; padding:0.5rem 1rem;">Logout</a>
            <?php else: ?>
                <a href="/MIME-VOTE/login">Sign In</a>
                <a href="/MIME-VOTE/register" class="btn btn-primary" style="margin-left:1.5rem; padding:0.5rem 1rem;">Register</a>
            <?php endif; ?>
        </div>
    </nav>

    <main style="flex: 1; padding: 3rem 0;">
        <?= $content ?>
    </main>

    <footer style="text-align: center; padding: 2rem; color: var(--text-muted); font-size: 0.875rem; border-top: 1px solid var(--border); margin-top: auto;">
        <p>&copy; <?= date('Y') ?> Universal Transparent E-Voting System (UTEVS). All rights reserved.</p>
        <p style="margin-top: 0.5rem; font-size: 0.75rem;">Protected by AES-256 E2E Encryption & Cryptographic Hashing.</p>
    </footer>

    <!-- Global JS -->
    <script>
        // Form AJAX Handler Utility
        document.querySelectorAll('form.ajax-form').forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const btn = form.querySelector('button[type="submit"]');
                const originalText = btn.innerHTML;
                btn.innerHTML = 'Processing...';
                btn.disabled = true;

                const formData = new FormData(form);
                try {
                    const res = await fetch(form.action, {
                        method: 'POST',
                        body: formData
                    });
                    const data = await res.json();
                    
                    if (data.status === 'success' && data.redirect) {
                        window.location.href = data.redirect;
                    } else if (data.status === 'success') {
                        // Custom success handling (like opening a modal with receipt)
                        if(form.dataset.successCb) window[form.dataset.successCb](data);
                    } else {
                        alert(data.message || 'An error occurred.');
                    }
                } catch (err) {
                    alert('Network error occurred.');
                } finally {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            });
        });
    </script>
</body>
</html>
