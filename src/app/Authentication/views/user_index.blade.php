<h1>Viewing all users</h1>
<table>
    <tr>
        <th>Username</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>
@foreach($users as $user)
    <tr>
        <td>
            {{ $user->GetUsername() }}
        </td>
        <td>
            <a href="/admin/user/edit/{{ $user->GetID() }}" class="edit"><i class="fa fa-pencil-square-o"></i></a>
        </td>
        <td>
        @unless($user->GetID() == 1)
            <a href="/admin/user/delete/{{ $user->GetID() }}" class="delete"><i class="fa fa-trash"></i></a>
        @endunless
        </td>
    </tr>
@endforeach

</table>