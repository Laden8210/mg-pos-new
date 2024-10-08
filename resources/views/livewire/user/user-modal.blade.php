<div class="modal fade" id="add-user" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
wire:ignore.self>
<div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Create User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <form wire:submit.prevent="store" enctype="multipart/form-data">
                @if (session('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif

                <div class="row">
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" name="FirstName" placeholder="Enter First Name"
                                class="form-control" wire:model="firstname">
                            @error('firstname')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Middle Name</label>
                            <input type="text" name="MiddleName" placeholder="Enter Middle Name"
                                class="form-control" wire:model="middle">
                            @error('middle')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="LastName" placeholder="Enter Last Name"
                                class="form-control" wire:model="lastname">
                            @error('lastname')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Suffix</label>
                            <input type="text" name="Suffix" placeholder="Enter Suffix" class="form-control"
                                wire:model="suffix">
                            @error('suffix')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Age</label>
                            <input type="number" name="Age" placeholder="Enter Age"
                                class="form-control" wire:model="age">
                            @error('age')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="Address" placeholder="Enter Address"
                                class="form-control" wire:model="address">
                            @error('address')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="Username" placeholder="Enter Username"
                                class="form-control" wire:model="username">
                            @error('username')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="Password" placeholder="Enter Password"
                                class="form-control" wire:model="password">
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input type="password" name="ConfirmPassword" placeholder="Confirm your Password"
                                class="form-control" wire:model="password_confirmation">
                            @error('confirm_password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Contact No.</label>
                            <input type="number" name="ContactNumber" placeholder="Enter Contact Number"
                                class="form-control" wire:model="contact_number">
                            @error('contact_number')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Gender</label>
                            <select name="Gender" class="form-select" wire:model="gender">
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            @error('gender')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Access Level</label>
                            <select name="AccessLevel" class="form-select" wire:model="role">
                                <option value="">Select Access Level</option>
                                <option value="Manager">Manager</option>
                                <option value="Cashier">Cashier</option>
                            </select>
                            @error('role')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="Status" class="form-select" wire:model="status">
                                <option value="">Select Status</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Avatar</label>
                            <div class="image-upload">
                                <input type="file" name="avatar" wire:model="avatar" accept="image/*">
                                <div class="image-uploads">
                                    <img src="{{ asset('img/icons/upload-outline.svg') }}" alt="img">
                                    <h4>Drag and drop a file to upload</h4>
                                </div>
                            </div>
                            @error('avatar')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                            <!-- Displaying Preview -->
                            @if ($avatar)
                                <img src="{{ $avatar->temporaryUrl() }}" width="100" height="100" alt="Avatar Preview">
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" >Add
                        Employee</button>
                </div>
            </form>
        </div>
    </div>
</div>



</div>



<div class="modal fade" id="update-user" tabindex="-1" aria-labelledby="update-user-label" aria-hidden="true"
wire:ignore.self>
<div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="update-user-label">Update User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <form wire:submit.prevent="updateUser" enctype="multipart/form-data">

                @if (session('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif

                <div class="row">
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" name="FirstName" placeholder="Enter First Name"
                                class="form-control" wire:model="ufirstname">
                            @error('ufirstname')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Middle Name</label>
                            <input type="text" name="MiddleName" placeholder="Enter Middle Name"
                                class="form-control" wire:model="umiddle">
                            @error('umiddle')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="LastName" placeholder="Enter Last Name"
                                class="form-control" wire:model="ulastname">
                            @error('ulastname')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Suffix</label>
                            <input type="text" name="Suffix" placeholder="Enter Suffix"
                                class="form-control" wire:model="usuffix">
                            @error('usuffix')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Age</label>
                            <input type="number" name="Age" placeholder="Enter Age"
                                class="form-control" wire:model="uage">
                            @error('uage')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="Address" placeholder="Enter Address"
                                class="form-control" wire:model="uaddress">
                            @error('uaddress')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="Username" placeholder="Enter Username"
                                class="form-control" wire:model="uusername">
                            @error('uusername')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="Password" placeholder="Enter Password"
                                class="form-control" wire:model="upassword">
                            @error('upassword')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input type="password" name="ConfirmPassword" placeholder="Confirm your Password"
                                class="form-control" wire:model="upassword_confirmation">
                            @error('upassword_confirmation')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Contact No.</label>
                            <input type="number" name="ContactNumber" placeholder="Enter Contact Number"
                                class="form-control" wire:model="ucontact_number">
                            @error('ucontact_number')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Gender</label>
                            <select name="Gender" class="form-select" wire:model="ugender">
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            @error('ugender')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Access Level</label>
                            <select name="AccessLevel" class="form-select" wire:model="urole">
                                <option value="">Select Access Level</option>
                                <option value="Manager">Manager</option>
                                <option value="Cashier">Cashier</option>
                            </select>
                            @error('urole')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="Status" class="form-select" wire:model="ustatus">
                                <option value="">Select Status</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            @error('ustatus')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Avatar</label>

                            <div class="image-upload">
                                <input type="file" name="avatar" wire:model="avatar" accept="image/*">
                                <div class="image-uploads">
                                    <img src="{{ asset('img/icons/upload-outline.svg') }}" alt="img">
                                    <h4>Drag and drop a file to upload</h4>
                                </div>
                            </div>
                            @error('uavatar')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Employee</button>
                </div>
            </form>

        </div>
    </div>
</div>
</div>
