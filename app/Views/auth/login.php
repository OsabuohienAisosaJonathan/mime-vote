<div style="display: flex; justify-content: center; align-items: center; min-height: 80vh;">
    <div class="glass-panel" style="width: 100%; max-width: 400px;">
        <h2 style="text-align: center; margin-bottom: 2rem;">Voter Authentication</h2>
        
        <form action="/MIME-VOTE/login" method="POST" class="ajax-form">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" required placeholder="Enter your registered email">
            </div>
            
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="••••••••">
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Secure Login</button>
        </form>

        <p style="text-align: center; margin-top: 2rem; font-size: 0.875rem;">
            Don't have an account? <a href="/MIME-VOTE/register" style="color: var(--accent); text-decoration: none;">Register here</a>
        </p>
    </div>
</div>
