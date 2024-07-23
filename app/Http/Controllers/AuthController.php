<?php

namespace App\Http\Controllers;

use App\Models\User;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $google2fa = app('pragmarx.google2fa');
        $secretKey = $google2fa->generateSecretKey();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'two_factor_secret' => $secretKey,
        ]);

        return response()->json(['message' => 'User registered successfully']);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Simpan informasi pengguna sementara untuk verifikasi 2FA
        session(['2fa:user:id' => $user->id]);

        // Jika 2FA belum diaktifkan, generate QR code
        if (!$user->two_factor_enabled) {
            $google2fa = app('pragmarx.google2fa');
            $secretKey = $user->two_factor_secret;

            $qrCodeUrl = $google2fa->getQRCodeUrl(
                'YourAppName',
                $user->email,
                $secretKey
            );

            $qrCode = new QrCode($qrCodeUrl);
            $qrCode->setEncoding(new Encoding('UTF-8'))
                   ->setSize(300)
                   ->setMargin(10);

            $writer = new PngWriter();
            $result = $writer->write($qrCode);

            $qrCodeBase64 = base64_encode($result->getString());

            // Sudah Di generate
            $user->two_factor_enabled = true;
            $user->save();

            return response()->json([
                'qr_code' => 'data:image/png;base64,'.$qrCodeBase64,
                'message' => 'Please scan the QR code to activate 2FA.',
            ]);
        } else {
            // Jika 2FA sudah aktif, minta OTP untuk verifikasi
            return response()->json([
                'message' => 'Please enter your OTP to complete the authentication.',
            ]);
        }
    }

    public function twoFactorAuthenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'token' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid email'], 401);
        }

        $google2fa = app('pragmarx.google2fa');
        $secretKey = $user->two_factor_secret;

        if (!$google2fa->verifyKey($secretKey, $request->token)) {
            return response()->json(['message' => 'Invalid 2FA token'], 401);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json(['token' => $token]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
