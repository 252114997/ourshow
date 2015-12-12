<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ScanImageCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'ourshow:scanimage';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'scan all pictures in directory, and insert into the table "tb_pictures".';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	public function isImage($path) {
		if (is_dir($path)) {
			$this->error("is directory! {$path}");
			return false;
		}
		if (0 == filesize($path)) {
			$this->error("filesize is 0! {$path}");
			return false;
		}

		$image_type = exif_imagetype($path);
		$image_type_array = array(
			IMAGETYPE_GIF,
			IMAGETYPE_JPEG,
			IMAGETYPE_PNG,
			IMAGETYPE_BMP,
		);
		if (!in_array($image_type, $image_type_array)) {
			$this->error("unknow image type! {$image_type} = exif_imagetype({$path})");
			return false;
		}

		return true;
	}

	public function grab_dump($var) {
		ob_start();
		var_dump($var);
		return ob_get_clean();
	}

	const PATH_SEPERATOR = "\\";
	public function scanPicture($scandir) {
		if (!is_dir($scandir)) {
			$this->info("skip scan {$scandir}[".json_encode(is_dir($scandir))."]");
			return 0;
		}

		$count = 0;
		$scanned_directorys = array_values(array_diff(scandir($scandir), array('..', '.')));
		foreach ($scanned_directorys as $key => $filepath) {
			$filepath = $scandir.self::PATH_SEPERATOR.$filepath;
			$filepath = str_replace('\\', '/', $filepath);
			$this->info("process {$filepath}");
			if (is_dir($filepath)) {
				$count += $this->scanPicture($filepath);
			}
			else if (!$this->isImage($filepath)) {
				$this->info("skip not image file! {$filepath} !");
			}
			else {
				$exif_data = exif_read_data($filepath);
				if (isset($exif_data['DateTimeOriginal'])) {
					$datetime_original = DateTime::createFromFormat(
						'Y:m:d H:i:s', 
						$exif_data['DateTimeOriginal']
					);
					$created_at = $datetime_original->format('Y-m-d H:i:s');
				}
				else {
					$created_at = date("Y-m-d H:i:s", filectime($filepath));
				}
				// Log::debug('create_time = '.json_encode($created_at));
				// Log::debug('exif_data = '.json_encode($exif_data));
				// die;
				$md5 = md5_file($filepath);
				$title = basename($filepath, '.'.pathinfo($filepath)['extension']);
				$path = substr($filepath, strlen(storage_path()));
				$caption = "";

				$insert_values = array(
					// 'id' => $md5,
					'name' => iconv('GBK','utf-8',$title),
					'path' => iconv('GBK','utf-8',$path),
					// 'caption' => $caption,
					'created_at' => $created_at
				);
				$picture = tb_pictures::updateOrCreate(
					array('id' => $md5),
					$insert_values
				)->toArray();
				$this->info("insert pictures: ".$this->grab_dump($picture));
				$count += 1;
			}
			$this->info("");
		}
		$this->info("found {$count} pictures in directory {$scandir}!");
		return $count;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$scandir = $this->option('scandir');
		$scandir = storage_path().self::PATH_SEPERATOR."img";

		$this->info("scanPicture {$scandir}");
		$this->scanPicture($scandir);
		$this->info('completed!');
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
		// 	array('username', InputArgument::OPTIONAL, '同步用户时执行的过滤选项！', ''),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('scandir', null, InputOption::VALUE_OPTIONAL, '指定扫描的绝对路径，默认扫描storage/img目录！', '*'),
		);
	}

}


?>