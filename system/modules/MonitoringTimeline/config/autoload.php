<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2018 Leo Feyer
 *
 * @license LGPL-3.0+
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
	'Monitoring\MonitoringTimeline'         => 'system/modules/MonitoringTimeline/classes/MonitoringTimeline.php',
	'Monitoring\MonitoringTimelineHookImpl' => 'system/modules/MonitoringTimeline/classes/MonitoringTimelineHookImpl.php',

	// Modules
	'Monitoring\ModuleTimeline'             => 'system/modules/MonitoringTimeline/modules/ModuleTimeline.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'monitoring_timeline' => 'system/modules/MonitoringTimeline/templates',
));
