<?php

class MustacheTemplateRenderer implements TemplateRenderer {

	protected $renderer;
	protected $pageLoaders;

	public $frameTemplate = 'frame';

	function __construct($baseFolder, $partialFolder, $pageFolders) {

		$loader = new Mustache_Loader_FilesystemLoader($baseFolder);

		foreach ($pageFolders as $key => $value) {
			$this->pageLoaders[$key] = new FilesystemAliasLoader($value);
		}

		$partialLoader = new Mustache_Loader_CascadingLoader(array_merge(
			$this->pageLoaders,
			[new Mustache_Loader_FilesystemLoader($partialFolder)]
		));

		$this->renderer = new Mustache_Engine([
			'loader'			=>	$loader,
			'partials_loader'	=>	$partialLoader
		]);

	}

	function render($id, $data) {
		foreach ($this->pageLoaders as $key => $loader) {
			$loader->setTemplate($key, $id);
		}

		return $this->renderer->render($this->frameTemplate, $data);
	}

}
