<?php
/* minify for modx by Oleg (oleg@websitezen.com), based on http://modxcms.com/forums/index.php?topic=55983.0 */
class Optimized {
private $type, $properties, $find, $replace, $requirements, $output, $matches, $search, $cached, $file, $fp, $testing, $base;
    function __construct($filetype,$output,$info) {
		$this->type=$filetype;
		$this->find = array();
		if ($this->type == 'js') {
			$this->find = '!<script(.*)src="(.*\.js)"(.*)min(.*)></script>!i';
			$this->replace  = array('<script type="text/javascript" src="','" class="minified"></script>');
		} elseif ($this->type == 'async') {
			$this->find = '!L(.*)\("(.*\.js)"(.*)min(.*)\);!i';
			$this->replace  = array('L("','");');
			$this->type = 'js';
		} else {
			$this->find = '!<link(.*)href="(.*\.css)"(.*)min(.*)>!i';
			$this->replace = array('<link rel="stylesheet" type="text/css" href="','" media="screen,projection,print" class="minified" />');
			$this->type = 'css';
		}
		$this->output = $output;
		$this->matches = $this->getmatches($this->output);
		if (!empty($this->matches[0])) {
			$this->generateKey();
			$this->setproperties($info);
			$this->checkCache();
			$this->testing = '';
			if ($this->type == 'none') {
				$this->testing = ' <!-- '. print_r($this->matches,1) . ' --> ';
			}
			$this->output = $this->finalsearch($this->output);
		}
	}
	public function getOutput() {
		// return print_r($this->matches,1);
		return $this->output;
	}
	private function setproperties() {
		global $modx;
		$this->context = $modx->context->get('key');
		$this->base = '';
		if ($this->context == 'store') {
			$this->base = '../';
		}
		$this->properties['cachePageKey'.$this->type] = $this->context . '/elements/minify/'. md5($this->key);
		$this->properties['cacheFileName'.$this->type] = $this->context . '.' . md5($this->key) . '.' . $this->type;
		$this->properties['file_path_css'] = $modx->getOption('css_path',null,MODX_ASSETS_PATH . 'templates/custom/css/');
		$this->properties['file_url_css'] = $modx->getOption('css_url',null,MODX_ASSETS_URL . 'templates/custom/css/');
		$this->properties['file_path_js'] = $modx->getOption('js_path',null,MODX_ASSETS_PATH . 'js/');
		$this->properties['file_url_js'] = $modx->getOption('js_url',null,MODX_ASSETS_URL . 'js/');
		$this->properties[xPDO::OPT_CACHE_KEY] = $modx->getOption('cache_resource_key', $this->properties, 'default');
		$this->properties[xPDO::OPT_CACHE_HANDLER] = $modx->getOption('cache_resource_handler', $this->properties, 'xPDOFileCache');
		$this->properties[xPDO::OPT_CACHE_EXPIRES] = (integer) $modx->getOption(xPDO::OPT_CACHE_EXPIRES, $this->properties, 0);
		$this->properties['cacheOptions'.$this->type] = array(
			xPDO::OPT_CACHE_KEY => $this->properties[xPDO::OPT_CACHE_KEY],
			xPDO::OPT_CACHE_HANDLER => $this->properties[xPDO::OPT_CACHE_HANDLER],
			xPDO::OPT_CACHE_EXPIRES => $this->properties[xPDO::OPT_CACHE_EXPIRES],
		);
	}
	private function checkCache() {
		global $modx;
		$this->cached = false;
		if ($modx->getCacheManager()) {
			$this->cached = $modx->cacheManager->get($this->properties['cachePageKey'.$this->type], $this->properties['cacheOptions'.$this->type]);
		} else {
			$modx->log(modX::LOG_LEVEL_ERROR,'Minify cache cannot be found.');
			return false;
		}
		if (empty($this->cached) || !isset($this->cached['properties']) || !isset($this->cached['output'])) {
		  $file = '';
		  foreach($this->matches[2] as $script) {
			if (substr($script[0],0,4) != 'http') {
				$script[0] = $this->base . $script[0];
				$end = filesize($script[0]);
				$fp = fopen($script[0],'r');
				$file .= ' /* Start of '.$script[0] . ' */ ';
				$file .= fread($fp,$end);
				$file .= ' /* End of '.$script[0] . ' bytes: '. $end. ' */ ';
				fclose($fp);
			} else {
				$file .= ' /* Start of '.$script[0] . ' */ ';
				$file .= file_get_contents($script[0]);
				$file .= ' /* End of '.$script[0] . ' */ ';
			}
		  }
		  $file = $this->minify($file,$this->type);
		  if ($modx->getCacheManager()) {
			$this->cached = array('properties' => $this->properties, 'output' => $file);
			$modx->cacheManager->set($this->properties['cachePageKey'.$this->type], $this->cached, $this->properties[xPDO::OPT_CACHE_EXPIRES], $this->properties['cacheOptions'.$this->type]);
			$fp = fopen($this->properties['file_path_'.$this->type].$this->properties['cacheFileName'.$this->type],'w');
			fwrite($fp,$file);
			fclose($fp);
		  }
		}
	}

	private function getmatches($input) {
		preg_match_all($this->find,$input,$matches,PREG_OFFSET_CAPTURE);
		return $matches;
	}
	private function generateKey() {
		$k='';
		foreach($this->matches[2] as $k) {
			$this->key .= $k[0];
		}
	}
	private function minify($file) {
	  if ($this->type == 'css') {
	  $file = preg_replace( '#\s+#', ' ', $file );
	  $file = preg_replace( '#/\*.*?\*/#s', '', $file );
	  }
	  $file = str_replace( '; ', ';', $file );
	  $file = str_replace( ': ', ':', $file );
	  $file = str_replace( ' {', '{', $file );
	  $file = str_replace( '{ ', '{', $file );
	  $file = str_replace( ', ', ',', $file );
	  $file = str_replace( '} ', '}', $file );
	  $file = str_replace( ';}', '}', $file );
	 // $file = preg_replace('!url\((.*)\)!i','url('.MODX_BASE_URL.dirname($script[0]).'/\\1)',$file);
	  return trim($file);
	}
	private function finalsearch($output) {
		$output = preg_replace($this->find,'',$output);
		$output = substr($output,0,$this->matches[0][0][1]) . $this->replace[0] .$this->properties['file_url_'.$this->type].$this->properties['cacheFileName'.$this->type].$this->replace[1] . $this->testing . substr($output,$this->matches[0][0][1]);
		return $output;
	}

}

