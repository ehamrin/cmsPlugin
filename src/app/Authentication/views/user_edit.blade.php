<h1>Editing {{ $user->GetUsername() }}</h1>
<form action="" method="POST" class="inline-1-2">
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" name="username" value="{{ $user->GetUsername() }}" autocomplete="off"/>
    </div>

    @foreach($permissions as $item)
        @include('partials.user_permission', compact('item', 'user'))
    @endforeach

    <button type="submit" name="edit_submit">Save</button>
</form>
<form action="" method="POST" class="inline-1-2">

    <div class="form-group">
        <label for="old">Old password</label>
        <input type="password" name="old" autocomplete="off"/>
    </div>
    <div class="form-group">
        <label for="new">New password</label>
        <input type="password" name="new" autocomplete="off"/>
    </div>
    <div class="form-group">
        <label for="new_repeat">Repeat password</label>
        <input type="password" name="new_repeat" autocomplete="off"/>
    </div>

    <button type="submit" name="edit_password">Update password</button>
</form>