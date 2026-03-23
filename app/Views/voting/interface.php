<div class="container" style="max-width: 800px;">
    <div style="text-align: center; margin-bottom: 3rem;">
        <h1><?= htmlspecialchars($election['title']) ?></h1>
        <p style="color: var(--warning);">Your vote is encrypted with AES-256 globally. Please construct your ballot.</p>
    </div>

    <form action="/MIME-VOTE/vote/cast" method="POST" class="ajax-form" data-success-cb="onVoteSuccess">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <input type="hidden" name="election_id" value="<?= $election['id'] ?>">

        <?php foreach($election['positions'] as $pos): ?>
            <div class="glass-panel" style="margin-bottom: 2rem;">
                <h3 style="margin-bottom: 1rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;"><?= htmlspecialchars($pos['title']) ?></h3>
                
                <div style="display: grid; gap: 1rem;">
                    <?php foreach($pos['candidates'] as $cand): ?>
                        <label class="card" style="display: flex; align-items: center; padding: 1rem; border: 1px solid var(--border); border-radius: var(--radius); cursor: pointer; background: rgba(0,0,0,0.2);">
                            <input type="radio" name="votes[<?= $pos['id'] ?>]" value="<?= $cand['id'] ?>" required style="margin-right: 1.5rem; transform: scale(1.5);">
                            <div>
                                <h4 style="font-size: 1.2rem; margin-bottom: 0.25rem;"><?= htmlspecialchars($cand['first_name'] . ' ' . $cand['last_name']) ?></h4>
                                <p style="font-size: 0.85rem; color: var(--text-muted);"><?= htmlspecialchars($cand['bio'] ?? '') ?></p>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div style="text-align: center; margin: 3rem 0;">
            <button type="submit" class="btn btn-primary" style="font-size: 1.2rem; padding: 1.5rem 3rem;">🔐 Encrypt & Cast Ballot</button>
        </div>
    </form>
</div>

<!-- Success Modal Template -->
<div id="receiptModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index: 100; align-items:center; justify-content:center;">
    <div class="glass-panel" style="max-width: 500px; width: 90%; text-align: center;">
        <div style="font-size: 3rem; margin-bottom: 1rem;">✅</div>
        <h2>VOTE SECURED</h2>
        <p style="margin: 1rem 0;">Your vote has been cryptographically secured. Here are your verification receipts. Keep them safe.</p>
        <div id="receiptList" style="background: rgba(0,0,0,0.5); padding: 1rem; border-radius: 8px; font-family: monospace; font-size: 1.1rem; color: var(--success); margin-bottom: 2rem; letter-spacing: 2px;"></div>
        <a href="/MIME-VOTE/dashboard" class="btn btn-primary" style="width: 100%;">Return to Dashboard</a>
    </div>
</div>

<script>
    function onVoteSuccess(data) {
        document.getElementById('receiptList').innerText = data.receipts.join('\n');
        document.getElementById('receiptModal').style.display = 'flex';
    }
</script>
