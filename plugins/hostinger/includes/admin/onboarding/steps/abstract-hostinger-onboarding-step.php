<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Hostinger_Onboarding_Step {
	abstract public function get_title(): string;

	abstract public function get_body(): array;

	abstract public function step_identifier(): string;

	abstract public function get_redirect_link(): string;

	public function completed(): bool {
		return in_array( $this->step_identifier(), array_column( $this->get_completed_steps(), 'action' ), true );
	}

	public function get_completed_steps(): array {
		return get_option( 'hostinger_onboarding_steps', array() );
	}
}
