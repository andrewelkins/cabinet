{{ "\n\n" }}
// I would recommend adding auth checks for the upload functionality.
@if (! $restful)

// Cabinet routes
Route::get( '{{ lcfirst(substr($name,0,-10)) }}',                        '{{ $name }}@index');
Route::post('{{ lcfirst(substr($name,0,-10)) }}',                        '{{ $name }}@store');
Route::get( '{{ lcfirst(substr($name,0,-10)) }}/results',                '{{ $name }}@list');
@else

// Cabinet RESTful route
Route::post('{{ lcfirst(substr($name,0,-10)) }}',                        '{{ $name }}@postIndex')
->before('csrf');
Route::controller( 'upload', '{{ $name }}');
@endif
