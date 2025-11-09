<div class="tab-pane fade active show" id="account-detail">
    @include('admin.partials.flash_messages')
    <div class="card">
        <div class="card-header">
            <h5>Account Details</h5>
        </div>
        <div class="card-body">
            
            <form method="post" method="post" action="{{ route('editAccount') }}">
                {{ @csrf_field() }}
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>First Name <span class="required">*</span></label>
                        <input required="" class="form-control square" name="first_name" value="{{ $user->first_name }}" type="text">
                        @error('first_name')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label>Last Name <span class="required">*</span></label>
                        <input required="" class="form-control square" name="last_name" value="{{ $user->last_name }}">
                        @error('last_name')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="form-group col-md-12">
                        <label>Bio <span class="required">*</span></label>
                        <input class="form-control square" name="bio" type="text" value="{{ $user->bio }}">
                    </div>
                    <div class="form-group col-md-12">
                        <label>Email Address <span class="required">*</span></label>
                        <input required="" class="form-control square" name="email" type="email" value="{{ $user->email }}" disabled>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Phone Number <span class="required">*</span></label>
                        <input required="" class="form-control square" type="phone" value="{{ $user->phonenumber }}" disabled>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-fill-out submit">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <h5>Change Password</h5>
        </div>
        <div class="card-body">
            <form method="post" method="post" action="{{ url('/auth/update-password') }}">
                {{ @csrf_field() }}
                <div class="row">
                    <div class="form-group col-md-12">
                        <label>Current Password <span class="required">*</span></label>
                        <input class="form-control square" type="password" placeholder="Current Password" type="text" required  name="current_password" value="{{ old('current_password') }}">
                        @error('current_password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group col-md-12">
                        <label>New Password <span class="required">*</span></label>
                        <input class="form-control square" type="password" placeholder="New Password" type="text" required  minlength="8" maxlength="36"  name="new_password" value="{{ old('new_password') }}">
                        @error('new_password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group col-md-12">
                        <label>Confirm Password <span class="required">*</span></label>
                        <input class="form-control square" type="password" placeholder="Confirm Password" type="text" required  minlength="8" maxlength="36"  name="confirmed_password" value="{{ old('confirmed_password') }}">
                        @error('confirmed_password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-fill-out submit">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>