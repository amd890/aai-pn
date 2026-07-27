<?php

namespace App\Livewire\Auth;

use App\Domain\Membership\Services\MembershipService;
use App\Domain\Organization\Models\Institution;
use App\Domain\Organization\Models\Region;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.front')]
class Register extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $nik = '';
    public string $nip = '';
    public string $phone = '';
    public int|null $region_id = null;
    public int|null $institution_id = null;
    public string $institution_name_custom = '';
    public string $jenjang_arsiparis = 'Ahli Muda';
    public string $golongan = 'III/c';

    public bool $registered = false;

    public function mount()
    {
        $defaultRegion = Region::first();
        if ($defaultRegion) {
            $this->region_id = $defaultRegion->id;
        }

        $defaultInstitution = Institution::first();
        if ($defaultInstitution) {
            $this->institution_id = $defaultInstitution->id;
        }
    }

    public function register(MembershipService $membershipService)
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'nik' => 'required|string|min:16|max:16|unique:members,nik',
            'phone' => 'nullable|string|max:20',
            'jenjang_arsiparis' => 'required|string',
            'region_id' => 'required|exists:regions,id',
        ]);

        $instId = $this->institution_id;
        if (empty($instId) && !empty($this->institution_name_custom)) {
            $inst = Institution::firstOrCreate(['name' => trim($this->institution_name_custom)]);
            $instId = $inst->id;
        }

        $membershipService->registerMember(
            [
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password,
            ],
            [
                'name' => $this->name,
                'nik' => $this->nik,
                'nip' => $this->nip,
                'phone' => $this->phone,
                'region_id' => $this->region_id,
                'institution_id' => $instId,
                'jenjang_arsiparis' => $this->jenjang_arsiparis,
                'golongan' => $this->golongan,
                'position' => 'Arsiparis ' . $this->jenjang_arsiparis,
            ]
        );

        $this->registered = true;
    }

    public function render()
    {
        $regions = Region::orderBy('name', 'asc')->get();
        $institutions = Institution::orderBy('name', 'asc')->get();

        return view('livewire.auth.register', compact('regions', 'institutions'));
    }
}
