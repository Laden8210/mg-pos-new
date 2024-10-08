<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
      <h4 class="fw-bold py-3 mb-4">
          <span class="text-muted fw-light">Account Settings /</span> Account
      </h4>
      @if (session('success'))
      <div id="successAlert" class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
  @endif
      <div class="row">
          <div class="col-md-12">
              <!-- Profile Details Card -->
              <div class="card mb-4">
                <h5 class="card-header">Profile Details</h5>
                <div class="card-body">
                    <div class="button-wrapper mt-3 text-center">
                        <form action="{{ route('upload.avatar') }}" method="POST" enctype="multipart/form-data">
                            @csrf <!-- Include CSRF token -->
                            <label for="upload" style="cursor: pointer;">
                                <img src="{{ auth()->user()->avatar ? asset('assets/img/avatars/' . auth()->user()->avatar) : asset('assets/img/avatars/1.png') }}" 
                                     alt="User Avatar" 
                                     class="d-block rounded-3 border border-secondary" 
                                     style="width: 150px; height: 150px; object-fit: cover;" 
                                     id="uploadedAvatar" />
                            </label>
                            
                            <input type="file" id="upload" class="account-file-input" name="avatar" hidden accept="image/png, image/jpeg" />
                            <p class="text-muted mb-0 mt-2">Allowed JPG or PNG. Max size: 10MB.</p>
                            
                            @error('avatar') 
                                <div class="text-danger mt-1">{{ $message }}</div> 
                            @enderror
                            
                            @if (session()->has('message'))
                                <div class="text-success mt-1">{{ session('message') }}</div>
                            @endif
                            
                            <button type="submit" class="btn btn-primary mt-2">Upload Avatar</button>
                        </form>
                    </div>
                </div>
            </div>
              <!-- User Information Update Form -->
              <div class="card mb-4">
                  <h5 class="card-header">Update Account Information</h5>
                  <div class="card-body">
                      <form wire:submit.prevent="updateUser" enctype="multipart/form-data">
                          <div class="row">
                              @foreach(['First Name' => 'ufirstname', 'Middle Name' => 'umiddle', 'Last Name' => 'ulastname', 'Suffix' => 'usuffix', 'Age' => 'uage', 'Address' => 'uaddress', 'Username' => 'uusername', 'Contact No.' => 'ucontact_number'] as $label => $model)
                                  <div class="col-lg-6 col-sm-6 col-12 mb-3">
                                      <div class="form-group">
                                          <label for="{{ $model }}">{{ $label }}</label>
                                          <input type="{{ in_array($label, ['Age', 'Contact No.']) ? 'number' : 'text' }}" id="{{ $model }}" placeholder="Enter {{ $label }}" class="form-control" wire:model="{{ $model }}" />
                                          @error($model) <span class="text-danger">{{ $message }}</span> @enderror
                                      </div>
                                  </div>
                              @endforeach

                              @foreach(['ugender' => ['Male', 'Female'], 'urole' => ['Manager', 'Cashier'], 'ustatus' => ['Active', 'Inactive']] as $model => $options)
                                  <div class="col-lg-4 col-sm-6 col-12 mb-3">
                                      <div class="form-group">
                                          <label for="{{ $model }}">{{ ucfirst(substr($model, 1)) }}</label>
                                          <select id="{{ $model }}" class="form-select" wire:model="{{ $model }}">
                                              <option value="">Select {{ ucfirst(substr($model, 1)) }}</option>
                                              @foreach($options as $option)
                                                  <option value="{{ $option }}">{{ $option }}</option>
                                              @endforeach
                                          </select>
                                          @error($model) <span class="text-danger">{{ $message }}</span> @enderror
                                      </div>
                                  </div>
                              @endforeach
                          </div>

                          <div class="mt-2">
                              <button type="submit" class="btn btn-primary me-2">Save changes</button>
                              <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                          </div>
                      </form>
                  </div>
              </div>

              <!-- Deactivation Account Card -->
              <div class="card">
                  <h5 class="card-header">Deactivate Account</h5>
                  <div class="card-body">
                      <div class="mb-3">
                          <div class="alert alert-warning">
                              <h6 class="alert-heading fw-bold mb-1">Are you sure you want to deactivate your account?</h6>
                              <p class="mb-0">Once you Deactivate your account, there is no going back. Please be certain.</p>
                          </div>
                      </div>
                      <form wire:submit.prevent="deactivateAccount">
                          <button type="submit" class="btn btn-danger">Deactivate Account</button>
                      </form>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
      setTimeout(function() {
          const successAlert = document.getElementById('successAlert');
          if (successAlert) {
              successAlert.style.transition = 'opacity 1s';
              successAlert.style.opacity = '0';
              setTimeout(() => successAlert.style.display = 'none', 1000);
          }
      }, 2000);
  });
</script>
