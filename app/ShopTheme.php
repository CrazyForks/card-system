<?php
namespace App; use Illuminate\Database\Eloquent\Model; class ShopTheme extends Model { protected $guarded = array(); public $timestamps = false; protected $casts = array('options' => 'array', 'config' => 'array'); private static $default_theme; public static function defaultTheme() { if (!static::$default_theme) { static::$default_theme = ShopTheme::query()->where('name', \App\System::_get('shop_theme_default', 'Material'))->first(); if (!static::$default_theme) { static::$default_theme = ShopTheme::query()->firstOrFail(); } } return static::$default_theme; } public static function freshList() { $spdf5a27 = realpath(app_path('..' . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'shop_theme')); \App\ShopTheme::query()->get()->each(function ($spea194f) use($spdf5a27) { if (!file_exists($spdf5a27 . DIRECTORY_SEPARATOR . $spea194f->name . DIRECTORY_SEPARATOR . 'config.php')) { $spea194f->delete(); } }); foreach (scandir($spdf5a27) as $sp78ea1a) { if ($sp78ea1a === '.' || $sp78ea1a === '..') { continue; } try { @($spea194f = (include $spdf5a27 . DIRECTORY_SEPARATOR . $sp78ea1a . DIRECTORY_SEPARATOR . 'config.php')); } catch (\Exception $spcdd557) { continue; } $spea194f['config'] = array_map(function ($sp52b00a) { return $sp52b00a['value']; }, @$spea194f['options'] ?? array()); $sp4affe8 = \App\ShopTheme::query()->where('name', $sp78ea1a)->first(); if ($sp4affe8) { $sp4affe8->description = $spea194f['description']; $sp4affe8->options = @$spea194f['options'] ?? array(); $sp4affe8->config = ($sp4affe8->config ?? array()) + $spea194f['config']; $sp4affe8->saveOrFail(); } else { if ($spea194f && isset($spea194f['description'])) { \App\ShopTheme::query()->create(array('name' => $sp78ea1a, 'description' => $spea194f['description'], 'options' => @$spea194f['options'] ?? array(), 'config' => $spea194f['config'])); } } } } }