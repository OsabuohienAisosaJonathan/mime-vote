<?php
?>

<div class="glass-panel" style="max-width: 1200px; margin: 0 auto; padding: 2rem;">
    <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 class="gradient-text" style="font-size: 2.5rem; font-weight: 700;">Manage Candidates</h1>
            <p style="color: var(--text-muted); margin-top: 0.5rem;">Register and manage candidates for active election positions.</p>
        </div>
        <div>
            <a href="/MIME-VOTE/admin/candidates/create" class="btn btn-primary">+ Register Candidate</a>
            <a href="/MIME-VOTE/admin/dashboard" class="btn outline" style="margin-left: 1rem; border: 1px solid var(--border); color: var(--text-main);">Back</a>
        </div>
    </header>

    <div class="glass-card" style="padding: 1.5rem; overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.1); color: var(--text-muted);">
                    <th style="padding: 1rem;">Candidate Name</th>
                    <th style="padding: 1rem;">Election</th>
                    <th style="padding: 1rem;">Position</th>
                    <th style="padding: 1rem;">Manifesto (URL)</th>
                    <th style="padding: 1rem;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($candidates)): ?>
                    <tr>
                        <td colspan="5" style="padding: 2rem; text-align: center; color: var(--text-muted);">No candidates registered yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($candidates as $candidate): ?>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td style="padding: 1rem; font-weight: 600; font-size: 1.1rem; display: flex; align-items: center; gap: 0.75rem;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: #3b82f6; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">
                                    <?= substr($candidate['first_name'], 0, 1) . substr($candidate['last_name'], 0, 1) ?>
                                </div>
                                <?= htmlspecialchars($candidate['first_name'] . ' ' . $candidate['last_name']) ?>
                            </td>
                            <td style="padding: 1rem; color: var(--text-muted);"><?= htmlspecialchars($candidate['election_title']) ?></td>
                            <td style="padding: 1rem; color: #10b981; font-weight: 500;"><?= htmlspecialchars($candidate['position_title']) ?></td>
                            <td style="padding: 1rem;">
                                <?php if ($candidate['manifesto_url']): ?>
                                    <a href="<?= htmlspecialchars($candidate['manifesto_url']) ?>" target="_blank" style="color: #3b82f6; text-decoration: none;">View Link &rarr;</a>
                                <?php else: ?>
                                    <span style="color: var(--text-muted); font-style: italic;">None Provided</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 1rem;">
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="/MIME-VOTE/admin/candidates/edit/<?= $candidate['id'] ?>" class="btn" style="padding: 0.4rem 1rem; font-size: 0.875rem; background: rgba(255,255,255,0.05); color: white;">Edit</a>
                                    
                                    <form action="/MIME-VOTE/admin/candidates/delete/<?= $candidate['id'] ?>" method="POST" onsubmit="return confirm('Remove candidate? This action is permanent.');" style="margin: 0;">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <button type="submit" class="btn" style="padding: 0.4rem 1rem; font-size: 0.875rem; background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2);">Remove</button>
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
