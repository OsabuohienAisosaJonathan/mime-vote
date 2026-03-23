<?php
?>

<div class="glass-panel" style="max-width: 1200px; margin: 0 auto; padding: 2rem;">
    <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 class="gradient-text" style="font-size: 2.5rem; font-weight: 700;">Admin Dashboard</h1>
            <p style="color: var(--text-muted); margin-top: 0.5rem;">Welcome back, <?= htmlspecialchars($userFirstName) ?></p>
        </div>
        <div>
            <a href="/MIME-VOTE/admin/elections/create" class="btn btn-primary" style="margin-right: 1rem;">+ New Election</a>
            <a href="/MIME-VOTE/admin/users" class="btn outline">Manage Users</a>
        </div>
    </header>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
        <div class="glass-card" style="padding: 1.5rem; text-align: center;">
            <div style="font-size: 2.5rem; font-weight: 700; color: var(--primary);"><?= $stats['active_elections'] ?></div>
            <div style="color: var(--text-muted); font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 0.5rem;">Active Elections</div>
        </div>
        <div class="glass-card" style="padding: 1.5rem; text-align: center;">
            <div style="font-size: 2.5rem; font-weight: 700; color: var(--secondary);"><?= $stats['total_elections'] ?></div>
            <div style="color: var(--text-muted); font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 0.5rem;">Total Elections</div>
        </div>
        <div class="glass-card" style="padding: 1.5rem; text-align: center;">
            <div style="font-size: 2.5rem; font-weight: 700; color: #10b981;"><?= $stats['total_candidates'] ?></div>
            <div style="color: var(--text-muted); font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 0.5rem;">Verified Candidates</div>
        </div>
        <div class="glass-card" style="padding: 1.5rem; text-align: center;">
            <div style="font-size: 2.5rem; font-weight: 700; color: #f59e0b;"><?= $stats['total_votes'] ?></div>
            <div style="color: var(--text-muted); font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 0.5rem;">Votes Cast</div>
        </div>
        <div class="glass-card" style="padding: 1.5rem; text-align: center;">
            <div style="font-size: 2.5rem; font-weight: 700; color: #8b5cf6;"><?= $stats['total_users'] ?></div>
            <div style="color: var(--text-muted); font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 0.5rem;">Registered Voters</div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
        <div class="glass-card" style="padding: 2rem;">
            <h2 style="margin-bottom: 1.5rem; font-size: 1.25rem;">Quick Management Links</h2>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <a href="/MIME-VOTE/admin/elections" style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: rgba(255,255,255,0.03); border-radius: 8px; text-decoration: none; color: white;">
                    <span>🗳️ Elections Setup</span>
                    <span style="color: var(--primary);">Manage &rarr;</span>
                </a>
                <a href="/MIME-VOTE/admin/positions" style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: rgba(255,255,255,0.03); border-radius: 8px; text-decoration: none; color: white;">
                    <span>👔 Positions Setup</span>
                    <span style="color: var(--primary);">Manage &rarr;</span>
                </a>
                <a href="/MIME-VOTE/admin/candidates" style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: rgba(255,255,255,0.03); border-radius: 8px; text-decoration: none; color: white;">
                    <span>👥 Candidates Setup</span>
                    <span style="color: var(--primary);">Manage &rarr;</span>
                </a>
            </div>
        </div>

        <div class="glass-card" style="padding: 2rem;">
            <h2 style="margin-bottom: 1.5rem; font-size: 1.25rem;">System Health & Audit</h2>
            <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem; color: #10b981; font-weight: 500;">
                    <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #10b981; box-shadow: 0 0 10px #10b981;"></span>
                    Cryptographic Ledger Engine Online
                </div>
            </div>
            <div style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2); padding: 1rem; border-radius: 8px;">
                <div style="display: flex; align-items: center; gap: 0.5rem; color: #3b82f6; font-weight: 500;">
                    <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #3b82f6; box-shadow: 0 0 10px #3b82f6;"></span>
                    AES-256 Storage Online
                </div>
            </div>
        </div>
    </div>
</div>
