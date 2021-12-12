<?php
namespace App\Http\Controllers\Admin; use App\Library\Response; use Illuminate\Http\Request; use App\Http\Controllers\Controller; class PayWay extends Controller { function get(Request $sp517903) { $sp542adc = (int) $sp517903->input('current_page', 1); $spf40cb2 = (int) $sp517903->input('per_page', 20); $sp30241a = \App\PayWay::orderBy('sort')->where('type', $sp517903->input('type')); $spee2f3d = $sp517903->input('search', false); $sp5d286a = $sp517903->input('val', false); if ($spee2f3d && $sp5d286a) { if ($spee2f3d == 'simple') { return Response::success($sp30241a->get(array('id', 'name'))); } elseif ($spee2f3d == 'id') { $sp30241a->where('id', $sp5d286a); } else { $sp30241a->where($spee2f3d, 'like', '%' . $sp5d286a . '%'); } } $spb38401 = $sp517903->input('enabled'); if (strlen($spb38401)) { $sp30241a->whereIn('enabled', explode(',', $spb38401)); } $sp38cdfb = $sp30241a->paginate($spf40cb2, array('*'), 'page', $sp542adc); return Response::success($sp38cdfb); } function edit(Request $sp517903) { $this->validate($sp517903, array('id' => 'required|integer', 'type' => 'required|integer|between:1,2', 'name' => 'required|string', 'sort' => 'required|integer', 'channels' => 'required|string', 'enabled' => 'required|integer|between:0,3')); $spd5afc6 = (int) $sp517903->post('id'); $sp418422 = \App\PayWay::find($spd5afc6); if (!$sp418422) { if (\App\PayWay::where('name', $sp517903->post('name'))->exists()) { return Response::fail('名称已经存在'); } $sp418422 = new \App\PayWay(); } else { if (\App\PayWay::where('name', $sp517903->post('name'))->where('id', '!=', $sp418422->id)->exists()) { return Response::fail('名称已经存在'); } } $sp418422->type = (int) $sp517903->post('type'); $sp418422->name = $sp517903->post('name'); $sp418422->sort = (int) $sp517903->post('sort'); $sp418422->img = $sp517903->post('img'); $sp418422->channels = @json_decode($sp517903->post('channels')) ?? array(); $sp418422->comment = $sp517903->post('comment'); $sp418422->enabled = (int) $sp517903->post('enabled'); $sp418422->saveOrFail(); return Response::success(); } function enable(Request $sp517903) { $this->validate($sp517903, array('ids' => 'required|string', 'enabled' => 'required|integer|between:0,3')); $sp315ad7 = $sp517903->post('ids'); $spb38401 = (int) $sp517903->post('enabled'); \App\PayWay::whereIn('id', explode(',', $sp315ad7))->update(array('enabled' => $spb38401)); return Response::success(); } function sort(Request $sp517903) { $this->validate($sp517903, array('id' => 'required|integer')); $spd5afc6 = (int) $sp517903->post('id'); $sp418422 = \App\PayWay::findOrFail($spd5afc6); $sp418422->sort = (int) $sp517903->post('sort'); $sp418422->save(); return Response::success(); } function delete(Request $sp517903) { $this->validate($sp517903, array('ids' => 'required|string')); $sp315ad7 = $sp517903->post('ids'); \App\PayWay::whereIn('id', explode(',', $sp315ad7))->delete(); return Response::success(); } }