<?php
/**
 * Package in plugins
 * 
 * @package minify
 * @subpackage build
 */
$plugins = array();

/* create the plugin object */
$plugins[0] = $modx->newObject('modPlugin');
$plugins[0]->set('id',1);
$plugins[0]->set('name','minify');
$plugins[0]->set('description','Searches the document content for script and css tags and re-links them into a compbined and minified cache file.');
$plugins[0]->set('plugincode', getSnippetContent($sources['plugins'] . 'plugin.minify.php'));
$plugins[0]->set('category', 0);

$events = include $sources['events'].'events.minify.php';
if (is_array($events) && !empty($events)) {
    $plugins[0]->addMany($events);
    $modx->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($events).' Plugin Events for minify.'); flush();
} else {
    $modx->log(xPDO::LOG_LEVEL_ERROR,'Could not find plugin events for minify!');
}
unset($events);

return $plugins;