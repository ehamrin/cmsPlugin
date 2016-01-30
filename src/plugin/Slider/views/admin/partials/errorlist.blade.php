@if(isset($slide->getModelError()[$name]))
    <ul class="error-list">
        @if(is_array($slide->getModelError()[$name]))
            @foreach($slide->getModelError()[$name] as $error)
                <li>{{ $error }}</li>
            @endforeach
        @else
            <li>{{ $slide->getModelError()[$name] }}</li>
        @endif
    </ul>
@endif
