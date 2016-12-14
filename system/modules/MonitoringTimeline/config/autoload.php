<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @package MonitoringTimeline
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'Monitoring',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'Monitoring\MonitoringTimelineHookImpl' => 'system/modules/MonitoringTimeline/classes/MonitoringTimelineHookImpl.php',
));
