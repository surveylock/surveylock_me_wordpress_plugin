<?php

use TenPixls\SurveyLockMe\Providers\Content\SLMContentProvider;
use TenPixls\SurveyLockMe\Providers\Snax\SLMSnaxProvider;

function srvlm_get_providers() {
	return [
		SLMContentProvider::class,
		SLMSnaxProvider::class,
	];
}