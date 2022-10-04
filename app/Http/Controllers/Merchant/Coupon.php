<?php
namespace App\Http\Controllers\Merchant; use App\Library\Response; use Carbon\Carbon; use Illuminate\Http\Request; use App\Http\Controllers\Controller; class Coupon extends Controller { function get(Request $spdf16c9) { $spc64cdd = $this->authQuery($spdf16c9, \App\Coupon::class)->with(array('category' => function ($spc64cdd) { $spc64cdd->select(array('id', 'name')); }))->with(array('product' => function ($spc64cdd) { $spc64cdd->select(array('id', 'name')); })); $spdb3063 = $spdf16c9->input('search', false); $sp085db7 = $spdf16c9->input('val', false); if ($spdb3063 && $sp085db7) { if ($spdb3063 == 'id') { $spc64cdd->where('id', $sp085db7); } else { $spc64cdd->where($spdb3063, 'like', '%' . $sp085db7 . '%'); } } $spf26f7e = (int) $spdf16c9->input('category_id'); $spfb3e15 = $spdf16c9->input('product_id', -1); if ($spf26f7e > 0) { if ($spfb3e15 > 0) { $spc64cdd->where('product_id', $spfb3e15); } else { $spc64cdd->where('category_id', $spf26f7e); } } $sp24b3a3 = $spdf16c9->input('status'); if (strlen($sp24b3a3)) { $spc64cdd->whereIn('status', explode(',', $sp24b3a3)); } $sp915043 = $spdf16c9->input('type'); if (strlen($sp915043)) { $spc64cdd->whereIn('type', explode(',', $sp915043)); } $spc64cdd->orderByRaw('expire_at DESC,category_id,product_id,type,status'); $sp7ec90f = (int) $spdf16c9->input('current_page', 1); $spc4a487 = (int) $spdf16c9->input('per_page', 20); $sp61ff14 = $spc64cdd->paginate($spc4a487, array('*'), 'page', $sp7ec90f); return Response::success($sp61ff14); } function create(Request $spdf16c9) { $sp051e12 = $spdf16c9->post('count', 0); $sp915043 = (int) $spdf16c9->post('type', \App\Coupon::TYPE_ONETIME); $sp81c4e0 = $spdf16c9->post('expire_at'); $sp09f9c3 = (int) $spdf16c9->post('discount_val'); $sp61ad79 = (int) $spdf16c9->post('discount_type', \App\Coupon::DISCOUNT_TYPE_AMOUNT); $sp1c2837 = $spdf16c9->post('remark'); if ($sp61ad79 === \App\Coupon::DISCOUNT_TYPE_AMOUNT) { if ($sp09f9c3 < 1 || $sp09f9c3 > 1000000000) { return Response::fail('优惠券面额需要在0.01-10000000之间'); } } if ($sp61ad79 === \App\Coupon::DISCOUNT_TYPE_PERCENT) { if ($sp09f9c3 < 1 || $sp09f9c3 > 100) { return Response::fail('优惠券面额需要在1-100之间'); } } $spf26f7e = (int) $spdf16c9->post('category_id', -1); $spfb3e15 = (int) $spdf16c9->post('product_id', -1); if ($sp915043 === \App\Coupon::TYPE_REPEAT) { $spd85029 = $spdf16c9->post('coupon'); if (!$spd85029) { $spd85029 = strtoupper(str_random()); } $spb52873 = new \App\Coupon(); $spb52873->user_id = $this->getUserIdOrFail($spdf16c9); $spb52873->category_id = $spf26f7e; $spb52873->product_id = $spfb3e15; $spb52873->coupon = $spd85029; $spb52873->type = $sp915043; $spb52873->discount_val = $sp09f9c3; $spb52873->discount_type = $sp61ad79; $spb52873->count_all = (int) $spdf16c9->post('count_all', 1); if ($spb52873->count_all < 1 || $spb52873->count_all > 10000000) { return Response::fail('可用次数不能超过10000000'); } $spb52873->expire_at = $sp81c4e0; $spb52873->saveOrFail(); return Response::success(array($spb52873->coupon)); } elseif ($sp915043 === \App\Coupon::TYPE_ONETIME) { if (!$sp051e12) { return Response::forbidden('请输入生成数量'); } if ($sp051e12 > 100) { return Response::forbidden('每次生成不能大于100张'); } $spd84bc1 = array(); $sp8a9dd8 = array(); $spf93fb1 = $this->getUserIdOrFail($spdf16c9); $sp3f26e3 = Carbon::now(); for ($spc25c52 = 0; $spc25c52 < $sp051e12; $spc25c52++) { $spb52873 = strtoupper(str_random()); $sp8a9dd8[] = $spb52873; $spd84bc1[] = array('user_id' => $spf93fb1, 'coupon' => $spb52873, 'category_id' => $spf26f7e, 'product_id' => $spfb3e15, 'type' => $sp915043, 'discount_val' => $sp09f9c3, 'discount_type' => $sp61ad79, 'status' => \App\Coupon::STATUS_NORMAL, 'remark' => $sp1c2837, 'created_at' => $sp3f26e3, 'expire_at' => $sp81c4e0); } \App\Coupon::insert($spd84bc1); return Response::success($sp8a9dd8); } else { return Response::forbidden('unknown type: ' . $sp915043); } } function edit(Request $spdf16c9) { $spaacfde = (int) $spdf16c9->post('id'); $spd85029 = $spdf16c9->post('coupon'); $spf26f7e = (int) $spdf16c9->post('category_id', -1); $spfb3e15 = (int) $spdf16c9->post('product_id', -1); $sp81c4e0 = $spdf16c9->post('expire_at', NULL); $sp24b3a3 = (int) $spdf16c9->post('status', \App\Coupon::STATUS_NORMAL); $sp915043 = (int) $spdf16c9->post('type', \App\Coupon::TYPE_ONETIME); $sp09f9c3 = (int) $spdf16c9->post('discount_val'); $sp61ad79 = (int) $spdf16c9->post('discount_type', \App\Coupon::DISCOUNT_TYPE_AMOUNT); if ($sp61ad79 === \App\Coupon::DISCOUNT_TYPE_AMOUNT) { if ($sp09f9c3 < 1 || $sp09f9c3 > 1000000000) { return Response::fail('优惠券面额需要在0.01-10000000之间'); } } if ($sp61ad79 === \App\Coupon::DISCOUNT_TYPE_PERCENT) { if ($sp09f9c3 < 1 || $sp09f9c3 > 100) { return Response::fail('优惠券面额需要在1-100之间'); } } $spb52873 = $this->authQuery($spdf16c9, \App\Coupon::class)->find($spaacfde); if ($spb52873) { $spb52873->coupon = $spd85029; $spb52873->category_id = $spf26f7e; $spb52873->product_id = $spfb3e15; $spb52873->status = $sp24b3a3; $spb52873->type = $sp915043; $spb52873->discount_val = $sp09f9c3; $spb52873->discount_type = $sp61ad79; if ($sp915043 === \App\Coupon::TYPE_REPEAT) { $spb52873->count_all = (int) $spdf16c9->post('count_all', 1); if ($spb52873->count_all < 1 || $spb52873->count_all > 10000000) { return Response::fail('可用次数不能超过10000000'); } } if ($sp81c4e0) { $spb52873->expire_at = $sp81c4e0; } $spb52873->saveOrFail(); } else { $sp5acc8c = explode('
', $spd85029); for ($spc25c52 = 0; $spc25c52 < count($sp5acc8c); $spc25c52++) { $sp9ed045 = str_replace('', '', trim($sp5acc8c[$spc25c52])); $spb52873 = new \App\Coupon(); $spb52873->coupon = $sp9ed045; $spb52873->category_id = $spf26f7e; $spb52873->product_id = $spfb3e15; $spb52873->status = $sp24b3a3; $spb52873->type = $sp915043; $spb52873->discount_val = $sp09f9c3; $spb52873->discount_type = $sp61ad79; $sp5acc8c[$spc25c52] = $spb52873; } \App\Product::find($spfb3e15)->coupons()->saveMany($sp5acc8c); } return Response::success(); } function enable(Request $spdf16c9) { $this->validate($spdf16c9, array('ids' => 'required|string', 'enabled' => 'required|integer|between:0,1')); $spb19a2c = $spdf16c9->post('ids'); $sp0bc006 = (int) $spdf16c9->post('enabled'); $this->authQuery($spdf16c9, \App\Coupon::class)->whereIn('id', explode(',', $spb19a2c))->update(array('enabled' => $sp0bc006)); return Response::success(); } function delete(Request $spdf16c9) { $this->validate($spdf16c9, array('ids' => 'required|string')); $spb19a2c = $spdf16c9->post('ids'); $this->authQuery($spdf16c9, \App\Coupon::class)->whereIn('id', explode(',', $spb19a2c))->delete(); return Response::success(); } }