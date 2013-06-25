<link rel="stylesheet" type="text/css" href="{{ URL::asset('packages/andrew13/cabinet/css/basic.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ URL::asset('packages/andrew13/cabinet/css/dropzone.css') }}" />

<script src="{{ URL::asset('packages/andrew13/cabinet/js/dropzone.min.js') }}"></script>
<script src="{{ URL::asset('packages/andrew13/cabinet/js/dropzone.min.js') }}"></script>

<form action="{{ URL::to('upload') }}" class="dropzone" id="cabinet-dropzone" accept-charset="UTF-8">
    <input type="hidden" name="_token" value="{{{ Session::getToken() }}}">
    <div class="fallback">
        <input name="file" type="file" multiple />
    </div>
</form>