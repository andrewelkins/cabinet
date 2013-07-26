{{ "\n\n" }}
// Adding auth checks for the upload functionality is highly recommended.
@if (! $restful)

// Cabinet routes
Route::get('{{ lcfirst(substr($name,0,-10)) }}/data', '{{ $name }}@data');
Route::resource( '{{ lcfirst(substr($name,0,-10)) }}', '{{ $name }}',
        array('except' => array('show', 'edit', 'update', 'destroy')));
@else

// Cabinet RESTful route
Route::post('{{ lcfirst(substr($name,0,-10)) }}',                        '{{ $name }}@postIndex')
->before('csrf');
Route::controller( 'upload', '{{ $name }}');
@endif
