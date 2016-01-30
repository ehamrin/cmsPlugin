<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="_method" value="{{ $method }}" />
    <div class="form-group">
        <strong>Name</strong>
        <input type="text" name="name" value="{{ $slide->getName() }}"/>
        @include("admin.partials.errorlist", array("name" => "name"))
    </div>
    <div class="form-group">
        <input type="file" name="slide" />
        @include("admin.partials.errorlist", ["name" => "filename"])
    </div>
    <div class="form-group">
        <strong>Alignment</strong>
        <label for="alignment_left">
            Left:
            <input id="alignment_left" type="radio" name="alignment" value="left"
               @if($slide->getAlignment() == 'left')
                   checked="checked"
               @endif
            />
        </label>
        <label for="alignment_center">
            Center:
            <input id="alignment_center" type="radio" name="alignment" value="center"
                @if($slide->getAlignment() == 'center')
                    checked="checked"
                @endif
            />
        </label>
        <label for="alignment_right">
            Right:
            <input id="alignment_right" type="radio" name="alignment" value="right"
               @if($slide->getAlignment() == 'right')
                    checked="checked"
               @endif
            />
        </label>
        @include("admin.partials.errorlist", array("name" => "alignment"))
    </div>
    <div class="form-group">
        <button type="submit">Ladda upp</button>
    </div>
</form>