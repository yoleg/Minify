<?php
/**
 * minifyFURL
 *
 * Copyright 2010 by Oleg Pryadko (websitezen.com)
 *
 * This file is part of minify, a css and js minifier for MODx Revolution based on http://modxcms.com/forums/index.php?topic=55983.0 for MODx
 * Revolution. This file is loosely based off of ArchivistFURL by Shaun McCormick.
 *
 * minify is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * minify is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * minify; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 */
/* Usage: Open up the /components/minify/functions.minify.php file to adjust settings (you can override the following settings: css_path, css_url, js_path, js_url)
* make sure each style and javascript link has class="min" following the URL. 
* Example: <link rel="stylesheet" href="mycss/screen.css" type="text/css" class="min" /> */
/* See readme file for more info */
$minCSS = $modx->getOption('minify_css',null,'1');
$minJS = $modx->getOption('minify_js',null,'1');
$minAsync = $modx->getOption('minify_async',null,'1');

$base_path = MODX_CORE_PATH.'/components/minify/';  
$f = $base_path.'functions.minify.php';
if (file_exists($f)) {include $f;} else {$modx->log(modX::LOG_LEVEL_ERROR,'Minify not found at: '.$f);}

$output = $modx->documentOutput;

if ($minCSS) {
  $CSS = new Optimized('css',$output,$info);
  $output = $CSS->getOutput();
}
if ($minJS) {
  $JS = new Optimized('js',$output,$info);
  $output = $JS->getOutput();
}
if ($minAsync) {
  $Async = new Optimized('async',$output,$info);
  $output = $Async->getOutput();
}

$modx->documentOutput = $output;