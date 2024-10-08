<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Employee;
use Livewire\WithFileUploads;

class UserAccountSettings extends Component
{
    use WithFileUploads;

    public $employee_id;
    public $ufirstname;
    public $ulastname;
    public $umiddle;
    public $usuffix;
    public $uage;
    public $uaddress;
    public $ucontact_number;
    public $ugender;
    public $urole;
    public $ustatus;
    public $uusername;
    public $upassword;
    public $upassword_confirmation;
    public $avatar;

    public function mount()
    {
        $employee = auth()->user();
        $this->fillEmployeeData($employee);
    }

    private function fillEmployeeData($employee)
    {
        $this->employee_id = $employee->employee_id;
        $this->ufirstname = $employee->firstname;
        $this->ulastname = $employee->lastname;
        $this->umiddle = $employee->middle;
        $this->usuffix = $employee->suffix;
        $this->uage = $employee->age;
        $this->uaddress = $employee->address;
        $this->ucontact_number = $employee->contact_number;
        $this->ugender = $employee->gender;
        $this->urole = $employee->role;
        $this->ustatus = $employee->status;
        $this->uusername = $employee->username;
    }

    public function updateUser()
    {
        $this->validateUserData();

        $employee = Employee::find($this->employee_id);
        if ($employee) {
            $this->updateEmployeeData($employee);
            session()->flash('success', 'Employee account updated successfully.');
        } else {
            session()->flash('error', 'Employee not found.');
        }
    }

    private function validateUserData()
    {
        $this->validate([
            'ufirstname' => 'required|string|max:255',
            'ulastname' => 'required|string|max:255',
            'umiddle' => 'nullable|string|max:255',
            'usuffix' => 'nullable|string|max:10',
            'uage' => 'required|integer|min:18',
            'uaddress' => 'required|string|max:255',
            'ucontact_number' => 'required|string|max:20',
            'ugender' => 'required|in:Male,Female',
            'urole' => 'required|string|max:255',
            'ustatus' => 'required|string|in:Active,Inactive',
            'uusername' => 'required|string|max:255|unique:employees,username,' . $this->employee_id . ',employee_id',
            'upassword' => 'nullable|string|min:6',
            'upassword_confirmation' => 'same:upassword',
            'avatar' => 'nullable|image|max:10240',
        ]);
    }

    private function updateEmployeeData($employee)
    {
        $employee->firstname = $this->ufirstname;
        $employee->lastname = $this->ulastname;
        $employee->middle = $this->umiddle;
        $employee->suffix = $this->usuffix;
        $employee->age = $this->uage;
        $employee->address = $this->uaddress;
        $employee->contact_number = $this->ucontact_number;
        $employee->gender = $this->ugender;
        $employee->role = $this->urole;
        $employee->status = $this->ustatus;
        $employee->username = $this->uusername;

        // Handle avatar upload if a new file is provided
        if ($this->avatar) {
            // Store the new avatar and update the employee record
            $avatarPath = $this->avatar->store('public/assets/img/avatars');
            $employee->avatar = str_replace('public/', '', $avatarPath); // Adjust the path for storage
        }

        $employee->updated_at = now();
        $employee->save();
    }

    public function deactivateAccount()
    {
        $employee = auth()->user();

        if ($employee) {
            $employee->status = 'Inactive';
            $employee->save();

            auth()->logout();
            session()->flash('message', 'Your account has been deactivated successfully.');
            return redirect()->route('login'); // Redirect to login page
        } else {
            session()->flash('error', 'User not found.');
        }
    }

    public function render()
    {
        return view('livewire.user.user-account-settings');
    }
}
