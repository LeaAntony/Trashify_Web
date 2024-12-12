<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Warga;

class AutentikasiMobileController extends Controller
{
    public function daftar(Request $request)
    {
        try {
            // Validasi input
            $validateUser  = $request->validate([
                'Nama' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:user',
                'password' => 'required|string|min:8',
            ]);
            
            // Menambahkan role dan meng-hash password
            $validateUser ['role'] = 'Warga';
            $validateUser ['password'] = bcrypt($validateUser ['password']);

            // Membuat pengguna baru
            $user = User::create($validateUser);

            // Membuat entri Warga
            $warga = Warga::create([
                'ID_User' => $user->ID_User,
            ]);

            // Membuat token untuk pengguna
            $token = $user->createToken('token')->plainTextToken;

            if ($user && $warga) {
                return response()->json([
                    'message' => 'Berhasil mendaftarkan Akun',
                    'token' => $token,
                ], 201);
            } else {
                return response()->json(['message' => 'Gagal mendaftarkan Akun!'], 500);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($e->validator->errors()->has('email')) {
                return response()->json(['message' => 'Email sudah digunakan!'], 409);
            }
            return response()->json(['message' => 'Validasi gagal!'], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan, silakan coba lagi!'], 500);
        }
    }
    
    public function masuk(Request $request)
    {
        $validateUser  = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        $user = User::where('email', $validateUser ['email'])->first();

        if (!$user) {
            return response()->json(['message' => 'Email belum terdaftar'], 404);
        }

        $token = $user->createToken('token')->plainTextToken;

        if (Auth::attempt($validateUser)) { 
            $user = Auth::user();
            if ($user->role === 'Warga') {
                return response()->json([
                    'message' => 'Berhasil Masuk',
                    'ID_User' => $user->ID_User,
                    'Nama' => $user->Nama,
                    'token' => $token,
                ], 200);
            }
        } else {
            return response()->json(['message' => 'Email atau Kata Sandi salah'], 401);
        }
    }

    // public function sendVerificationEmail(Request $request)
    // {
    //     $user = User::where('email', $request->email)->first();
        
    //     if ($user) {
    //         $verificationCode = rand(100000, 999999); // Generate a random verification code
    //         $user->verification_code = $verificationCode;
    //         $user->save();

    //         // Send email with verification code
    //         Mail::to($user->email)->send(new \App\Mail\VerificationMail($verificationCode));

    //         return response()->json(['message' => 'Verification code sent to your email.'], 200);
    //     }

    //     return response()->json(['message' => 'User  not found.'], 404);
    // }

    // public function verify(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'verification_code' => 'required|integer',
    //     ]);

    //     $user = User::where('email', $request->email)->first();

    //     if ($user && $user->verification_code == $request->verification_code) {
    //         $user->is_verified = true; // Set user as verified
    //         $user->verification_code = null; // Clear the verification code
    //         $user->save();

    //         return response()->json(['message' => 'Email verified successfully.'], 200);
    //     }

    //     return response()->json(['message' => 'Invalid verification code.'], 400);
    // }
}
