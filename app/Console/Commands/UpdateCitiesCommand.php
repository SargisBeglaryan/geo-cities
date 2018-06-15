<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Cities as Cities;
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
        // $this->append_cronjob($job); on Linux OS

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
            $this->info("failed to copy ...");
        }
        $zip = Zip::open($newfile);
        if ($zip) {
            $lastCityId = Cities::select('id')->orderBy('id', 'desc')->first();
            $zip->extract(public_path(), 'RU.txt');
            ini_set('memory_limit', '-1');
            $file = fopen(public_path('RU.txt'), "r");
            $cursor = -1;
            fseek($file, $cursor, SEEK_END);
            $char = fgetc($file);
            $lastLine = '';
            while ($char === "\n" || $char === "\r") {
                fseek($file, $cursor--, SEEK_END);
                $char = fgetc($file);
            }

            while ($char !== false && $char !== "\n" && $char !== "\r") {
                $lastLine = $char . $lastLine;
                fseek($file, $cursor--, SEEK_END);
                $char = fgetc($file);
            }
            $filePath = str_replace("\\", "/" , public_path('RU.txt'));
            $checkLastCity = explode("\t",$lastLine);
            if (!isset($lastCityId) || $checkLastCity[0] != $lastCityId->id) {
                $pdo = DB::connection()->getPdo();
                $pdo->exec("LOAD DATA LOCAL INFILE '".$filePath."'
                IGNORE INTO TABLE cities
                FIELDS TERMINATED BY '\t'
                LINES TERMINATED BY '\n' STARTING BY ''");
                $this->info('Table was updated successfully!');
            }
            if (is_file(public_path('RU.txt'))) {
                @unlink(public_path('RU.txt'));
            }
            if (is_file(public_path($newfile))) {
                @unlink(public_path($newfile));
            }
            fclose($file);
            $zip->close();
        }
        $this->info('User checked Successfully!');
        error_log("CronJob is working");
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
