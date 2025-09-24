<?php
namespace App\Helpers;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class LogHelper
{
    public static function log($action, $module, $oldData = null, $newData = null)
    {
        Log::create([
            'user' => Auth::check() ? Auth::user()->name : 'System',
            'action' => strtoupper($action),
            'module' => $module,
            'old_data' => $oldData ? json_encode($oldData) : null,
            'new_data' => $newData ? json_encode($newData) : null,
            'ip_address' => request()->ip(),
        ]);
    }
}
