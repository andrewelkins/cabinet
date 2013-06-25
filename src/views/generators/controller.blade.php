{{ '<?php' }}

/*
|--------------------------------------------------------------------------
| Cabinet Controller Template
|--------------------------------------------------------------------------
|
| This is the default Cabinet controller template for controlling uploads.
| Feel free to change to your needs.
|
*/

class {{ $name }} extends BaseController {

    protected $softDelete = true;

    /**
     * Displays the form for account creation
     *
     */
    public function {{ (! $restful) ? 'index' : 'getIndex' }}()
    {
        return View::make(Config::get('cabinet::upload_form'));
    }

    /**
     * Stores new account
     *
     */
    public function {{ (! $restful) ? 'store' : 'postIndex' }}()
    {
        $file = Input::file('file');

        ${{ lcfirst(Config::get('cabinet::upload_model')) }} = new {{ Config::get('cabinet::upload_model') }};

        try {
            list(${{ lcfirst(Config::get('cabinet::upload_model')) }}->filename, ${{ lcfirst(Config::get('cabinet::upload_model')) }}->path) = ${{ lcfirst(Config::get('cabinet::upload_model')) }}->upload($file);
        } catch(Exception $exception){
            // Something went wrong. Log it.
            Log::error($exception);
            // Return error
            return Response::json($exception->getMessage(), 400);
        }

        // File extension
        ${{ lcfirst(Config::get('cabinet::upload_model')) }}->extension = $file->getExtension();
        // Mimetype for the file
        ${{ lcfirst(Config::get('cabinet::upload_model')) }}->mimetype = ${{ lcfirst(Config::get('cabinet::upload_model')) }}->getMimeType();
        // Current user or 0
        ${{ lcfirst(Config::get('cabinet::upload_model')) }}->user_id = (Auth::user() ? Auth::user()->id : 0);

        ${{ lcfirst(Config::get('cabinet::upload_model')) }}->save();

        // If it now has an id, it should have been successful.
        if ( ${{ lcfirst(Config::get('cabinet::upload_model')) }}->id ) {
            return Response::json(array('status' => 'success', 'file' => ${{ lcfirst(Config::get('cabinet::upload_model')) }}->toArray()), 200);
        } else {
            return Response::json('Error', 400);
        }
    }

    public function {{ (! $restful) ? 'list' : 'getList' }}()
    {
        return View::make(Config::get('cabinet::upload_list'));
    }

    public function {{ (! $restful) ? 'data' : 'getData' }}()
    {
        $uploads = {{ Config::get('cabinet::upload_model') }}::all();

        return Datatables::of($uploads)
            ->remove_column('id')
            ->remove_column('deleted_at')
            ->remove_column('created_at')
            ->remove_column('updated_at')
            ->make();
    }

}
