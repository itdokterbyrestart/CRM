<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{
    User,
};
use Spatie\Permission\Models\{
    Role,
};
use Auth;
use Illuminate\Support\Facades\Hash;

class Form extends Component
{
    public $name, $email, $password, $password_confirmation, $mail_report = false, $blocked = false, $updated_at, $created_at, $modelId, $roles, $selected_roles = [];
    public $edit = 1;

    public function mount($model, $edit)
    {
        if (!Auth::user()->can('manage users')) {
            return abort(403);
        }
        $this->roles = Role::orderBy('name')->get();
        $this->edit = $edit;
        if ($model) {
            $this->modelId = $model->id;
            if ($edit === 0) {
                $this->updated_at= $model->updated_at->format('d-m-Y | H:i');
                $this->created_at = $model->created_at->format('d-m-Y | H:i');
            }
            $this->name = $model->name;
            $this->email = $model->email;
            $this->mail_report = ($model->mail_report == 1 ? true : false);
            $this->blocked = ($model->blocked == 1 ? true : false);
            foreach ($model->roles as $role) {
                $this->selected_roles[$role->id] = true;
            }
        }
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|min:2',
            'email' => 'required|email|max:255|unique:users,email,' . $this->modelId,
            'password' => 'nullable|string|min:8',
            'password_confirmation' => 'required_with:password|same:password',
            'mail_report' => 'required|boolean',
            'blocked' => 'required|boolean',
            'selected_roles' => 'array',
        ];
    }

    protected $validationAttributes = [
        'name' => 'naam',
        'email' => 'email',
        'password' => 'wachtwoord',
        'mail_report' => 'email rapportage',
        'blocked' => 'geblokkeerd',
        'selected_roles' => 'geselecteerde rollen',
        'roles' => 'rollen',
        'roles.*' => 'rol',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updatingSelectedRoles($value, $id)
    {
        if ($value === false) {
            unset($this->selected_roles[$id]);
        }
    } 

    public function store()
    {
        $this->validate();

        $selected_roles_array = [];
        foreach($this->selected_roles as $id => $value) {
            if (count($this->roles->where('id',$id)) > 0 AND $value === true) {
                array_push($selected_roles_array, $id);
            }
        }

        try {
            User::updateOrCreate(
                ['id' => $this->modelId],
                [
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make($this->password),
                    'mail_report' => $this->mail_report,
                    'blocked' => $this->blocked,
                ]
            )->syncRoles($selected_roles_array);

        } catch(\Exception $e) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Fout! De gebruiker is niet aangemaakt/bewerkt',
                'text' => $e->getMessage(),
            ]);
        }

        return redirect(route('user.index'));
    }

    public function render()
    {
        return view('content.user.livewire.form');
    }
}
