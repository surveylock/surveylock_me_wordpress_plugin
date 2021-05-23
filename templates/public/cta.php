<?php $provider = isset($provider) ? $provider : 'content' ?>
<div id="slm-cta-container-<?php echo $provider; ?>" class="slm-cta-container">
    <div class="slm-cta-holder">
        <div class="slm-cta-text"><?php echo $text; ?></div>
	    <button type="button" data-slm-cta><?php echo $buttonText; ?></button>
    </div>
</div>
<style>
    button[data-slm-cta] {
        background-color: <?php echo $color; ?>;
        border-color: <?php echo $color; ?>;
    }
</style>