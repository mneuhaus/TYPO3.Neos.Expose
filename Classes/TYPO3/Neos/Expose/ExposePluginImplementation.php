<?php
namespace TYPO3\Neos\Expose;

use TYPO3\Flow\Mvc\ActionRequest;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Expose".            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 */
class ExposePluginImplementation extends \TYPO3\Neos\TypoScript\PluginImplementation {
	/**
	 * @return string
	 */
	public function getPackage() {
		$controller = $this->node->getProperty('controller');
		if (stristr($controller, ':') !== FALSE) {
			$parts = explode(':', $controller);
			return $parts[0];
		}

		return $this->tsValue('package');
	}

	/**
	 * @return string
	 */
	public function getController() {
		$controller = $this->node->getProperty('controller');
		if (stristr($controller, ':') !== FALSE) {
			$parts = explode(':', $controller);
			return $parts[1];
		}
		return $controller;
	}

	/**
	 * Pass the arguments which were addressed to the plugin to its own request
	 *
	 * @param ActionRequest $pluginRequest The plugin request
	 * @return void
	 */
	protected function passArgumentsToPluginRequest(ActionRequest $pluginRequest) {
		parent::passArgumentsToPluginRequest($pluginRequest);

		$controller = $this->node->getProperty('controller');
		if (stristr($controller, ':') !== FALSE) {
			$parts = explode(':', $controller);
			$package = $parts[0];
			$controller = $parts[1];
			$pluginRequest->setArgument('package', $package);
		}
		if ($pluginRequest->getControllerName() === NULL) {
			$pluginRequest->setControllerName($this->getController());
		}
		$pluginRequest->setArgument('controller', $controller);
		$pluginRequest->setArgument('type', $this->node->getProperty('type'));
		$tsPrefix = $this->node->getProperty('tsPrefix');
		if (strlen($tsPrefix) > 0) {
			$pluginRequest->setArgument('__typoScriptPrefix', $tsPrefix);
		}
	}
}
