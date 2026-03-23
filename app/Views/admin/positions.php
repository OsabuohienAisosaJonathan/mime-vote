<?php
?>

<div class="glass-panel" style="max-width: 1200px; margin: 0 auto; padding: 2rem;">
    <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 class="gradient-text" style="font-size: 2.5rem; font-weight: 700;">Manage Positions</h1>
            <p style="color: var(--text-muted); margin-top: 0.5rem;">Configure ballot positions for each election.</p>
        </div>
        <div>
            <a href="/MIME-VOTE/admin/positions/create" class="btn btn-primary">+ New Position</a>
            <a href="/MIME-VOTE/admin/dashboard" class="btn outline" style="margin-left: 1rem;">Back</a>
        </div>
    </header>

    <div class="glass-card" style="padding: 1.5rem; overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.1); color: var(--text-muted);">
                    <th style="padding: 1rem;">Election</th>
                    <th style="padding: 1rem;">Position Title</th>
                    <th style="padding: 1rem;">Max Votes (Selectable)</th>
                    <th style="padding: 1rem;">Order</th>
                    <th style="padding: 1rem;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($positions)): ?>
                    <tr>
                        <td colspan="5" style="padding: 2rem; text-align: center; color: var(--text-muted);">No positions found. Create one.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($positions as $position): ?>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td style="padding: 1rem; color: #8b5cf6; font-weight: 500;"><?= htmlspecialchars($position['election_title']) ?></td>
                            <td style="padding: 1rem; font-weight: 500; font-size: 1.1rem;"><?= htmlspecialchars($position['title']) ?></td>
                            <td style="padding: 1rem; color: var(--text-muted);"><?= (int)($position['max_votes_per_user'] ?? 1) ?> Candidate(s)</td>
                            <td style="padding: 1rem; color: var(--text-muted);"><?= $position['display_order'] ?></td>
                            <td style="padding: 1rem;">
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="/MIME-VOTE/admin/positions/edit/<?= $position['id'] ?>" class="btn" style="padding: 0.4rem 1rem; font-size: 0.875rem; background: rgba(255,255,255,0.05); color: white;">Edit</a>
                                    
                                    <form action="/MIME-VOTE/admin/positions/delete/<?= $position['id'] ?>" method="POST" onsubmit="return confirm('Delete this position? This will also remove all associated candidates and votes.');">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <button type="submit" class="btn" style="padding: 0.4rem 1rem; font-size: 0.875rem; background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2);">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
