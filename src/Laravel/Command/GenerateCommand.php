<?php
namespace In\Blueprint\Laravel\Command;

use Doctrine\Common\Annotations\SimpleAnnotationReader;
use File;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use In\Blueprint\Blueprint;
use In\Blueprint\Util;

/**
 * Created by IntelliJ IDEA.
 * User: apple
 * Date: 17/6/8
 * Time: 上午12:52
 */
class GenerateCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'blueprint:generate {file?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate docs';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->info('Regenerating docs');

        $resources = new Collection();

        $outPath = 'blueprint.apib';
        if ($file = $this->argument('file')) {

            if (substr($file, '-4') != '.php') {
                $this->error("it is not an php file \r\n{$file}");
                return;
            }

            $name = 'A' . substr($file, 1, strlen($file) - 5);
            $className = str_replace('/', '\\', $name);
            $resources->push($className);

            $outPath = $name . ".apib";
            $dirPath = 'api/'. substr($outPath, 0, strrpos($name, '/'));
            if (!file_exists(storage_path($dirPath))) {
                File::makeDirectory(storage_path($dirPath), 0755, true);
            } else {
                if (!is_dir(storage_path($dirPath))) {
                    $this->error("{$dirPath} existed, but it is not an directory \r\n{$file}");
                    return;
                }
            }

        } else {

            $finder = Util::finder(app_path('Http/Controllers'));

            foreach ($finder as $file) {
                if (substr($file->getFilename(), '-4') != '.php') {
                    continue;
                }
                $name = 'App/Http/Controllers/' . substr($file->getRelativePathname(), 0, strlen($file->getRelativePathname()) - 4);
                $className = str_replace('/', '\\', $name);
                $resources->push($className);
            }
        }

        $blueprint = new Blueprint(new SimpleAnnotationReader, new Filesystem);
        $content = $blueprint->generate($resources, config('app.name'), 'v1', null);

        file_put_contents(storage_path('api/'. $outPath), $content);
        file_put_contents(storage_path('api/'. str_replace('.apib', '.md', $outPath)), $content);
        $this->info(implode("\r\n", [
            "done generate ",
            storage_path('api/'. $outPath),
            print_r($resources, 1),
        ]));
    }
}
