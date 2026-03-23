<div class="container">
    <div style="margin-bottom: 3rem;">
        <h1 style="color: var(--text-main);">Welcome, <?= htmlspecialchars($user['name']) ?></h1>
        <p>Voter ID: <span style="font-family: monospace; color: var(--accent);"><?= htmlspecialchars($user['uuid']) ?></span></p>
    </div>

    <h2 style="margin-bottom: 1.5rem; font-size: 1.5rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">Active Elections</h2>

    <?php if(empty($active_elections)): ?>
        <div class="glass-panel" style="text-align: center; padding: 4rem;">
            <p style="font-size: 1.2rem;">There are no active elections at the moment.</p>
        </div>
    <?php else: ?>
        <div class="dashboard-grid">
            <?php foreach($active_elections as $election): ?>
                <div class="glass-panel card">
                    <h3 style="margin-bottom: 0.5rem;"><?= htmlspecialchars($election['title']) ?></h3>
                    <p style="font-size: 0.875rem; margin-bottom: 1.5rem;">
                        Closes: <?= date('M d, Y H:i', strtotime($election['end_date'])) ?>
                    </p>
                    <a href="/MIME-VOTE/vote/<?= $election['uuid'] ?>" class="btn btn-primary" style="width: 100%;">Enter Voting Booth</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
