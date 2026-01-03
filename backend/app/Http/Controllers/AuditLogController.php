<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditLog;


class AuditLogController extends Controller
{
    public function index(){
        $auditLogs = AuditLog::with('user')->get();
        return response()->json(  $auditLogs);
    }
}
