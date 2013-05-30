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
        ${{ lcfirst(Config::get('auth.model')) }} = new {{ Config::get('cabinet::upload_model') }};

        ${{ lcfirst(Config::get('cabinet::upload_model')) }}->filename = Input::get( 'username' );
        ${{ lcfirst(Config::get('cabinet::upload_model')) }}->filetype = Input::get( 'email' );
        ${{ lcfirst(Config::get('cabinet::upload_model')) }}->password = Input::get( 'password' );

        // Process the files uploaded, and save them.


        // Save if valid. Password field will be hashed before save
        ${{ lcfirst(Config::get('auth.model')) }}->save();

        if ( ${{ lcfirst(Config::get('auth.model')) }}->id )
        {
            // Redirect with success message, You may replace "Lang::get(..." for your custom message.
            @if (! $restful)
            return Redirect::action('{{ $name }}@list')
            @else
            return Redirect::to('user/list')
            @endif
                ->with( 'notice', Lang::get('confide::confide.alerts.account_created') );
        }
        else
        {
            // Get validation errors (see Ardent package)
            $error = ${{ lcfirst(Config::get('cabinet::upload_model')) }}->errors()->all(':message');

            @if (! $restful)
            return Redirect::action('{{ $name }}@index')
            @else
            return Redirect::to('upload')
            @endif
                ->withInput(Input::except('password'))
                ->with( 'error', $error );
        }
    }

}
