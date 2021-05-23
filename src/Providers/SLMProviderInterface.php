<?php

namespace TenPixls\SurveyLockMe\Providers;

interface SLMProviderInterface {

	/**
	 * @return bool
	 */
	public function isEnabled();

	/**
	 * @return void
	 */
	public function init();
}