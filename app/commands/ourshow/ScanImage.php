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

	const PATH_SEPERATOR = "/";
	public function scanPicture($scandir) {
		$this->info("");
		if (!is_dir($scandir)) {
			$this->info("skip scan {$scandir}[".json_encode(is_dir($scandir))."]");
			return 0;
		}

		$count = 0;
		$scanned_directorys = array_values(array_diff(scandir($scandir), array('..', '.')));
		$picture_info_array = array();
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
				// ignore  exif_read_data(CIMG0147_副本.jpg): Illegal IFD size: x016C + 2 + x03E8*12 = x304C > x0184
				$exif_data = @exif_read_data($filepath);
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

				$picture_info_array[] = array(
					'id' => $md5,
					//'name' => iconv('GBK','utf-8',$title),
					//'path' => iconv('GBK','utf-8',$path),
					'name' => $title,
					'path' => $path,
					// 'caption' => $caption,
					'created_at' => $created_at
				);
				$count += 1;
			}
		}
		$this->info("found {$count} pictures in directory {$scandir}!");
		if (0 == count($picture_info_array)) {
			return 0;
		}

		$ablum_info = array(
			//'title' => iconv('GBK','utf-8',basename($scandir)),
			//'tips' => iconv('GBK','utf-8',basename($scandir)),
			'title' => basename($scandir),
			'tips' => basename($scandir),
			'picture_id' => $picture_info_array[0]['id']
		);
		$ablum_info_real = tb_ablums::updateOrCreate(
			array('title' => $ablum_info['title']),
			$ablum_info
		)->toArray();
		tb_ablum_picture::where('ablum_id', $ablum_info_real['id'])->delete();
		$this->info("updateOrCreate ablum_info: ".$this->grab_dump($ablum_info));
		$this->info("updateOrCreate tb_ablums: ".$this->grab_dump($ablum_info_real));

		foreach ($picture_info_array as $picture_info) {
			$id = $picture_info['id'];
			$picture_info_real = tb_pictures::updateOrCreate(
				array('id' => $id),
				$picture_info
			)->toArray();
			$this->info("updateOrCreate tb_pictures: ".$this->grab_dump($picture_info_real));

			$ablum_picture_info_real = tb_ablum_picture::create(
				array(
					'ablum_id' => $ablum_info_real['id'],
					'picture_id' => $picture_info_real['id']
				)
			)->toArray();
			// $this->info("create tb_ablum_picture: ".$this->grab_dump($ablum_picture_info_real));
		}

		$this->info("");
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
