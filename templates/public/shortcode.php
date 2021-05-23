<?php if ($maxHeight) : ?>
    <style>
        .slm-lock:not(.slm-content-show) {
            max-height: <?php echo $maxHeight ?>px;
        }
    </style>
<?php endif; ?>
<div class="slm-lock">
    <div class="slm-lock-content"><?php echo $content; ?></div>
    <div class="slm-lock-logo">
        &nbsp;
        <?php if (!empty(trim($infoBoxText))) : ?>
            <div class="slm-lock-logo-tooltip"><?php echo nl2br($infoBoxText); ?></div>
        <?php endif; ?>
    </div>
</div>