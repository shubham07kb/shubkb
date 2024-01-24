<div class="hts-data-container">
	<div class="hts-content-data">
		<div class="hts-data-type hts-post-type"></div>
		<div class="hts-data-type hts-tone-type"></div>
		<div class="hts-data-type hts-content-length"></div>
	</div>
	<div class="hts-metadata-wrapper">
    <div class="description">
        <h3><?= esc_html__('Keywords','hostinger-ai-assistant');?></h3>
	    <div id="hts-keywords-tip" data-tippy-content="<?= esc_html__('SEO keywords improve web content visibility on search engines like Google by being relevant and naturally placed', 'hostinger-ai-assistant')?>">
		    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
			    <path fill-rule="evenodd" clip-rule="evenodd" d="M6.99992 0.333252C3.31992 0.333252 0.333252 3.31992 0.333252 6.99992C0.333252 10.6799 3.31992 13.6666 6.99992 13.6666C10.6799 13.6666 13.6666 10.6799 13.6666 6.99992C13.6666 3.31992 10.6799 0.333252 6.99992 0.333252ZM7.66658 10.3333H6.33325V6.33325H7.66658V10.3333ZM7.66658 4.99992H6.33325V3.66658H7.66658V4.99992Z" fill="#727586"/>
		    </svg>
	    </div>
    </div>
    <div class="form-inputs">
        <div class="form-input">
            <div class="field">
                <div class="field-description">
	                <?= esc_html__('These keywords can help your article and website rank higher in search engines. You can input up to 5 keywords.','hostinger-ai-assistant');?>
                </div>
                <select id="hts-seo-keywords" multiple></select>
            </div>
        </div>
    </div>
	</div>
	<div class="hts-metadata-wrapper">
	<div class="description">
		<h3><?= esc_html__('Meta description','hostinger-ai-assistant');?></h3>
		<div id="hts-description-tip" data-tippy-content="<?= esc_html__('Meta descriptions offer concise page summaries in search results, aiding user understanding before clicking.', 'hostinger-ai-assistant')?>">
			<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path fill-rule="evenodd" clip-rule="evenodd" d="M6.99992 0.333252C3.31992 0.333252 0.333252 3.31992 0.333252 6.99992C0.333252 10.6799 3.31992 13.6666 6.99992 13.6666C10.6799 13.6666 13.6666 10.6799 13.6666 6.99992C13.6666 3.31992 10.6799 0.333252 6.99992 0.333252ZM7.66658 10.3333H6.33325V6.33325H7.66658V10.3333ZM7.66658 4.99992H6.33325V3.66658H7.66658V4.99992Z" fill="#727586"/>
			</svg>
		</div>
	</div>
    <div class="form-inputs">
        <div class="form-textarea">
            <div class="field">
                <label for="hts-seo-meta-description">
	                <?= esc_html__('Description','hostinger-ai-assistant');?>
                </label>
                <textarea id="hts-seo-meta-description"></textarea>
            </div>
        </div>
    </div>
	</div>
</div>
