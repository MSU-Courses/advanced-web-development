<p>
    <label>
        <?= h($label) ?>
<?php if ($multiline ?? false): ?>
        <textarea name="<?= h($name) ?>"><?= h($value) ?></textarea>
<?php else: ?>
        <input name="<?= h($name) ?>" value="<?= h($value) ?>">
<?php endif; ?>
    </label>
<?php if ($error !== ''): ?>
    <span class="error"><?= h($error) ?></span>
<?php endif; ?>
</p>
