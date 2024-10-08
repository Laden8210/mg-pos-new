<div>
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h1>User Management</h1>
            <div class="card p-2">
                <div class="card-body">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-path">
                                <a class="btn btn-filter" id="filter_search">
                                    <img src="{{ asset('img/icons/search-whites.svg') }}" alt="img">
                                    <span><img src="{{ asset('img/icons/closes.svg') }}" alt="img"></span>
                                </a>
                            </div>
                            <div class="search-input">

                                <input type="text" class="form-control" placeholder="Search"
                                    wire:model.live.debounce.300ms = "search">
                            </div>
                        </div>
                        <div>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-user">
                                <i class="fas fa-plus"></i> Add User
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive" wire:poll.1s>
                        <table class="table datanew">
                            <thead>
                                <tr>

                                    <th class="text-center">Full Name</th>
                                    <th class="text-center">Username</th>
                                    <th class="text-center">Contact No.</th>
                                    <th class="text-center">Date Joined</th>
                                    <th class="text-center">Access Level</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                    @foreach ($employees as $e)
                                        <tr>

                                            <td class="productimgname">
                                                <a
                                                    href="javascript:void(0);">{{ $e->firstname . ' ' . $e->lastname }}</a>
                                            </td>
                                            <td class="text-center">{{ $e->username }}</td>
                                            <td class="text-center">{{ $e->contact_number }}</td>
                                            <td class="text-center">{{ date('Y-m-d', strtotime($e->created_at)) }}</td>
                                            <td class="text-center">
                                                @php
                                                    $e->role = strtolower($e->role);
                                                @endphp
                                                @if ($e->role == 'cashier')
                                                    <span class="badges bg-info">Cashier</span>
                                                @endif
                                                @if ($e->role == 'manager')
                                                    <span class="badges bg-lightyellow">Manager</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @php

                                                    $e->status = strtolower($e->status);
                                                @endphp
                                                @if ($e->status == 'active')
                                                    <span class="badges bg-lightgreen">Active</span>
                                                @endif

                                                @if ($e->status == 'inactive')
                                                    <span class="badges bg-lightred">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="text-center">

                                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#update-user"
                                                    wire:click="editUser({{ $e->employee_id }})" type="button">
                                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                                </button>

                                            </td>
                                        </tr>
                                    @endforeach

                            </tbody>
                        </table>
                    </div>


                </div>

                <nav>
                    {{ $employees->links('vendor.pagination.bootstrap-5') }}
                </nav>

            </div>
        </div>
    </div>

    @include('livewire.user.user-modal')

</div>
