<?php

namespace bachphuc\LaravelHTMLElements\Commands;

use Illuminate\Console\Command;

class ManageCommand extends Command{   
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manage:create {params}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Management Controller';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
        $paramStr = $this->argument('params');
        if(empty($paramStr)){
            $this->error('Missing params');
            return;
        }

        // model=book,middleware=auth,route_name=books.user
        $params = [];
        $parts = explode(',', $paramStr);
        
        foreach($parts as $part){
            $tmp = explode('=', trim($part));
            if(count($tmp) === 2){
                if(!empty(trim($tmp[0])) ){
                    $params[trim($tmp[0])] = trim($tmp[1]);
                }
            }
        }

        if(empty($params)){
            $this->error('Missing params');
            return;
        }

        if(!isset($params['model'])){
            $this->error('Model is required');
            return;
        }

        // parameters for this command
        // $parameters = [
        //     'model',
        //     'middleware',
        //     'route_name',
        //     'class',
        //     'display_field',
        // ];

        $className = '';
        if(isset($params['class'])){
            $className = $params['class'];
            if(strpos('Controller', $className) === false){
                $className = ucfirst($params['class']) . 'Controller';
            }
        }
        
        if(empty($className)){
            $className = 'Manage'. ucfirst(strtolower($params['model'])) . 'Controller';
        }

        if(!isset($params['display_field'])){
            $params['display_field'] = 'title';
        }

        $path = app_path('Http/Controllers/' . $className. ".php");
        if(file_exists($path)){
            $this->error('Controller "' . $className. '" already exits.');

            // TODO: confirm override this file, this is risk.
            if ($this->confirm('Do you want to override this file: ' . $className . '?')) {
                if($this->confirm("Are you sure? File will be lost. Make sure that you backup this file.")) {
                    $this->info('Override file '. $className . '...');
                }
                else{
                    return;
                }
            }
            else{
                return;
            }            
        }
        $namespace = "App\Http\Controllers";

        $params['className'] = $className;
        $params['namespace'] = $namespace;
        if(!isset($params['route_name'])){
            $params['route_name'] = str_plural($params['model']);
        }

        $view = view('bachphuc.elements::commands.templates.manage', $params);
        $content = $view->render();
        
        $content = preg_replace('/^[\n\r\s]+$/m', '' , $content);
        
        file_put_contents($path, $content);

        $this->info('Create Management Controller ' . $className . ' successfully. File: ' . $path); 
    }
}