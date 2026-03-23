<div style="display: flex; justify-content: center; align-items: center; min-height: 80vh;">
    <div class="glass-panel" style="width: 100%; max-width: 500px;">
        <h2 style="text-align: center; margin-bottom: 2rem;">Voter Registration</h2>
        
        <form action="/MIME-VOTE/register" method="POST" class="ajax-form">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">First Name</label>
                    <input type="text" name="first_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="last_name" class="form-control" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Secure Password</label>
                <input type="password" name="password" class="form-control" required minlength="8">
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Create Secure ID</button>
        </form>

        <p style="text-align: center; margin-top: 2rem; font-size: 0.875rem;">
            Already registered? <a href="/MIME-VOTE/login" style="color: var(--accent); text-decoration: none;">Login here</a>
        </p>
    </div>
</div>
