<div id="slider">
    <div class="slider">
        @foreach($slides as $slide)
            <div class="slide" style="background-image: url('{{ $slide->getPublicFilename() }}');background-position: center {{ $slide->getAlignment() }};"></div>
        @endforeach
    </div>
</div>
