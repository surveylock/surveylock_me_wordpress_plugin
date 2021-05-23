<?php $provider = isset($provider) ? $provider : 'content' ?>
<div id="slm-loader-container-<?php echo $provider; ?>" class="slm-loader-container">
    <div class="slm-loader-holder">
        <div class="slm-loader-text"><?php echo $text; ?></div>
        <?php srvlm_render_partial("loaders/{$loader}", 'common', compact('color')); ?>
    </div>
</div>