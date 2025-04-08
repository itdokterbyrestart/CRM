<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{
    User,
};
use Auth;
use Illuminate\Support\Facades\Hash;

class Form extends Component
{
    public $name, $email, $password, $password_confirmation, $mail_report = false, $updated_at, $created_at, $modelId;
    public $edit = 1;

    public function mount($model, $edit)
    {
        if (!Auth::user()->can('manage users')) {
            return abort(403);
        }
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
        }
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|min:2',
            'email' => 'required|email|max:255|unique:users,email,' . $this->modelId,
            'password' => 'nullable|string|min:8',
            'password_confirmation' => 'required_with:password|same:password',
            'mail_report' => 'required|boolean'
        ];
    }

    protected $validationAttributes = [
        'name' => 'naam',
        'email' => 'email',
        'password' => 'wachtwoord',
        'mail_report' => 'email rapportage',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function store()
    {
        $this->validate();

        try {
            User::updateOrCreate(
                ['id' => $this->modelId],
                [
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make($this->password),
                    'mail_report' => $this->mail_report,
                ]
            );

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
