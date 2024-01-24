<?php

defined( 'ABSPATH' ) || exit;

class Hostinger_Amplitude_Actions {
	public const HOME_ENTER = 'wordpress.home.enter';
	public const LEARN_ENTER = 'wordpress.learn.enter';
	public const AI_ASSISTANT_ENTER = 'wordpress.ai_assistant.enter';
	public const AMAZON_AFFILIATE_ENTER = 'wordpress.amazon_affiliate.enter';
	public const WOO_ONBOARDING_STARTED = 'wp_admin.woocommerce_onboarding.start';
	public const WOO_STORE_SETUP_STORE = 'wp_admin.woocommerce_onboarding.setup_store';
	public const WOO_STORE_SETUP_COMPLETED = 'wp_admin.woocommerce_onboarding.completed';
}
