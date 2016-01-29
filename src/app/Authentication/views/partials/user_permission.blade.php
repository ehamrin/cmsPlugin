<div class="form-group">
    <label for="{{ $item->GetPermission() }}">{{ $item->GetName() }}</label>
    <input id="{{ $item->GetPermission() }}" name="permission[{{ $item->GetPermission() }}]" type="checkbox" value="{{ $item->GetPermission() }}"
           @if($user->Can($item->GetPermission()))
           checked=checked
            @endif
    />
</div>