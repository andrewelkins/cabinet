<?php namespace Andrew13\Cabinet;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Generates routes.
 * Code originally from Confide https://github.com/Zizaco/confide
 */
class RoutesCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cabinet:routes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Append the default Cabinet controller routes to the routes.php';

    /**
     * Create a new command instance.
     *
     * @return \Andrew13\Cabinet\RoutesCommand
     */
    public function __construct()
    {
        parent::__construct();
        $app = app();
        $app['view']->addNamespace('cabinet',substr(__DIR__,0,-8).'views');
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $name = $this->prepareName($this->option('controller'));
        $restful = $this->option('restful');

        $this->line('');
        $this->info( "Routes file: app/routes.php" );

        if(! $restful)
        {
            $message = "The default Cabinet routes (to use with the Controller template)".
            " will be appended to your routes.php file.";
        }
        else
        {
            $message = "A single route to handle every action in a RESTful controller".
            " will be appended to your routes.php file. This may be used with a cabinet".
            " controller generated using [-r|--restful] option.";
        }
        

        $this->comment( $message );
        $this->line('');

        if ( $this->confirm("Proceed with the append? [Yes|no]") )
        {
            $this->line('');

            $this->info( "Appending routes..." );
            if( $this->appendRoutes( $name, $restful ) )
            {
                $this->info( "app/routes.php Patched successfully!" );
            }
            else{
                $this->error( 
                    "Coudn't append content to app/routes.php\nCheck the".
                    " write permissions within the file."
                );
            }

            $this->line('');

        }
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        $app = app();

        return array(
            array('controller', null, InputOption::VALUE_OPTIONAL, 'Name of the controller.', $this->app['config']->get('cabinet::upload_model')),
            array('--restful', '-r', InputOption::VALUE_NONE, 'Generate RESTful controller.'),
        );
    }

    /**
     * Prepare the controller name
     *
     * @param string  $name
     * @return string
     */
    protected function prepareName( $name = '' )
    {
        $name = ( $name != '') ? ucfirst($name) : 'Upload';
        
        if( substr($name,-10) == 'controller' )
        {
            $name = substr($name, 0, -10).'Controller';
        }
        else
        {
            $name .= 'Controller';
        }

        return $name;
    }

    /**
     * Create the controller
     *
     * @param  string $name
     * @param bool $restful
     * @return bool
     */
    protected function appendRoutes( $name = '', $restful = false )
    {        
        $app = app();
        $routes_file = $app['path'] . '/routes.php';
        $cabinet_routes = $app['view']->make('cabinet::generators.routes')
            ->with('name', $name)
            ->with('restful', $restful)
            ->render();

        if( file_exists( $routes_file ) )
        {
            $fs = fopen($routes_file, 'a');
            if ( $fs )
            {
                fwrite($fs, $cabinet_routes);
                $this->line($cabinet_routes);
                fclose($fs);
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

}
