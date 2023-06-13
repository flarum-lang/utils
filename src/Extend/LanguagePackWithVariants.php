<?php

/*
 * This file is part of flarum-lang/utils.
 *
 * Copyright (c) 2023 Robert Korulczyk <robert@korulczyk.pl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace FlarumLang\Utils\Extend;

use Flarum\Extend\Event;
use Flarum\Extend\ExtenderInterface;
use Flarum\Extend\LanguagePack;
use Flarum\Extend\LifecycleInterface;
use Flarum\Extension\Extension;
use Flarum\Frontend\Assets;
use Flarum\Frontend\Compiler\Source\SourceCollector;
use Flarum\Settings\Event\Saved;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Contracts\Container\Container;
use InvalidArgumentException;
use function file_get_contents;
use function json_encode;

/**
 * Class LanguagePackWithVariants.
 *
 * @author Robert Korulczyk <robert@korulczyk.pl>
 */
class LanguagePackWithVariants implements ExtenderInterface, LifecycleInterface {

	private $path;
	private $variants;
	private $defaultVariant;
	private $label;

	public function __construct(array $config, string $path = '/locale') {
		$this->path = $path;
		if (empty($config['variants']) || !is_array($config['variants']) || count($config['variants']) < 2) {
			throw new InvalidArgumentException('Invalid variants config.');
		}
		$this->variants = $config['variants'];
		$this->defaultVariant = $config['defaultVariant'] ?? reset($config['variants']);
		$this->label = $config['label'] ?? 'Variant';
	}

	public function extend(Container $container, Extension $extension = null) {
		if ($extension === null) {
			throw new InvalidArgumentException('I need an extension instance to register a language pack.');
		}

		/* @var $settings SettingsRepositoryInterface */
		$settings = resolve('flarum.settings');
		$configKey = $extension->getId() . '.variant';
		$variant = $settings->get($configKey, $this->defaultVariant);

		$this->registerAssets($container, $extension, [
			'setting' => $configKey,
			'type' => 'select',
			'label' => $this->label,
			'options' => $this->variants,
			'default' => $this->defaultVariant,
		]);
		(new LanguagePack("{$this->path}/$variant"))->extend($container, $extension);
		(new Event())
			->listen(Saved::class, function (Saved $event) use ($container, $configKey, $extension) {
				foreach ($event->settings as $setting => $value) {
					if ($setting === $configKey) {
						$container->make('flarum.locales')->clearCache();
						$locale = $extension->composerJsonAttribute('extra.flarum-locale.code');
						$container->make('flarum.assets.forum')->makeLocaleJs($locale)->flush();
						$container->make('flarum.assets.admin')->makeLocaleJs($locale)->flush();
					}
				}
			})
			->extend($container, $extension);
	}

	public function onEnable(Container $container, Extension $extension) {
		$container->make('flarum.locales')->clearCache();
	}

	public function onDisable(Container $container, Extension $extension) {
		$container->make('flarum.locales')->clearCache();
	}

	private function registerAssets(Container $container, Extension $extension, array $settings): void {
		$abstract = 'flarum.assets.admin';

		$container->resolving($abstract, function (Assets $assets) use ($extension, $settings) {
			$assets->js(function (SourceCollector $sources) use ($extension, $settings) {
				$sources->addString(function () use ($extension, $settings) {
					$js = file_get_contents(__DIR__ . '/../../js/dist/languagePackWithVariants.js');
					$js = strtr($js, [
						'PLACEHOLDER_INITIALIZER_ID' => json_encode($extension->getId() . '-languagePackWithVariants'),
						'PLACEHOLDER_EXTENSION_ID' => json_encode($extension->getId()),
						'PLACEHOLDER_SETTINGS' => json_encode($settings),
					]);
					return <<<JS
						var module={};
						$js
						flarum.extensions['{$extension->getId()}-languagePackWithVariants']=module.exports;
						JS;
				});
			});
		});
	}
}
