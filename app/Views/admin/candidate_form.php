<?php
$isEdit = !empty($candidate);
?>

<div class="glass-panel" style="max-width: 800px; margin: 0 auto; padding: 2rem;">
    <header style="margin-bottom: 2rem;">
        <h1 class="gradient-text" style="font-size: 2rem; font-weight: 700;"><?= htmlspecialchars($title) ?></h1>
        <p style="color: var(--text-muted); margin-top: 0.5rem;">Register a candidate for an election ballot position.</p>
    </header>

    <form action="<?= htmlspecialchars($action) ?>" method="POST" style="margin: 0; padding: 0;">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        
        <div class="form-group">
            <label for="position_id" class="form-label">Target Position</label>
            <select id="position_id" name="position_id" required class="form-control">
                <option value="" disabled <?= !$isEdit ? 'selected' : '' ?>>Select a position...</option>
                <?php foreach ($positions as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= ($isEdit && $candidate['position_id'] == $p['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['election_title'] . ' &rarr; ' . $p['position_title']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" id="first_name" name="first_name" class="form-control" required placeholder="e.g. John" value="<?= $isEdit ? htmlspecialchars($candidate['first_name']) : '' ?>">
            </div>

            <div class="form-group">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" id="last_name" name="last_name" class="form-control" required placeholder="e.g. Doe" value="<?= $isEdit ? htmlspecialchars($candidate['last_name']) : '' ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="bio" class="form-label">Biography / Manifesto Summary</label>
            <textarea id="bio" name="bio" class="form-control" rows="4" placeholder="Brief public biography for voters..." style="resize: vertical;"><?= $isEdit ? htmlspecialchars($candidate['bio']) : '' ?></textarea>
        </div>

        <div class="form-group">
            <label for="manifesto_url" class="form-label">Manifesto Link (Optional)</label>
            <input type="url" id="manifesto_url" name="manifesto_url" class="form-control" placeholder="https://..." value="<?= $isEdit ? htmlspecialchars($candidate['manifesto_url']) : '' ?>">
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary" style="flex: 1;"><?= $isEdit ? 'Save Changes' : 'Register Candidate' ?></button>
            <a href="/MIME-VOTE/admin/candidates" class="btn outline" style="flex: 1; text-align: center; text-decoration: none; border: 1px solid var(--border); color: var(--text-main);">Cancel</a>
        </div>
    </form>
</div>

<style>select option { background: #111827; color: white; }</style>
