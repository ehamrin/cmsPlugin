<h1>Create new user</h1>
<form action="" method="POST" class="inline-1-2">
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" name="username" autocomplete="off"/>
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" autocomplete="off"/>
    </div>

    @foreach($permissions as $item)
        @include('partials.user_permission', compact('item', 'user'))
    @endforeach

    <button type="submit" name="add_submit">Save</button>
</form>