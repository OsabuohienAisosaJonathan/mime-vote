<?php
?>

<div class="glass-panel" style="max-width: 1200px; margin: 0 auto; padding: 2rem;">
    <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 class="gradient-text" style="font-size: 2.5rem; font-weight: 700;">Manage Elections</h1>
            <p style="color: var(--text-muted); margin-top: 0.5rem;">Create, edit, and organize voting events.</p>
        </div>
        <div>
            <a href="/MIME-VOTE/admin/elections/create" class="btn btn-primary">+ New Election</a>
            <a href="/MIME-VOTE/admin/dashboard" class="btn outline" style="margin-left: 1rem;">Back to Dashboard</a>
        </div>
    </header>

    <div class="glass-card" style="padding: 1.5rem; overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.1); color: var(--text-muted);">
                    <th style="padding: 1rem;">Title</th>
                    <th style="padding: 1rem;">Status</th>
                    <th style="padding: 1rem;">Start Date</th>
                    <th style="padding: 1rem;">End Date</th>
                    <th style="padding: 1rem;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($elections)): ?>
                    <tr>
                        <td colspan="5" style="padding: 2rem; text-align: center; color: var(--text-muted);">No elections found. Create one to get started.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($elections as $election): ?>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td style="padding: 1rem; font-weight: 500;"><?= htmlspecialchars($election['title']) ?></td>
                            <td style="padding: 1rem;">
                                <?php if ($election['status'] === 'active'): ?>
                                    <span style="background: rgba(16, 185, 129, 0.2); color: #10b981; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem;">Active</span>
                                <?php elseif ($election['status'] === 'completed'): ?>
                                    <span style="background: rgba(139, 92, 246, 0.2); color: #8b5cf6; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem;">Completed</span>
                                <?php else: ?>
                                    <span style="background: rgba(245, 158, 11, 0.2); color: #f59e0b; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem;">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 1rem; color: var(--text-muted);"><?= htmlspecialchars(date('M d, Y H:i', strtotime($election['start_date']))) ?></td>
                            <td style="padding: 1rem; color: var(--text-muted);"><?= htmlspecialchars(date('M d, Y H:i', strtotime($election['end_date']))) ?></td>
                            <td style="padding: 1rem;">
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="/MIME-VOTE/admin/elections/edit/<?= $election['id'] ?>" class="btn" style="padding: 0.4rem 1rem; font-size: 0.875rem; background: rgba(255,255,255,0.05); color: white;">Edit</a>
                                    
                                    <form action="/MIME-VOTE/admin/elections/delete/<?= $election['id'] ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this election? This cannot be undone.');">
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
