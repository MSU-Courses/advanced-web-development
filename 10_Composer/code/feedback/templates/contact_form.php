<?php if ($sent): ?>
    <p>Спасибо, <?= h($form->name) ?>! Сообщение получено.</p>
<?php else: ?>
    <form method="post">
        <p>
            <label>Имя <input name="name" value="<?= h($form->name) ?>"></label>
            <?= h($errors['name'] ?? '') ?>
        </p>
        <p>
            <label>Email <input name="email" value="<?= h($form->email) ?>"></label>
            <?= h($errors['email'] ?? '') ?>
        </p>
        <p>
            <label>Сообщение <textarea name="message"><?= h($form->message) ?></textarea></label>
            <?= h($errors['message'] ?? '') ?>
        </p>
        <button type="submit">Отправить</button>
    </form>
<?php endif; ?>
