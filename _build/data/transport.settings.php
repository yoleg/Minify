<?php
/**
 * minify
 *
 * Copyright 2010 by Oleg Pryadko (websitezen.com)
 *
 * This file is part of minify, a css and js minifier for MODx Revolution based on http://modxcms.com/forums/index.php?topic=55983.0 for MODx Revolution.
 *
 * minify is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * minify is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * minify; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package minify
 */
/**
 * @package minify
 * @subpackage build
*/
$settings = array();
$settings['minify_js']= $modx->newObject('modSystemSetting');
$settings['minify_js']->fromArray(array(
    'key' => 'minify_js',
    'value' => '1',
    'xtype' => 'combo-boolean',
    'namespace' => 'minify',
    'area' => 'minify',
),'',true,true);

$settings = array();
$settings['minify_css']= $modx->newObject('modSystemSetting');
$settings['minify_css']->fromArray(array(
    'key' => 'minify_css',
    'value' => '1',
    'xtype' => 'combo-boolean',
    'namespace' => 'minify',
    'area' => 'minify',
),'',true,true);

$settings = array();
$settings['minify_async']= $modx->newObject('modSystemSetting');
$settings['minify_async']->fromArray(array(
    'key' => 'minify_async',
    'value' => '1',
    'xtype' => 'combo-boolean',
    'namespace' => 'minify',
    'area' => 'minify',
),'',true,true);

return $settings;
