        </main>
        </div>

        <script src="<?php echo SITE_URL; ?>/js/app-minimal.js"></script>
        <?php if (isset($extraScripts)): ?>
            <?php foreach ($extraScripts as $script): ?>
                <script src="<?php echo SITE_URL; ?>/<?php echo $script; ?>"></script>
            <?php endforeach; ?>
        <?php endif; ?>
        </body>

        </html>

