<h1>Manage page slider</h1>
<div class="table-wrapper">
    <table>
        @foreach($slides as $slide)
        <tr>
            <td>{{ $slide->getName() }}</td>
            <td>{{$slide->getFilename()}}</td>
            <td>{{$slide->getCreated() }}</td>
            <td><a href="/admin/slider/edit/{{ $slide->getId() }}"><i class="fa fa-pencil-square-o"></i></a></td>
            <td><a class="delete" href="/admin/slider/delete/{{ $slide->getId() }}"><i class="fa fa-trash-o"></i></a></td>
        </tr>
        @endforeach
    </table>
</div>

