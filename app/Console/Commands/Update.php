<?php
namespace App\Console\Commands; use App\Library\CurlRequest; use function DeepCopy\deep_copy; use Illuminate\Console\Command; use Illuminate\Support\Str; class Update extends Command { protected $signature = 'update {--proxy=} {--proxy-auth=}'; protected $description = 'check update'; public function __construct() { parent::__construct(); } private function download_progress($spe1037b, $spa23346) { $spf916f9 = fopen($spa23346, 'w+'); if (!$spf916f9) { return false; } $sp79ef57 = curl_init(); curl_setopt($sp79ef57, CURLOPT_URL, $spe1037b); curl_setopt($sp79ef57, CURLOPT_FOLLOWLOCATION, true); curl_setopt($sp79ef57, CURLOPT_RETURNTRANSFER, true); curl_setopt($sp79ef57, CURLOPT_FILE, $spf916f9); curl_setopt($sp79ef57, CURLOPT_PROGRESSFUNCTION, function ($spa6be92, $sp8dde0e, $sp1362f2, $sp0dd195, $sp2e96da) { if ($sp8dde0e > 0) { echo '    download: ' . sprintf('%.2f', $sp1362f2 / $sp8dde0e * 100) . '%'; } }); curl_setopt($sp79ef57, CURLOPT_NOPROGRESS, false); curl_setopt($sp79ef57, CURLOPT_HEADER, 0); curl_setopt($sp79ef57, CURLOPT_USERAGENT, 'card update'); if (defined('MY_PROXY')) { $sp5921d2 = MY_PROXY; $sp156dfe = CURLPROXY_HTTP; if (strpos($sp5921d2, 'http://') || strpos($sp5921d2, 'https://')) { $sp5921d2 = str_replace('http://', $sp5921d2, $sp5921d2); $sp5921d2 = str_replace('https://', $sp5921d2, $sp5921d2); $sp156dfe = CURLPROXY_HTTP; } elseif (strpos($sp5921d2, 'socks4://')) { $sp5921d2 = str_replace('socks4://', $sp5921d2, $sp5921d2); $sp156dfe = CURLPROXY_SOCKS4; } elseif (strpos($sp5921d2, 'socks4a://')) { $sp5921d2 = str_replace('socks4a://', $sp5921d2, $sp5921d2); $sp156dfe = CURLPROXY_SOCKS4A; } elseif (strpos($sp5921d2, 'socks5://')) { $sp5921d2 = str_replace('socks5://', $sp5921d2, $sp5921d2); $sp156dfe = CURLPROXY_SOCKS5_HOSTNAME; } curl_setopt($sp79ef57, CURLOPT_PROXY, $sp5921d2); curl_setopt($sp79ef57, CURLOPT_PROXYTYPE, $sp156dfe); if (defined('MY_PROXY_PASS')) { curl_setopt($sp79ef57, CURLOPT_PROXYUSERPWD, MY_PROXY_PASS); } } curl_exec($sp79ef57); curl_close($sp79ef57); echo '
'; return true; } public function handle() { set_time_limit(0); $sp5921d2 = $this->option('proxy'); if (!empty($sp5921d2)) { define('MY_PROXY', $sp5921d2); } $spee06b6 = $this->option('proxy-auth'); if (!empty($spee06b6)) { define('MY_PROXY_PASS', $spee06b6); } if (!empty(getenv('_'))) { $spa656f5 = '"' . getenv('_') . '" "' . $_SERVER['PHP_SELF'] . '" '; } else { if (!empty($_SERVER['_'])) { $spa656f5 = '"' . $_SERVER['_'] . '" "' . $_SERVER['PHP_SELF'] . '" '; } else { if (PHP_OS === 'WINNT') { $sp44bdc4 = dirname(php_ini_loaded_file()) . DIRECTORY_SEPARATOR . 'php.exe'; } else { $sp44bdc4 = dirname(php_ini_loaded_file()); if (ends_with($sp44bdc4, DIRECTORY_SEPARATOR . 'etc')) { $sp44bdc4 = substr($sp44bdc4, 0, -4); } $sp44bdc4 .= DIRECTORY_SEPARATOR . 'php'; } if (!file_exists($sp44bdc4)) { if (PHP_OS === 'WINNT') { $sp44bdc4 = 'php.exe'; } else { $sp44bdc4 = 'php'; } if ((bool) @exec($sp44bdc4 . ' --version') === FALSE) { echo '未找到php安装路径!
'; goto LABEL_EXIT; } } $spa656f5 = '"' . $sp44bdc4 . '" "' . $_SERVER['PHP_SELF'] . '" '; } } exec($spa656f5 . ' cache:clear'); exec($spa656f5 . ' config:clear'); echo '
'; $this->comment('检查更新中...'); $this->info('当前版本: ' . config('app.version')); $spfeee67 = @json_decode(CurlRequest::get('https://raw.githubusercontent.com/Tai7sy/card-system/master/.version'), true); if (!@$spfeee67['version']) { $this->warn('检查更新失败!'); $this->warn('Error: ' . ($spfeee67 ? json_encode($spfeee67) : 'Network error')); goto LABEL_EXIT; } $this->info('最新版本: ' . $spfeee67['version']); $this->info('版本说明: ' . (@$spfeee67['description'] ?? '无')); if (config('app.version') >= $spfeee67['version']) { $this->comment('您的版本已是最新!'); $spc38b6d = strtolower($this->ask('是否再次更新 (yes/no)', 'no')); if ($spc38b6d !== 'yes') { goto LABEL_EXIT; } } else { $spc38b6d = strtolower($this->ask('是否现在更新 (yes/no)', 'no')); if ($spc38b6d !== 'yes') { goto LABEL_EXIT; } } $sp9255f8 = realpath(sys_get_temp_dir()); if (strlen($sp9255f8) < 3) { $this->warn('获取临时目录失败!'); goto LABEL_EXIT; } $sp9255f8 .= DIRECTORY_SEPARATOR . Str::random(16); if (!mkdir($sp9255f8) || !is_writable($sp9255f8) || !is_readable($sp9255f8)) { $this->warn('临时目录不可读写!'); goto LABEL_EXIT; } if (!function_exists('exec')) { $this->warn('函数 exec 已被禁用, 无法继续更新!'); goto LABEL_EXIT; } if (PHP_OS === 'WINNT') { $sp36e05c = 'C:\\Program Files\\7-Zip\\7z.exe'; if (!is_file($sp36e05c)) { $sp36e05c = strtolower($this->ask('未找到7-Zip, 请手动输入7zG.exe路径', $sp36e05c)); } if (!is_file($sp36e05c)) { $this->warn('7-Zip不可用, 请安装7-Zip后重试'); goto LABEL_EXIT; } $sp36e05c = '"' . $sp36e05c . '"'; } else { exec('tar --version', $sp2cdfec, $sp84d1c5); if ($sp84d1c5) { $this->warn('Error: tar --version 
' . join('
', $sp2cdfec)); goto LABEL_EXIT; } } $this->comment('正在下载新版本...'); $spa23346 = $sp9255f8 . DIRECTORY_SEPARATOR . 'ka_update_' . Str::random(16) . '.tmp'; if (!$this->download_progress($spfeee67['url'], $spa23346)) { $this->warn('写入临时文件失败!'); goto LABEL_EXIT; } $sp2a6bdb = md5_file($spa23346); if ($sp2a6bdb !== $spfeee67['md5']) { $this->warn('更新文件md5校验失败!, file:' . $sp2a6bdb . ', require:' . $spfeee67['md5']); goto LABEL_EXIT; } $this->comment('正在解压...'); unset($sp2cdfec); if (PHP_OS === 'WINNT') { exec("{$sp36e05c} x -so {$spa23346} | {$sp36e05c} x -aoa -si -ttar -o{$sp9255f8}", $sp2cdfec, $sp84d1c5); } else { exec("tar -zxf {$spa23346} -C {$sp9255f8}", $sp2cdfec, $sp84d1c5); } if ($sp84d1c5) { $this->warn('Error: 解压失败 
' . join('
', $sp2cdfec)); goto LABEL_EXIT; } $this->comment('正在关闭主站...'); exec($spa656f5 . ' down'); sleep(5); $this->comment(' --> 正在清理旧文件...'); $sp491fdb = base_path(); foreach (array('app', 'bootstrap', 'config', 'public/dist', 'database', 'routes', 'vendor') as $sp987089) { \File::deleteDirectory($sp491fdb . DIRECTORY_SEPARATOR . $sp987089); } $this->comment(' --> 正在复制新文件...'); \File::delete($sp9255f8 . DIRECTORY_SEPARATOR . 'card_dist' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'logo.png'); \File::delete($sp9255f8 . DIRECTORY_SEPARATOR . 'card_dist' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . '.htaccess'); \File::delete($sp9255f8 . DIRECTORY_SEPARATOR . 'card_dist' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'web.config'); \File::delete($sp9255f8 . DIRECTORY_SEPARATOR . 'card_dist' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'nginx.conf'); \File::delete($sp9255f8 . DIRECTORY_SEPARATOR . 'card_dist' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'robots.txt'); \File::copyDirectory($sp9255f8 . DIRECTORY_SEPARATOR . 'card_system_free_dist', $sp491fdb); $this->comment(' --> 正在创建缓存...'); exec($spa656f5 . ' cache:clear'); exec($spa656f5 . ' route:cache'); exec($spa656f5 . ' config:cache'); $this->comment(' --> 正在更新数据库...'); exec($spa656f5 . ' migrate'); if (PHP_OS === 'WINNT') { echo '
'; $this->alert('请注意手动设置目录权限'); $this->comment('    storage 可读可写             '); $this->comment('    bootstrap/cache/ 可读可写    '); echo '

'; } else { $this->comment(' --> 正在设置目录权限...'); exec('rm -rf storage/framework/cache/data/*'); exec('chmod -R 777 storage/'); exec('chmod -R 777 bootstrap/cache/'); } $this->comment('正在启用主站...'); exec($spa656f5 . ' up'); exec($spa656f5 . ' queue:restart'); $spc7daf4 = true; LABEL_EXIT: if (isset($sp9255f8) && strlen($sp9255f8) > 19) { $this->comment('清理临时目录...'); \File::deleteDirectory($sp9255f8); } if (isset($spc7daf4) && $spc7daf4) { $this->info('更新成功!'); } if (PHP_OS === 'WINNT') { } else { exec('rm -rf storage/framework/cache/data/*'); exec('chmod -R 777 storage/'); exec('chmod -R 777 bootstrap/cache/'); } echo '
'; die; } }