<div class="container">
    <div style="text-align: center; margin-bottom: 3rem;">
        <h1 style="color: var(--success); display: flex; align-items: center; justify-content: center; gap: 1rem;">
            <span style="display:inline-block; width: 12px; height: 12px; background: var(--success); border-radius: 50%; box-shadow: 0 0 10px var(--success); animation: pulse 2s infinite;"></span>
            LIVE Observer Network
        </h1>
        <p>Monitor real-time system integrity and turnout telemetry on the blockchain-inspired UTEVS ledger.</p>
    </div>

    <div class="dashboard-grid">
        <?php foreach($elections as $el): ?>
            <div class="glass-panel card flex-col" style="display: flex; flex-direction: column;">
                <h3><?= htmlspecialchars($el['title']) ?></h3>
                <div class="stats-container" style="flex: 1; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin: 2rem 0; text-align: center;">
                    <div style="background: rgba(0,0,0,0.3); padding: 1.5rem; border-radius: 8px;">
                        <span style="font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted);">Votes Cast</span>
                        <div style="font-size: 2.5rem; font-weight: 700; color: var(--accent);" id="cast_<?= $el['uuid'] ?>">--</div>
                    </div>
                    <div style="background: rgba(0,0,0,0.3); padding: 1.5rem; border-radius: 8px;">
                        <span style="font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted);">Total Eligible</span>
                        <div style="font-size: 2.5rem; font-weight: 700;" id="ele_<?= $el['uuid'] ?>">--</div>
                    </div>
                </div>
                <div style="font-size: 0.8rem; color: var(--text-muted); text-align: center; margin-bottom: 1rem;" id="time_<?= $el['uuid'] ?>">Polling...</div>
                
                <button onclick="pollStats('<?= $el['uuid'] ?>')" class="btn glass-panel" style="width: 100%; border: 1px solid var(--accent); color: var(--accent);">Manually Sync Node</button>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
@keyframes pulse {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}
</style>

<script>
    async function pollStats(uuid) {
        try {
            const res = await fetch(`/MIME-VOTE/api/observer/stats/${uuid}`);
            const payload = await res.json();
            if (payload.status === 'success' && payload.data) {
                document.getElementById('cast_' + uuid).innerText = payload.data.total_cast || '0';
                document.getElementById('ele_' + uuid).innerText = payload.data.total_eligible || '0';
                document.getElementById('time_' + uuid).innerText = "Last securely recorded at: " + (payload.data.last_vote_cast_at || 'Never');
            }
        } catch (e) {
            console.error("Observer network sync failed", e);
        }
    }

    // Auto-poll every 5 seconds for active feel
    <?php foreach($elections as $el): ?>
        pollStats('<?= $el['uuid'] ?>');
        setInterval(() => pollStats('<?= $el['uuid'] ?>'), 5000);
    <?php endforeach; ?>
</script>
