<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.front')]
class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;
    public string $errorMessage = '';

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            if (request()->hasSession()) {
                request()->session()->regenerate();
            }

            $user = Auth::user();

            // Redirect admin staff to executive dashboard, members to portal
            if ($user->hasAnyRole(['super-admin', 'administrator', 'sekretariat-nasional', 'bendahara-nasional', 'pengurus-wilayah', 'verifier-anggota', 'lsp-admin'])) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('portal.dashboard');
        }

        $this->errorMessage = 'Kredensial yang Anda masukkan tidak valid atau akun belum terdaftar.';
        $this->addError('email', $this->errorMessage);
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
