<form role="form" class="form-horizontal" enctype="multipart/form-data"
    action="{{ route('patch_settings_nowpayments') }}" method="post" autocomplete="off">
    {{ csrf_field() }}
    {{ method_field('PATCH') }}

    <!-- Environment Field -->
    <div class="form-group">
        <label>Environment <span class="required">*</span></label>
        <?php echo form_dropdown('environment', $options['env_list'], old('environment', optional($rec->keys)->environment), "class='form-control selectPickerWithoutSearch'"); ?>
        <div class="invalid-feedback d-block">{{ showError($errors, 'environment') }}</div>
    </div>

    <!-- Display Name Field -->
    <div class="form-group">
        <label>Display Name <span class="required">*</span></label>
        <input type="text" class="form-control {{ showErrorClass($errors, 'name') }}" name="name"
            value="{{ old('name', optional($rec)->name) }}">
        <div class="invalid-feedback">{{ showError($errors, 'name') }}</div>
    </div>

    <!-- API Key Field -->
    <div class="form-group">
        <label>API Key <span class="required">*</span></label>
        <input type="text" class="form-control {{ showErrorClass($errors, 'api_key') }}" name="api_key"
            value="{{ old('api_key', optional($rec->keys)->api_key) }}">
        <div class="invalid-feedback">{{ showError($errors, 'api_key') }}</div>
    </div>

    <!-- Public Key Field -->
    <div class="form-group">
        <label>Public Key <span class="required">*</span></label>
        <input type="text" class="form-control {{ showErrorClass($errors, 'public_key') }}" name="public_key"
            value="{{ old('public_key', optional($rec->keys)->public_key) }}">
        <div class="invalid-feedback">{{ showError($errors, 'public_key') }}</div>
    </div>

    <!-- Inactive Checkbox -->
    <?php
    $inactive = old('inactive', optional($rec)->inactive) ? 'checked' : '';
    ?>
    <div class="form-group">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="inactive" name="inactive" value="1"
                {{ $inactive }}>
            <label class="custom-control-label" for="inactive">Inactive</label>
        </div>
    </div>

    <!-- Submit Button -->
    <input type="submit" name="submit" class="btn btn-success" value="Submit" />
</form>
