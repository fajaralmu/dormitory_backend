<div>
    @if($jenis == 'gambar')
        <img src="{{ Storage::url('media/' . $filename) }}" width="300">
    @endif

    @if($jenis == 'audio')
        <audio controls>
            <source src="{{ Storage::url('media/' . $filename) }}" type="audio/mp3">
            Your browser does not support the <code>audio</code> element.
        </audio>
    @endif

    @if($jenis == 'video')
        <video controls>
            <source src="{{ Storage::url('media/' . $filename) }}" type="video/mp4">
            Your browser does not support the <code>video</code> element.
        </video>
    @endif
</div>