<?php

namespace Service\Console\Commands;

use Illuminate\Console\Command;

class UpdateCitiesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'citiesjob:cities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $job = "* * * * * php ".base_path()."/artisan $this->signature >> /dev/null 2>&1";
        $this->append_cronjob($job);

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $file = "http://download.geonames.org/export/dump/RU.zip";
        $newfile = 'cities.zip';

        if (!copy($file, $newfile)) {
            echo "failed to copy ...";
        }
         $zip = Zip::open($newfile);
         $citiesFile = '';
         if ($zip) {
            $zip->extract(public_path(), 'RU.txt');
            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', 300);
            $file = fopen(public_path('RU.txt'), "r");
            while(!feof($file)){
                $oneLine = fgets($file);
                $citiesData = explode("\t",$oneLine);
                $citiesData[18] = substr($citiesData[18], 0, -1);
                Cities::insert([
                    'id'=> $citiesData[0], 'name' => $citiesData[1],
                    'latitude'=> $citiesData[4], 'longitude' => $citiesData[5],
                    'country_code' => $citiesData[8], 'timezone' => $citiesData[17],
                    'date' => $citiesData[18]
                    ]);
            }
            unlink(public_path('RU.txt'));
            unlink(public_path($newfile));
            fclose($file);
            $zip->close();
        $this->info('User Name Change Successfully!');
        error_log("CronJob is working");
        error_log("You messed up!", 3, "/var/tmp/my-errors.log");
    }

    public function cronjob_exists($command){
        $cronjob_exists=false;
        exec('crontab -l', $crontab);

        if(isset($crontab)&&is_array($crontab)){
            $crontab = array_flip($crontab);
            if(isset($crontab[$command])){
                $cronjob_exists=true;
            }
        }
        return $cronjob_exists;
    }

    function append_cronjob($command){
        $output = shell_exec('crontab -l');
        if(is_string($command) && !empty($command) && $this->cronjob_exists($command)===FALSE){
            file_put_contents('/tmp/crontab.txt', $output.$command.PHP_EOL);
            echo exec('crontab /tmp/crontab.txt');
        }
        return $output;
    }
}
