<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Cities as Cities;
use \App\External_File as ExternalFile;
use Illuminate\Support\Facades\DB;
use Zip;

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
    protected $description = 'This command update cities table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $job = "* * * * * php ".base_path()."/artisan $this->signature >> /dev/null 2>&1";
        $this->append_cronjob($job); //on Linux OS

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $file = "http://download.geonames.org/export/dump/RU.zip";
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $file);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FILETIME, true);

        $result = curl_exec($curl);
        $info = curl_getinfo($curl);

        $lastModifedData = ExternalFile::select('last_modified')->orderBy('id', 'desc')->first();

        if(!isset($lastModifedData) || date ('Y-m-d H:i:s', $info['filetime']) != $lastModifedData->last_modified) {
            $newfile = 'cities.zip';
            if (!copy($file, $newfile)) {
                $this->info("failed to copy ...");
                return false;
            }
            $zip = Zip::open($newfile);

            if ($zip) {
                $zip->extract(public_path(), 'RU.txt');
                ini_set('memory_limit', '-1');
                $filePath = str_replace("\\", "/" , public_path('RU.txt'));

                $pdo = DB::connection()->getPdo();
                $pdo->exec("LOAD DATA LOCAL INFILE '".$filePath."'
                IGNORE INTO TABLE cities
                FIELDS TERMINATED BY '\t'
                LINES TERMINATED BY '\n' STARTING BY ''");

                ExternalFile::insert(['last_modified' => date ('Y-m-d H:i:s', $info['filetime'])]);

                $this->info('Table was updated successfully!');
                $zip->close();
            }
        } else {
            $this->info('File not have updates!');
        }
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
