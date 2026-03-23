<?php
$isEdit = !empty($election);
?>

<div class="glass-panel" style="max-width: 800px; margin: 0 auto; padding: 2rem;">
    <header style="margin-bottom: 2rem;">
        <h1 class="gradient-text" style="font-size: 2rem; font-weight: 700;"><?= htmlspecialchars($title) ?></h1>
        <p style="color: var(--text-muted); margin-top: 0.5rem;">Fill out the details below to define the election parameters.</p>
    </header>

    <form action="<?= htmlspecialchars($action) ?>" method="POST" style="margin: 0; padding: 0;">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        
        <div class="form-group">
            <label for="title" class="form-label">Election Title</label>
            <input type="text" id="title" name="title" class="form-control" required placeholder="e.g. Student Union General Election 2026" value="<?= $isEdit ? htmlspecialchars($election['title']) : '' ?>">
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-control" rows="4" placeholder="Brief details about the election..." style="resize: vertical;"><?= $isEdit ? htmlspecialchars($election['description']) : '' ?></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label for="start_date" class="form-label">Start Date & Time</label>
                <input type="datetime-local" id="start_date" name="start_date" class="form-control" required value="<?= $isEdit ? date('Y-m-d\TH:i', strtotime($election['start_date'])) : '' ?>">
            </div>
            
            <div class="form-group">
                <label for="end_date" class="form-label">End Date & Time</label>
                <input type="datetime-local" id="end_date" name="end_date" class="form-control" required value="<?= $isEdit ? date('Y-m-d\TH:i', strtotime($election['end_date'])) : '' ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="status" class="form-label">Election Status</label>
            <select id="status" name="status" class="form-control">
                <option value="draft" <?= $isEdit && $election['status'] === 'draft' ? 'selected' : '' ?>>Draft (Hidden)</option>
                <option value="active" <?= $isEdit && $election['status'] === 'active' ? 'selected' : '' ?>>Active (Visible & Open)</option>
                <option value="completed" <?= $isEdit && $election['status'] === 'completed' ? 'selected' : '' ?>>Completed (Closed)</option>
            </select>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary" style="flex: 1;"><?= $isEdit ? 'Save Changes' : 'Create Election' ?></button>
            <a href="/MIME-VOTE/admin/elections" class="btn outline" style="flex: 1; text-align: center; text-decoration: none; border: 1px solid var(--border); color: var(--text-main);">Cancel</a>
        </div>
    </form>
</div>

<style>
/* Reset select dropdown styles for dark mode aesthetics */
select option {
    background: #111827;
    color: white;
}
/* Time picker icon color fix for webkit */
::-webkit-calendar-picker-indicator {
    filter: invert(1);
    opacity: 0.6;
    cursor: pointer;
}
</style>
