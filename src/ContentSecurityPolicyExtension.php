<?php

namespace Mrtnzlml;

/**
 * @see https://www.w3.org/TR/CSP/
 * @see http://content-security-policy.com/
 */
class ContentSecurityPolicyExtension extends \Nette\DI\CompilerExtension
{

	private $defaults = [
		'enabled' => TRUE,
		'report-only' => FALSE,
		'default-src' => "'self'",
		'script-src' => "* 'unsafe-inline' 'unsafe-eval'",
		'style-src' => "* 'unsafe-inline'",
		'img-src' => "'self' data:",
		'connect-src' => "'self'",
		'font-src' => '*',
		'object-src' => '*',
		'media-src' => '*',
		//report-uri (POST:csp_report)
		'child-src' => '*',
		'form-action' => "'self'",
		'frame-ancestors' => "'self'",
	];

	public function afterCompile(\Nette\PhpGenerator\ClassType $class)
	{
		$config = $this->getConfig($this->defaults);
		$initialize = $class->getMethod('initialize');

		if (!$config['enabled']) {
			return;
		}
		unset($config['enabled']);

		if ($config['report-only'] && !isset($config['report-uri'])) {
			throw new \LogicException("You have to setup 'report-uri' if you want to use 'report-only' mode.");
		}
		$reportOnly = $config['report-only'] ? '-Report-Only' : '';
		unset($config['report-only']);

		if (isset($config['report-uri'])) {
			$initialize->addBody('$_csp_report_url = $this->getByType(\'Nette\Http\Request\')->getUrl()->getBaseUrl();');
			$config['report-uri'] = '{$_csp_report_url}' . $config['report-uri'];
		}

		$policies = [];
		foreach ($config as $key => $value) {
			$policies[] = $key . ' ' . $value;
		}
		$csp = implode('; ', $policies) . ';';
		$initialize->addBody("header(\"Content-Security-Policy$reportOnly: $csp\");");
	}

}
