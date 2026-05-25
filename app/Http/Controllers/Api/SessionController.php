<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\UserSession;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $currentTokenId = (string) $request->user()->currentAccessToken()->getKey();

        $sessions = UserSession::where('user_id', (string) $request->user()->_id)
            ->orderBy('last_active_at', 'desc')
            ->get()
            ->map(function ($s) use ($currentTokenId) {
                $arr = $s->toArray();
                $arr['is_current'] = $arr['token_id'] === $currentTokenId;
                return $arr;
            });

        return $this->ok($sessions, 'Daftar sesi berhasil diambil');
    }

    public function revoke(Request $request, string $id)
    {
        $currentTokenId = (string) $request->user()->currentAccessToken()->getKey();

        $session = UserSession::where('_id', $id)
            ->where('user_id', (string) $request->user()->_id)
            ->first();

        if (!$session) {
            return $this->fail('Sesi tidak ditemukan', [], 404);
        }

        if ((string) $session->token_id === $currentTokenId) {
            return $this->fail('Tidak bisa merevoke sesi yang sedang aktif. Gunakan logout.', [], 422);
        }

        // Delete the Sanctum token so the device can no longer authenticate
        $request->user()->tokens()->where('id', $session->token_id)->delete();

        $session->update([
            'is_active'     => false,
            'logged_out_at' => now(),
        ]);

        return $this->ok(null, 'Sesi berhasil direvoke');
    }

    public function revokeAll(Request $request)
    {
        $currentTokenId = (string) $request->user()->currentAccessToken()->getKey();
        $userId = (string) $request->user()->_id;

        // Delete all Sanctum tokens except current
        $request->user()->tokens()->where('id', '!=', $currentTokenId)->delete();

        UserSession::where('user_id', $userId)
            ->where('is_active', true)
            ->where('token_id', '!=', $currentTokenId)
            ->update([
                'is_active'     => false,
                'logged_out_at' => now(),
            ]);

        return $this->ok(null, 'Semua sesi lain berhasil direvoke');
    }
}
