<?php
namespace App\Http\Controllers; use App\System; use Illuminate\Foundation\Bus\DispatchesJobs; use Illuminate\Routing\Controller as BaseController; use Illuminate\Foundation\Validation\ValidatesRequests; use Illuminate\Foundation\Auth\Access\AuthorizesRequests; use Illuminate\Http\Request; class Controller extends BaseController { use AuthorizesRequests, DispatchesJobs, ValidatesRequests; function authQuery(Request $sp7fb11a, $spfa1eb5, $sp7fcd33 = 'user_id', $spa8a849 = 'user_id') { return $spfa1eb5::where($sp7fcd33, \Auth::id()); } protected function getUserId(Request $sp7fb11a, $spa8a849 = 'user_id') { return \Auth::id(); } protected function getUserIdOrFail(Request $sp7fb11a, $spa8a849 = 'user_id') { $sp95525e = self::getUserId($sp7fb11a, $spa8a849); if ($sp95525e) { return $sp95525e; } else { throw new \Exception('参数缺少 ' . $spa8a849); } } protected function getUser(Request $sp7fb11a) { return \Auth::getUser(); } protected function checkIsInMaintain() { if ((int) System::_get('maintain') === 1) { $spb3e303 = System::_get('maintain_info'); echo view('message', array('title' => '维护中', 'message' => $spb3e303)); die; } } protected function msg($sp650be2, $sp0f26f3 = null, $sp6231b9 = null) { return view('message', array('message' => $sp650be2, 'title' => $sp0f26f3, 'exception' => $sp6231b9)); } }