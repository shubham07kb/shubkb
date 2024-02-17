<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$helper = new Hostinger_Helper();
/** @var Hostinger_Onboarding $hostinger_onboarding_steps */
$hostinger_onboarding_steps = $onboarding;
$hostinger_content          = $hostinger_onboarding_steps->get_content();
$hostinger_remaining_tasks  = 0;

/** @var Hostinger_Onboarding_Step $hostinger_step */
foreach ( $hostinger_onboarding_steps->get_steps() as $hostinger_step ) {
	$hostinger_remaining_tasks = ! $hostinger_step->completed() ? ++$hostinger_remaining_tasks : $hostinger_remaining_tasks;
}
$hostinger_videos = array(
	array(
		'id'       => 'WkbQr5dSGLs',
		'title'    => __( 'How to Add Your WordPress Website to Google Search Console', 'hostinger' ),
		'duration' => '4:24',
	),
	array(
		'id'       => 'PDGdAjmgN3Y',
		'title'    => __( 'How to Create a WordPress Contact Us Page', 'hostinger' ),
		'duration' => '2:48',
	),
	array(
		'id'       => '4NxiM_VXFuE',
		'title'    => __( 'How to Clear Cache in WordPress Website', 'hostinger' ),
		'duration' => '3:21',
	),
	array(
		'id'       => 'WHXtmEppbn8',
		'title'    => __( 'How to Edit the Footer in WordPress', 'hostinger' ),
		'duration' => '6:27',
	),
	array(
		'id'       => 'drC7cgDP3vU',
		'title'    => __( 'LiteSpeed Cache: How to Get 100% WordPress Optimization', 'hostinger' ),
		'duration' => '13:29',
	),
	array(
		'id'       => 'WdmfWV11VHU',
		'title'    => __( 'How to Back Up a WordPress Site', 'hostinger' ),
		'duration' => '8:26',
	),
	array(
		'id'       => 'YK-XO7iLyGQ',
		'title'    => __( 'How to Import Images Into WordPress Website', 'hostinger' ),
		'duration' => '1:44',
	),
	array(
		'id'       => 'suvkDYwTCfg',
		'title'    => __( 'How to Set Up WordPress SMTP', 'hostinger' ),
		'duration' => '2:30',
	),
);

$hostinger_additional_tabs = apply_filters( 'hostinger_plugin_additional_tabs', array() );

?>
<div class="hsr-overlay"></div>
<div class="hsr-onboarding-navbar">
	<div class="hsr-mobile-sidebar">
		<div class="hsr-close">
			<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M6.99961 8.04961L1.74961 13.2996C1.59961 13.4496 1.42461 13.5246 1.22461 13.5246C1.02461 13.5246 0.849609 13.4496 0.699609 13.2996C0.549609 13.1496 0.474609 12.9746 0.474609 12.7746C0.474609 12.5746 0.549609 12.3996 0.699609 12.2496L5.94961 6.99961L0.699609 1.74961C0.549609 1.59961 0.474609 1.42461 0.474609 1.22461C0.474609 1.02461 0.549609 0.849609 0.699609 0.699609C0.849609 0.549609 1.02461 0.474609 1.22461 0.474609C1.42461 0.474609 1.59961 0.549609 1.74961 0.699609L6.99961 5.94961L12.2496 0.699609C12.3996 0.549609 12.5746 0.474609 12.7746 0.474609C12.9746 0.474609 13.1496 0.549609 13.2996 0.699609C13.4496 0.849609 13.5246 1.02461 13.5246 1.22461C13.5246 1.42461 13.4496 1.59961 13.2996 1.74961L8.04961 6.99961L13.2996 12.2496C13.4496 12.3996 13.5246 12.5746 13.5246 12.7746C13.5246 12.9746 13.4496 13.1496 13.2996 13.2996C13.1496 13.4496 12.9746 13.5246 12.7746 13.5246C12.5746 13.5246 12.3996 13.4496 12.2496 13.2996L6.99961 8.04961Z" fill="#673DE6"/>
			</svg>
		</div>
		<div class="hsr-wrapper"></div>
	</div>
	<div class="hsr-onboarding-navbar__wrapper">
		<div class="hsr-logo">
			<svg class="hsr-mobile-logo" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path fill-rule="evenodd" clip-rule="evenodd" d="M2.00019 11.2422V0.300192L7.52276 3.24494V8.17486L14.8335 8.17839L20.4457 11.2422H2.00019ZM16.3285 7.27269V0.299805L22 3.17128V10.5673L16.3285 7.27269ZM16.3285 20.6466V15.7592L8.96135 15.7541C8.96822 15.7866 3.25605 12.6413 3.25605 12.6413L22 12.7292V23.6711L16.3285 20.6466ZM2 20.6466L2.0002 13.4962L7.52276 16.7129V23.5179L2 20.6466Z" fill="#673DE6"/>
			</svg>
			<svg width="151" height="30" viewBox="0 0 151 30" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path fill-rule="evenodd" clip-rule="evenodd" d="M0.000249566 14.046V0.000497794L7.08916 3.78046V10.1086L16.4735 10.1132L23.6774 14.046H0.000249566ZM18.3925 8.95058V0L25.6725 3.6859V13.1797L18.3925 8.95058ZM18.3924 26.1177V19.8441L8.93577 19.8375C8.9446 19.8793 1.6123 15.8418 1.6123 15.8418L25.6725 15.9547V30L18.3924 26.1177ZM0 26.1177L0.000252212 16.9393L7.08916 21.0683V29.8033L0 26.1177Z" fill="#673DE6"/>
				<path d="M116.894 12.0326C117.477 11.3073 118.424 10.9447 119.734 10.9447C120.324 10.9447 120.868 11.0194 121.367 11.1692C121.866 11.3194 122.308 11.4959 122.693 11.6996L123.504 9.4519C123.408 9.39205 123.249 9.3078 123.026 9.19992C122.804 9.09179 122.521 8.98692 122.178 8.88532C121.836 8.78322 121.427 8.69344 120.952 8.61524C120.477 8.53753 119.945 8.49805 119.356 8.49805C118.49 8.49805 117.675 8.64239 116.912 8.93034C116.148 9.21803 115.484 9.64051 114.919 10.198C114.353 10.7553 113.909 11.4363 113.584 12.2393C113.26 13.0427 113.097 13.9659 113.097 15.0093C113.097 16.0404 113.244 16.9577 113.539 17.7605C113.834 18.5637 114.252 19.2414 114.792 19.7927C115.334 20.3444 115.986 20.7636 116.75 21.0518C117.513 21.3392 118.364 21.4833 119.302 21.4833C120.396 21.4833 121.316 21.4079 122.061 21.2583C122.807 21.1086 123.348 20.9681 123.685 20.836V14.6675H120.871V19.0015C120.703 19.0377 120.507 19.0616 120.285 19.0734C120.062 19.0852 119.794 19.0913 119.482 19.0913C118.905 19.0913 118.4 18.9925 117.967 18.7948C117.534 18.5969 117.174 18.318 116.885 17.9581C116.596 17.5983 116.38 17.1697 116.235 16.6726C116.091 16.1751 116.019 15.6201 116.019 15.0093C116.019 13.7499 116.311 12.7581 116.894 12.0326Z" fill="#673DE6"/>
				<path d="M72.1959 19.0104C71.9374 19.0768 71.6097 19.1094 71.213 19.1094C70.4196 19.1094 69.7489 19.0285 69.2021 18.8663C68.6546 18.7048 68.1945 18.5222 67.8223 18.318L67.0283 20.5479C67.1968 20.6442 67.4041 20.7458 67.6505 20.8539C67.8972 20.9618 68.192 21.0637 68.5345 21.1595C68.8773 21.2553 69.271 21.336 69.7159 21.4024C70.1608 21.4683 70.6599 21.5015 71.213 21.5015C72.8602 21.5015 74.0928 21.1806 74.9104 20.5388C75.7281 19.8976 76.1372 18.9953 76.1372 17.8322C76.1372 17.2332 76.0588 16.7239 75.9029 16.304C75.7463 15.8843 75.5087 15.5184 75.1904 15.2068C74.8716 14.8952 74.4716 14.6223 73.9909 14.3885C73.5097 14.1546 72.9445 13.9179 72.2955 13.6778C71.9825 13.5699 71.6968 13.4653 71.4387 13.3632C71.18 13.2616 70.9515 13.1474 70.753 13.0217C70.5547 12.8957 70.4011 12.7549 70.2932 12.5989C70.185 12.4435 70.1305 12.2514 70.1305 12.0233C70.1305 11.6401 70.2781 11.3551 70.5727 11.1693C70.8672 10.9837 71.3512 10.8902 72.0249 10.8902C72.6259 10.8902 73.1399 10.9596 73.5669 11.0971C73.9934 11.2352 74.3813 11.3941 74.7304 11.5739L75.5417 9.36193C75.145 9.14617 74.6398 8.948 74.0269 8.76819C73.4136 8.58864 72.6859 8.49811 71.8443 8.49811C71.1346 8.49811 70.4975 8.58562 69.9323 8.75914C69.3671 8.93342 68.8864 9.18162 68.4896 9.50553C68.0927 9.82968 67.7862 10.222 67.5696 10.6837C67.3532 11.1452 67.245 11.6637 67.245 12.2393C67.245 12.815 67.3471 13.3033 67.5517 13.7047C67.756 14.1068 68.0208 14.4486 68.3451 14.7302C68.6697 15.0121 69.0364 15.246 69.4453 15.4316C69.8541 15.6172 70.2629 15.7824 70.672 15.9257C71.6097 16.2381 72.271 16.5285 72.6559 16.7986C73.0405 17.068 73.2332 17.4072 73.2332 17.8146C73.2332 18.0065 73.2032 18.1805 73.1429 18.3362C73.0827 18.4921 72.9742 18.6269 72.8181 18.7403C72.662 18.855 72.4544 18.9447 72.1959 19.0104Z" fill="#673DE6"/>
				<path fill-rule="evenodd" clip-rule="evenodd" d="M64.1375 15.0092C64.1375 16.0759 63.9779 17.0145 63.6598 17.8232C63.3408 18.6327 62.9047 19.3102 62.3519 19.8554C61.7988 20.4013 61.1402 20.8118 60.3768 21.0874C59.6134 21.3635 58.7924 21.5013 57.915 21.5013C57.061 21.5013 56.2557 21.3635 55.498 21.0874C54.7404 20.8118 54.0788 20.4013 53.5139 19.8554C52.9484 19.3102 52.504 18.6327 52.1792 17.8232C51.8546 17.0145 51.6919 16.0759 51.6919 15.0092C51.6919 13.9419 51.8604 13.0036 52.1971 12.1946C52.5335 11.3851 52.9878 10.7046 53.559 10.1531C54.1298 9.60165 54.7913 9.18822 55.5432 8.91235C56.2943 8.63623 57.0852 8.49817 57.915 8.49817C58.7684 8.49817 59.574 8.63623 60.3319 8.91235C61.0893 9.18822 61.7506 9.60165 62.3158 10.1531C62.8808 10.7046 63.3259 11.3851 63.6505 12.1946C63.9754 13.0036 64.1375 13.9419 64.1375 15.0092ZM54.5954 15.0092C54.5954 15.6203 54.6704 16.1718 54.8207 16.6634C54.971 17.1553 55.1876 17.5778 55.4704 17.9313C55.7523 18.2854 56.0984 18.5578 56.5075 18.7494C56.916 18.9418 57.3849 19.0378 57.9143 19.0378C58.4311 19.0378 58.8972 18.9418 59.3123 18.7494C59.7267 18.5578 60.0758 18.2854 60.358 17.9313C60.6405 17.5778 60.8574 17.1553 61.0077 16.6634C61.158 16.1718 61.2331 15.6203 61.2331 15.0092C61.2331 14.3973 61.158 13.8428 61.0077 13.3452C60.8574 12.8477 60.6405 12.4225 60.358 12.0684C60.0758 11.7148 59.7267 11.4417 59.3123 11.2503C58.8972 11.0585 58.4311 10.9627 57.9143 10.9627C57.3849 10.9627 56.916 11.0615 56.5075 11.2591C56.0984 11.4571 55.7523 11.7329 55.4704 12.0868C55.1876 12.4403 54.971 12.8658 54.8207 13.3633C54.6704 13.8612 54.5954 14.4097 54.5954 15.0092Z" fill="#673DE6"/>
				<path d="M45.212 8.78644H48.0259V21.2494H45.212V15.9621H40.4863V21.2494H37.6724V8.78644H40.4863V13.5519H45.212V8.78644Z" fill="#673DE6"/>
				<path d="M88.5332 8.78656V11.1786H84.7634V21.2495H81.9497V11.1786H78.1797V8.78656H88.5332Z" fill="#673DE6"/>
				<path d="M94.4721 21.2498H91.6582V8.78656H94.4721V21.2498Z" fill="#673DE6"/>
				<path d="M107.068 21.2495C106.262 19.8229 105.391 18.4144 104.453 17.0232C103.514 15.6323 102.516 14.3196 101.459 13.0846V21.2495H98.6807V8.78656H100.971C101.368 9.18188 101.807 9.66749 102.288 10.2431C102.769 10.8187 103.259 11.4334 103.758 12.0867C104.257 12.74 104.753 13.4175 105.246 14.1186C105.739 14.82 106.202 15.4942 106.635 16.1418V8.78656H109.431V21.2495H107.068Z" fill="#673DE6"/>
				<path d="M127.622 21.2495V8.78656H136.063V11.1424H130.436V13.588H135.432V15.8903H130.436V18.8937H136.478V21.2495H127.622Z" fill="#673DE6"/>
				<path fill-rule="evenodd" clip-rule="evenodd" d="M147.846 9.64051C146.848 8.97485 145.411 8.6424 143.535 8.6424C143.018 8.6424 142.432 8.66629 141.777 8.71457C141.121 8.7621 140.487 8.84659 139.874 8.96579V21.2495H142.687V16.699H144.077C144.317 16.9872 144.559 17.3078 144.801 17.6619C145.042 18.0155 145.287 18.3897 145.534 18.7855C145.782 19.1813 146.023 19.5892 146.258 20.0087C146.493 20.4284 146.726 20.8418 146.955 21.2495H150.1C149.884 20.794 149.65 20.3296 149.398 19.8555C149.145 19.3822 148.883 18.9268 148.613 18.4892C148.342 18.0517 148.068 17.632 147.792 17.2301C147.516 16.8285 147.251 16.4719 146.998 16.1598C147.756 15.8485 148.336 15.4195 148.739 14.8743C149.142 14.3288 149.343 13.6179 149.343 12.743C149.343 11.34 148.844 10.3059 147.846 9.64051ZM143.147 11.0523C143.309 11.0405 143.492 11.0344 143.697 11.0344C144.598 11.0344 145.28 11.1635 145.743 11.4207C146.206 11.6792 146.438 12.1133 146.438 12.7249C146.438 13.3606 146.21 13.8105 145.752 14.0738C145.296 14.3379 144.538 14.4691 143.481 14.4691H142.687V11.0883C142.831 11.0767 142.985 11.0646 143.147 11.0523Z" fill="#673DE6"/>
			</svg>
		</div>
		<ul class="hsr-wrapper__list">
			<li class="hsr-list__item hts-home-tab" data-name="home"><?php echo esc_html__( 'Get started', 'hostinger' ); ?></li>
			<li class="hsr-list__item hts-learn-tab" data-name="learn"><?php echo esc_html__( 'Learn', 'hostinger' ); ?></li>
			<?php if ( has_action( 'hostinger_ai_assistant_tab_view' ) && current_user_can( 'edit_posts' ) ) : ?>
				<li class="hsr-list__item hts-ai-assistant-tab" data-name="ai-assistant">
					<svg width="22" height="23" viewBox="0 0 22 23" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M18.3 8.1251L17.225 5.6251L14.625 4.4751L17.225 3.3501L18.3 0.975098L19.375 3.3501L21.975 4.4751L19.375 5.6251L18.3 8.1251ZM18.3 23.0001L17.225 20.6001L14.625 19.4751L17.225 18.3501L18.3 15.8251L19.375 18.3501L21.975 19.4751L19.375 20.6001L18.3 23.0001ZM7.325 19.1501L5.025 14.2251L0 11.9751L5.025 9.7251L7.325 4.8251L9.65 9.7251L14.65 11.9751L9.65 14.2251L7.325 19.1501Z" fill="#673DE6"/>
					</svg>
					<?php echo esc_html__( 'AI Content Creator', 'hostinger' ); ?>
				</li>
			<?php endif; ?>
			<?php

			if ( ! empty( $hostinger_additional_tabs ) ) :
				foreach ( $hostinger_additional_tabs as $key => $value ) :
					$tab_key = sanitize_title( $key );
					?>
					<li class="hsr-list__item" data-name="<?php echo $tab_key; ?>">
						<?php echo $value; ?>
					</li>
				<?php
				endforeach;

			endif;

			?>
		</ul>
		<div class="hsr-go-to-hpanel">
			<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M11.6667 7.5C11.2064 7.5 10.8333 7.1269 10.8333 6.66667V3.33333C10.8333 2.8731 11.2064 2.5 11.6667 2.5H16.6667C17.1269 2.5 17.5 2.8731 17.5 3.33333V6.66667C17.5 7.1269 17.1269 7.5 16.6667 7.5H11.6667ZM3.33333 10.8333C2.8731 10.8333 2.5 10.4602 2.5 10V3.33333C2.5 2.8731 2.8731 2.5 3.33333 2.5H8.33333C8.79357 2.5 9.16667 2.8731 9.16667 3.33333V10C9.16667 10.4602 8.79357 10.8333 8.33333 10.8333H3.33333ZM11.6667 17.5C11.2064 17.5 10.8333 17.1269 10.8333 16.6667V10C10.8333 9.53976 11.2064 9.16667 11.6667 9.16667H16.6667C17.1269 9.16667 17.5 9.53976 17.5 10V16.6667C17.5 17.1269 17.1269 17.5 16.6667 17.5H11.6667ZM3.33333 17.5C2.8731 17.5 2.5 17.1269 2.5 16.6667V13.3333C2.5 12.8731 2.8731 12.5 3.33333 12.5H8.33333C8.79357 12.5 9.16667 12.8731 9.16667 13.3333V16.6667C9.16667 17.1269 8.79357 17.5 8.33333 17.5H3.33333ZM4.16667 9.16667H7.5V4.16667H4.16667V9.16667ZM12.5 15.8333H15.8333V10.8333H12.5V15.8333ZM12.5 5.83333H15.8333V4.16667H12.5V5.83333ZM4.16667 15.8333H7.5V14.1667H4.16667V15.8333Z" fill="#673DE6"/>
			</svg>
			<a href="<?= $helper->get_hpanel_domain_url() ?>" target="_blank" rel="noopener">
				<?php _e( 'Go to hPanel', 'hostinger' ); ?>
			</a>
		</div>
		<svg class="hsr-mobile-menu-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M3.75 18C3.5375 18 3.35938 17.9277 3.21563 17.7831C3.07187 17.6385 3 17.4594 3 17.2456C3 17.0319 3.07187 16.8542 3.21563 16.7125C3.35938 16.5708 3.5375 16.5 3.75 16.5H20.25C20.4625 16.5 20.6406 16.5723 20.7844 16.7169C20.9281 16.8615 21 17.0406 21 17.2544C21 17.4681 20.9281 17.6458 20.7844 17.7875C20.6406 17.9292 20.4625 18 20.25 18H3.75ZM3.75 12.75C3.5375 12.75 3.35938 12.6777 3.21563 12.5331C3.07187 12.3885 3 12.2094 3 11.9956C3 11.7819 3.07187 11.6042 3.21563 11.4625C3.35938 11.3208 3.5375 11.25 3.75 11.25H20.25C20.4625 11.25 20.6406 11.3223 20.7844 11.4669C20.9281 11.6115 21 11.7906 21 12.0044C21 12.2181 20.9281 12.3958 20.7844 12.5375C20.6406 12.6792 20.4625 12.75 20.25 12.75H3.75ZM3.75 7.5C3.5375 7.5 3.35938 7.42771 3.21563 7.28313C3.07187 7.13853 3 6.95936 3 6.74563C3 6.53188 3.07187 6.35417 3.21563 6.2125C3.35938 6.07083 3.5375 6 3.75 6H20.25C20.4625 6 20.6406 6.07229 20.7844 6.21687C20.9281 6.36147 21 6.54064 21 6.75437C21 6.96812 20.9281 7.14583 20.7844 7.2875C20.6406 7.42917 20.4625 7.5 20.25 7.5H3.75Z" fill="#36344D"/>
		</svg>
	</div>
</div>

<div class="hostinger hsr-onboarding hsr-tab-content" data-name="home">
	<h2 class="hsr-onboarding__title"><?php echo $hostinger_content['title']; ?></h2>
	<p class="hsr-onboarding__description"><?php echo $hostinger_content['description']; ?></p>
	<div data-remaining-tasks="<?php echo $hostinger_remaining_tasks; ?>" class="hsr-onboarding-steps">
		<?php
		$can_open_accordion = true;
		/** @var Hostinger_Onboarding_Step $hostinger_step */
		foreach ( $hostinger_onboarding_steps->get_steps() as $hostinger_step ) :
			?>
			<div class="hsr-onboarding-step <?php echo $hostinger_step->step_identifier(); ?>">
				<div class="hsr-onboarding-step--title">
					<?php $completed_class = $hostinger_step->completed() ? 'completed' : ''; ?>
					<span class="hsr-onboarding-step--status <?php echo $completed_class; ?>"></span>
					<h4><?php echo $hostinger_step->get_title(); ?></h4>
					<?php
					$class_name = '';
					if ( $can_open_accordion && ! $hostinger_step->completed() ) {
						$class_name         = 'open';
						$can_open_accordion = false;
					}
					?>
					<div class="hsr-onboarding-step--expand <?php echo $class_name; ?>"></div>
				</div>
				<div class="hsr-onboarding-step--content <?php echo $class_name; ?>">
					<?php foreach ( $hostinger_step->get_body() as $key => $item ) : ?>
						<?php $counter = $key + 1; ?>
						<div class="hsr-onboarding-step--body">
							<span class='hsr-onboarding-step--body__counter'><?php echo $counter; ?></span>
							<div class="hsr-onboarding-step--body__content">
								<div class="hsr-onboarding-step--body__title">
									<h4><?php echo $item['title']; ?></h4>
								</div>
								<p><?php echo $item['description']; ?></p>
							</div>
						</div>
					<?php endforeach; ?>
					<div class="hsr-onboarding-step--footer">
						<a data-step="<?php echo $hostinger_step->step_identifier(); ?>"
							class="hsr-btn hsr-secondary-btn hsr-got-it-btn"
							href="#"><?php echo esc_html__( 'Got it!', 'hostinger' ); ?></a>
						<a class="hsr-btn hsr-primary-btn"
							id="hst-<?php echo $hostinger_step->step_identifier(); ?>"
							rel="noopener noreferrer"
							href="<?php echo $hostinger_step->get_redirect_link(); ?>">
							<?php echo $hostinger_step->button_text(); ?>
						</a>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
		<?php
		$preview_btn = ! $hostinger_onboarding_steps->maintenance_mode_enabled() ? 'hsr-preview' : '';
		$completed   = $hostinger_remaining_tasks === 0 ? 'completed' : '';
		?>
		<a class="hsr-btn hsr-primary-btn hsr-no-bg-btn hsr-publish-btn <?php echo $completed; ?> <?php echo $preview_btn; ?>"
			href="<?php echo $hostinger_content['btn']['url']; ?>"><?php echo $hostinger_content['btn']['text']; ?></a>
		<a target="_blank" class="hsr-btn hsr-primary-btn hsr-no-bg-btn hsr-preview-btn <?php echo $preview_btn; ?>"
			href="<?php echo home_url(); ?>"><?php echo $hostinger_content['btn']['text']; ?></a>
	</div>
	<div class="hsr-modal hsr-publish-modal">
		<div class="hsr-publish-overlay"></div>
		<div class="hsr-publish-modal--body">
			<div class="hsr-circular">
				<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" clip-rule="evenodd"
							d="M48 24C48 37.2548 37.2548 48 24 48C10.7452 48 0 37.2548 0 24C0 10.7452 10.7452 0 24 0C37.2548 0 48 10.7452 48 24ZM45.3333 24C45.3333 35.7821 35.7821 45.3333 24 45.3333C12.2179 45.3333 2.66667 35.7821 2.66667 24C2.66667 12.2179 12.2179 2.66667 24 2.66667C35.7821 2.66667 45.3333 12.2179 45.3333 24Z"
							fill="#EBE4FF"/>
					<mask id="mask0_7023_11690" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0"
							width="48" height="48">
						<path fill-rule="evenodd" clip-rule="evenodd"
								d="M48 24C48 37.2548 37.2548 48 24 48C10.7452 48 0 37.2548 0 24C0 10.7452 10.7452 0 24 0C37.2548 0 48 10.7452 48 24ZM45.3333 24C45.3333 35.7821 35.7821 45.3333 24 45.3333C12.2179 45.3333 2.66667 35.7821 2.66667 24C2.66667 12.2179 12.2179 2.66667 24 2.66667C35.7821 2.66667 45.3333 12.2179 45.3333 24Z"
								fill="white"/>
					</mask>
					<g mask="url(#mask0_7023_11690)">
						<path d="M24 0H48V48H0.333333L0 24H24V0Z" fill="#673DE6"/>
					</g>
				</svg>
			</div>

			<div class="hsr-success-circular">
				<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd"
							clip-rule="evenodd"
							d="M48 24C48 37.2548 37.2548 48 24 48C10.7452 48 0 37.2548 0 24C0 10.7452 10.7452 0 24 0C37.2548 0 48 10.7452 48 24ZM45.3333 24C45.3333 35.7821 35.7821 45.3333 24 45.3333C12.2179 45.3333 2.66667 35.7821 2.66667 24C2.66667 12.2179 12.2179 2.66667 24 2.66667C35.7821 2.66667 45.3333 12.2179 45.3333 24Z"
							fill="#EBE4FF"/>
					<mask id="mask0_7023_11585" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0"
							width="48" height="48">
						<path fill-rule="evenodd" clip-rule="evenodd"
								d="M48 24C48 37.2548 37.2548 48 24 48C10.7452 48 0 37.2548 0 24C0 10.7452 10.7452 0 24 0C37.2548 0 48 10.7452 48 24ZM45.3333 24C45.3333 35.7821 35.7821 45.3333 24 45.3333C12.2179 45.3333 2.66667 35.7821 2.66667 24C2.66667 12.2179 12.2179 2.66667 24 2.66667C35.7821 2.66667 45.3333 12.2179 45.3333 24Z"
								fill="white"/>
					</mask>
					<g mask="url(#mask0_7023_11585)">
						<circle cx="24" cy="24" r="24" fill="#00B090"/>
					</g>
					<mask id="mask1_7023_11585" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="15" y="17"
							width="19" height="15">
						<path fill-rule="evenodd" clip-rule="evenodd"
								d="M33.4438 19.0002L20.9992 31.4448L15.0547 25.5002L16.9992 23.5557L20.9992 27.5557L31.4992 17.0557L33.4438 19.0002Z"
								fill="#00B090"/>
					</mask>
					<g mask="url(#mask1_7023_11585)">
						<path d="M17 22.5L14 25.5L21 32.5L34.5 19L31.5 16L21 26.5L17 22.5Z" fill="#00B090"/>
					</g>
				</svg>
			</div>

			<h3><?php echo esc_html__( 'Publishing website', 'hostinger' ); ?></h3>
			<p class="hsr-publish-modal--body__description"><?php echo esc_html__( 'This can take some time', 'hostinger' ); ?></p>
			<div class="hsr-publish-modal--footer">
				<a class="hsr-btn hsr-outline-btn hsr-close-btn" href="#"><?php echo esc_html__( 'Close', 'hostinger' ); ?></a>
			</div>
		</div>
	</div>
</div>

<div class="hsr-learn-more hsr-tab-content" data-name="learn" style="display: none;">
	<div class="hsr-learn-page-container">
		<div class="hsr-tutorial-wrapper">
			<div class="hsr-wrapper-header">
				<div class="hsr-header-title"><?php echo esc_html__( 'WordPress tutorials', 'hostinger' ); ?></div>
				<div class="hsr-header-youtube">
					<a href="https://www.youtube.com/@HostingerAcademy?sub_confirmation=1"
						class="hsr-hostinger-youtube-link" target="_blank" rel="noopener noreferrer">
						<img class="hsr-youtube-logo"
							src="<?php echo esc_url( HOSTINGER_ASSETS_URL . '/images/youtube-icon.svg' ); ?>"
							alt="youtube logo">
						<div class="hsr-youtube-title"><?php echo esc_html__( 'Hostinger Academy', 'hostinger' ); ?></div>
					</a>
				</div>
			</div>
			<div class="hsr-video-wrapper">
				<div class="hsr-video-content">
					<div class="hsr-main-video">
						<video
								id="hts-video-player"
								class="video-js vjs-big-play-centered vjs-default-skin"
								controls
								preload="auto"
								fluid="true"
								poster="//img.youtube.com/vi/WkbQr5dSGLs/maxresdefault.jpg"
								data-setup='{"techOrder": ["youtube"], "sources": [{
		"type": "video/youtube", "src":
		"https://www.youtube.com/watch?v=WkbQr5dSGLs"}] }'
						>
					</div>
					<div class="hsr-main-video-info">
						<div class="hsr-main-video-title">
							<?php echo esc_html__( 'How to Add Your WordPress Website to Google Search Console', 'hostinger' ); ?>
						</div>
					</div>
				</div>
				<div class="hsr-hsr-playlist-wrapper">
					<div class="hsr-playlist">
						<?php
						foreach ( $hostinger_videos as $item ) {
							?>
							<div class="hsr-playlist-item" id="hsr-playlist-item"
								data-title="<?php echo $item['title']; ?>" data-id="<?php echo $item['id']; ?>" data-video-src="https://www.youtube.com/watch?v=<?php echo $item['id']; ?>">
								<div class="hsr-playlist-item-arrow">
									<img class="hsr-arrow-icon"
										src="<?php echo esc_url( HOSTINGER_ASSETS_URL . '/images/play-icon.svg' ); ?>"
										alt="play arrow">
								</div>
								<div class="hsr-playlist-item-thumbnail">
									<img class="hsr-thumbnail-image"
										src="https://img.youtube.com/vi/<?php echo $item['id']; ?>/default.jpg"
										alt="video thumbnail">
								</div>
								<div class="hsr-playlist-item-info">
									<div class="hsr-playlist-item-title"><?php echo $item['title']; ?></div>
									<div class="hsr-playlist-item-time"><?php echo $item['duration']; ?></div>
								</div>
							</div>
							<?php
						}
						?>
					</div>
				</div>
			</div>
		</div>
		<div class="hsr-help-wrapper">
			<div class="hsr-help-card" id="card-knowledge">
				<div class="hsr-card-logo">
					<img class="hsr-logo-image"
						src="<?php echo esc_url( HOSTINGER_ASSETS_URL . '/images/knowledge-icon.svg' ); ?>"
						alt="knowledge image">
				</div>
				<div class="hsr-card-info">
					<div class="hsr-card-title"><?php echo esc_html__( 'Knowledge Base', 'hostinger' ); ?></div>
					<div class="hsr-card-description"><?php echo esc_html__( 'Find the answers you need in our Knowledge Base', 'hostinger' ); ?></div>
				</div>
			</div>
			<div class="hsr-help-card" id="card-help">
				<div class="hsr-card-logo">
					<img class="hsr-logo-image"
						src="<?php echo esc_url( HOSTINGER_ASSETS_URL . '/images/help-icon.svg' ); ?>"
						alt="help image">
				</div>
				<div class="hsr-card-info">
					<div class="hsr-card-title"><?php echo esc_html__( 'Help Center', 'hostinger' ); ?></div>
					<div class="hsr-card-description"><?php echo esc_html__( 'Get in touch with our live specialists', 'hostinger' ); ?></div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php if ( has_action( 'hostinger_ai_assistant_tab_view' ) && current_user_can( 'edit_posts' ) ) : ?>
	<!--AI ASSISTANT-->
	<div class="hostinger hsr-tab-content hsr-ai-assistant-tab" data-name="ai-assistant" style="display: none;">
		<?php do_action( 'hostinger_ai_assistant_tab_view' ); ?>
	</div>
<?php endif; ?>

<?php

if ( ! empty( $hostinger_additional_tabs ) ) :
	foreach ( $hostinger_additional_tabs as $key => $value ) :
		$tab_key = sanitize_title( $key );
		?>

		<div class="hostinger hsr-tab-content" data-name="<?php echo $tab_key; ?>" style="display: none;">
			<?php do_action( 'hostinger_plugin_additional_tab_content_' . sanitize_title( $key ) ); ?>
		</div>

	<?php
	endforeach;

endif;

?>

<?php
$promotional_banner_hidden = get_transient( 'hts_hide_promotional_banner_transient' );

if ( ! $promotional_banner_hidden ) {
	require_once HOSTINGER_ABSPATH . 'includes/admin/views/partials/hostinger-promotional-banner.php';
}
?>
