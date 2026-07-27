<?php

namespace App\Livewire\Admin\Organization;

use App\Domain\Organization\Models\OrganizationUnit;
use App\Support\Enums\OrganizationUnitType;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class UnitManager extends Component
{
    use WithPagination;

    public $name, $type, $code, $parent_id, $address, $phone, $email, $status = 'active';
    public $activeUnitId;
    public $showModal = false;
    public $noticeMessage = '';

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required',
            'code' => 'required|string|max:50',
            'parent_id' => 'nullable|exists:organization_units,id',
            'email' => 'nullable|email',
        ];
    }

    public function create()
    {
        $this->reset(['name', 'type', 'code', 'parent_id', 'address', 'phone', 'email', 'status', 'activeUnitId']);
        $this->showModal = true;
    }

    public function edit(int $id)
    {
        $unit = OrganizationUnit::findOrFail($id);
        $this->activeUnitId = $unit->id;
        $this->name = $unit->name;
        $this->type = $unit->type->value ?? $unit->type;
        $this->code = $unit->code;
        $this->parent_id = $unit->parent_id;
        $this->address = $unit->address;
        $this->phone = $unit->phone;
        $this->email = $unit->email;
        $this->status = $unit->status;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'type' => $this->type,
            'code' => $this->code,
            'parent_id' => $this->parent_id ?: null,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'status' => $this->status,
        ];

        if ($this->activeUnitId) {
            OrganizationUnit::find($this->activeUnitId)->update($data);
            $this->noticeMessage = 'Unit Organisasi diperbarui!';
        } else {
            OrganizationUnit::create($data);
            $this->noticeMessage = 'Unit Organisasi berhasil ditambahkan!';
        }

        $this->showModal = false;
    }

    public function render()
    {
        $units = OrganizationUnit::with(['parent'])->orderBy('type')->orderBy('name')->paginate(15);
        $parentOptions = OrganizationUnit::whereIn('type', ['pusat', 'wilayah'])->get();

        return view('livewire.admin.organization.unit-manager', [
            'units' => $units,
            'types' => OrganizationUnitType::cases(),
            'parentOptions' => $parentOptions,
        ]);
    }
}
