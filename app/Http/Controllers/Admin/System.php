<?php
namespace App\Http\Controllers\Admin; use App\Library\Helper; use App\Library\Response; use Illuminate\Http\Request; use App\Http\Controllers\Controller; use Illuminate\Support\Facades\DB; use Illuminate\Support\Facades\Mail; class System extends Controller { private function set(Request $spdf16c9, $spab2fa0) { foreach ($spab2fa0 as $spae6970) { if ($spdf16c9->has($spae6970)) { \App\System::_set($spae6970, $spdf16c9->post($spae6970)); } } } private function setMoney(Request $spdf16c9, $spab2fa0) { foreach ($spab2fa0 as $spae6970) { if ($spdf16c9->has($spae6970)) { \App\System::_set($spae6970, (int) round($spdf16c9->post($spae6970) * 100)); } } } private function setInt(Request $spdf16c9, $spab2fa0) { foreach ($spab2fa0 as $spae6970) { if ($spdf16c9->has($spae6970)) { \App\System::_set($spae6970, (int) $spdf16c9->post($spae6970)); } } } function setItem(Request $spdf16c9) { $spae6970 = $spdf16c9->post('name'); $sp4bfa1d = $spdf16c9->post('value'); if (!$spae6970 || !$sp4bfa1d) { return Response::forbidden(); } \App\System::_set($spae6970, $sp4bfa1d); return Response::success(); } function info(Request $spdf16c9) { $spcdf749 = array('app_name', 'app_title', 'app_url', 'app_url_api', 'keywords', 'description', 'shop_ann', 'shop_ann_pop', 'shop_qq', 'company', 'js_tj', 'js_kf'); $sp858bc4 = array('shop_inventory'); if ($spdf16c9->isMethod('GET')) { $sp7c2170 = array(); foreach ($spcdf749 as $spae6970) { $sp7c2170[$spae6970] = \App\System::_get($spae6970); } foreach ($sp858bc4 as $spae6970) { $sp7c2170[$spae6970] = (int) \App\System::_get($spae6970); } return Response::success($sp7c2170); } $spe1037b = array('app_url' => Helper::format_url($_POST['app_url']), 'app_url_api' => Helper::format_url($_POST['app_url_api'])); $spdf16c9->merge($spe1037b); $this->set($spdf16c9, $spcdf749); $this->setInt($spdf16c9, $sp858bc4); return Response::success(); } function theme(Request $spdf16c9) { if ($spdf16c9->isMethod('GET')) { \App\ShopTheme::freshList(); return Response::success(array('themes' => \App\ShopTheme::get(), 'default' => \App\ShopTheme::defaultTheme()->name)); } $spb1baf2 = \App\ShopTheme::whereName($spdf16c9->post('shop_theme'))->firstOrFail(); \App\System::_set('shop_theme_default', $spb1baf2->name); $spb1baf2->config = @json_decode($spdf16c9->post('theme_config')) ?? array(); $spb1baf2->saveOrFail(); return Response::success(); } function order(Request $spdf16c9) { $spab2fa0 = array('order_query_password_open', 'order_query_day', 'order_clean_unpay_open', 'order_clean_unpay_day'); if ($spdf16c9->isMethod('GET')) { $sp7c2170 = array(); foreach ($spab2fa0 as $spae6970) { $sp7c2170[$spae6970] = (int) \App\System::_get($spae6970); } return Response::success($sp7c2170); } $this->setInt($spdf16c9, $spab2fa0); return Response::success(); } function vcode(Request $spdf16c9) { $spcdf749 = array('vcode_driver', 'vcode_geetest_id', 'vcode_geetest_key'); $sp858bc4 = array('vcode_login_admin', 'vcode_shop_buy', 'vcode_shop_search'); if ($spdf16c9->isMethod('GET')) { $sp7c2170 = array(); foreach ($spcdf749 as $spae6970) { $sp7c2170[$spae6970] = \App\System::_get($spae6970); } foreach ($sp858bc4 as $spae6970) { $sp7c2170[$spae6970] = (int) \App\System::_get($spae6970); } return Response::success($sp7c2170); } $this->set($spdf16c9, $spcdf749); $this->setInt($spdf16c9, $sp858bc4); return Response::success(); } function email(Request $spdf16c9) { $spcdf749 = array('mail_driver', 'mail_smtp_host', 'mail_smtp_port', 'mail_smtp_username', 'mail_smtp_password', 'mail_smtp_from_address', 'mail_smtp_from_name', 'mail_smtp_encryption', 'sendcloud_user', 'sendcloud_key'); $sp858bc4 = array('mail_send_order', 'mail_send_order_use_contact'); if ($spdf16c9->isMethod('GET')) { $sp7c2170 = array(); foreach ($spcdf749 as $spae6970) { $sp7c2170[$spae6970] = \App\System::_get($spae6970); } foreach ($sp858bc4 as $spae6970) { $sp7c2170[$spae6970] = (int) \App\System::_get($spae6970); } return Response::success($sp7c2170); } $this->set($spdf16c9, $spcdf749); $this->setInt($spdf16c9, $sp858bc4); return Response::success(); } function sms(Request $spdf16c9) { $spcdf749 = array('sms_api_id', 'sms_api_key'); $sp858bc4 = array('sms_send_order', 'sms_price'); if ($spdf16c9->isMethod('GET')) { $sp7c2170 = array(); foreach ($spcdf749 as $spae6970) { $sp7c2170[$spae6970] = \App\System::_get($spae6970); } foreach ($sp858bc4 as $spae6970) { $sp7c2170[$spae6970] = (int) \App\System::_get($spae6970); } return Response::success($sp7c2170); } $this->set($spdf16c9, $spcdf749); $this->setInt($spdf16c9, $sp858bc4); return Response::success(); } function storage(Request $spdf16c9) { $spcdf749 = array('storage_driver', 'storage_s3_access_key', 'storage_s3_secret_key', 'storage_s3_region', 'storage_s3_bucket', 'storage_oss_access_key', 'storage_oss_secret_key', 'storage_oss_bucket', 'storage_oss_endpoint', 'storage_oss_cdn_domain', 'storage_qiniu_domains_default', 'storage_qiniu_domains_https', 'storage_qiniu_access_key', 'storage_qiniu_secret_key', 'storage_qiniu_bucket', 'storage_qiniu_notify_url'); $sp858bc4 = array('storage_oss_is_ssl', 'storage_oss_is_cname'); if ($spdf16c9->isMethod('GET')) { $sp7c2170 = array(); foreach ($spcdf749 as $spae6970) { $sp7c2170[$spae6970] = \App\System::_get($spae6970); } foreach ($sp858bc4 as $spae6970) { $sp7c2170[$spae6970] = (int) \App\System::_get($spae6970); } return Response::success($sp7c2170); } $this->set($spdf16c9, $spcdf749); $this->set($spdf16c9, $sp858bc4); return Response::success(); } function emailTest(Request $spdf16c9) { $this->validate($spdf16c9, array('to' => 'required')); $sp44a0f7 = $spdf16c9->post('to'); try { $sp8ee8d3 = Mail::to($sp44a0f7)->send(new \App\Mail\Test()); return Response::success($sp8ee8d3); } catch (\Throwable $sp54a0c6) { \App\Library\LogHelper::setLogFile('mail'); \Log::error('Mail Test Exception:' . $sp54a0c6->getMessage()); return Response::fail($sp54a0c6->getMessage(), $sp54a0c6); } } function orderClean(Request $spdf16c9) { $this->validate($spdf16c9, array('day' => 'required|integer|min:1')); $sp4f640c = (int) $spdf16c9->post('day'); \App\Order::where('status', \App\Order::STATUS_UNPAY)->where('created_at', '<', (new \Carbon\Carbon())->addDays(-$sp4f640c))->delete(); return Response::success(); } function deleteOrders(Request $spdf16c9) { $this->validate($spdf16c9, array('date' => 'required|date_format:Y-m-d')); $spe567bf = $spdf16c9->input('date'); \App\Order::where('created_at', '<', $spe567bf)->delete(); return Response::success(); } function deleteFundRecords(Request $spdf16c9) { $this->validate($spdf16c9, array('date' => 'required|date_format:Y-m-d')); $spe567bf = $spdf16c9->input('date'); \App\FundRecord::where('created_at', '<', $spe567bf)->delete(); \App\User::where('m_paid', '>', 0)->update(array('m_all' => DB::raw('m_all-m_paid'), 'm_paid' => 0)); return Response::success(); } function deleteLogs(Request $spdf16c9) { $this->validate($spdf16c9, array('date' => 'required|date_format:Y-m-d')); $spe567bf = $spdf16c9->input('date'); \App\Log::where('created_at', '<', $spe567bf)->delete(); return Response::success(); } }