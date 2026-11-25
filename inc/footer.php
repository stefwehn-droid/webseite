<?php require __DIR__ . '/shop-band.php'; ?>
</main>

<footer class="site-footer">
    <p class="counter">Besucher <span class="digits"><?= number_format(isset($visits) ? $visits : visitor_count(false), 0, ',', '.') ?></span></p>
    <p>&copy; <?= date('Y') ?> <?= h(SITE_AUTHOR) ?> · Alle Rechte vorbehalten · <a href="<?= h(url('/impressum.php')) ?>">Impressum</a></p>
</footer>

<button id="back-to-top" type="button" aria-label="Nach oben" hidden>&#8679;</button>
<script src="<?= h(asset('assets/js/site.js')) ?>" defer></script>
</body>
</html>
