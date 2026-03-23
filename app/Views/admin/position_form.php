<?php
$isEdit = !empty($position);
?>

<div class="glass-panel" style="max-width: 800px; margin: 0 auto; padding: 2rem;">
    <header style="margin-bottom: 2rem;">
        <h1 class="gradient-text" style="font-size: 2rem; font-weight: 700;"><?= htmlspecialchars($title) ?></h1>
        <p style="color: var(--text-muted); margin-top: 0.5rem;">Define the ballot position details and rules.</p>
    </header>

    <form action="<?= htmlspecialchars($action) ?>" method="POST" style="margin: 0; padding: 0;">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        
        <div class="form-group">
            <label for="election_id" class="form-label">Linked Election</label>
            <select id="election_id" name="election_id" required class="form-control">
                <option value="" disabled <?= !$isEdit ? 'selected' : '' ?>>Select an Election...</option>
                <?php foreach ($elections as $elec): ?>
                    <option value="<?= $elec['id'] ?>" <?= ($isEdit && $position['election_id'] == $elec['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($elec['title']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="title" class="form-label">Position Title</label>
            <input type="text" id="title" name="title" required class="form-control" placeholder="e.g. President, Treasurer, Senator" value="<?= $isEdit ? htmlspecialchars($position['title']) : '' ?>">
        </div>



        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label for="max_votes_per_user" class="form-label">Max Votes (Candidates Selectable)</label>
                <input type="number" id="max_votes_per_user" name="max_votes_per_user" min="1" required class="form-control" value="<?= $isEdit ? (int)$position['max_votes_per_user'] : 1 ?>">
                <small style="color: var(--text-muted); display: block; margin-top: 0.5rem;">E.g. 1 for President, 3 for Senators.</small>
            </div>
            
            <div class="form-group">
                <label for="display_order" class="form-label">Display Order (Sort Priority)</label>
                <input type="number" id="display_order" name="display_order" class="form-control" value="<?= $isEdit ? (int)$position['display_order'] : 0 ?>">
                <small style="color: var(--text-muted); display: block; margin-top: 0.5rem;">Lower numbers appear first on the ballot.</small>
            </div>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary" style="flex: 1;"><?= $isEdit ? 'Save Changes' : 'Create Position' ?></button>
            <a href="/MIME-VOTE/admin/positions" class="btn outline" style="flex: 1; text-align: center; text-decoration: none; border: 1px solid var(--border); color: var(--text-main);">Cancel</a>
        </div>
    </form>
</div>

<style>
select option { background: #111827; color: white; }
</style>
