<?php
namespace App\Policies; use App\User; use Illuminate\Auth\Access\HandlesAuthorization; class UserPolicy { use HandlesAuthorization; public function __construct() { } public function admin($sp85e034) { } public function merchant($sp85e034) { } public function before($sp85e034, $spf90af6) { return true; } }