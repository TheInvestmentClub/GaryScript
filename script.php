<?php

class GarysRenameFiles {

	public $count;
	public $renames = [];
	public $exclude = ['.', '..', '.git'];

	public function __construct() {
		$fileName = "./renames-2021.csv";
		$file = file_get_contents($fileName);
		$lines = explode("\r", $file);
		$renames = [];
		foreach ($lines as $line) {
			[$old, $new] = explode(",", $line);
			$renames[$old] = $new;
		}
		$this->renames = $renames;
	}

	public function searchDir(string $dir, string $path) {
		$files = $directories = [];
		$filesAndDirs = array_diff(scandir($dir), $this->exclude);
		foreach ($filesAndDirs as $item) {
			if (is_dir("$path/$item")) {
				$directories[] = "$path/$item";
			}
			elseif (preg_match("@(.*)\.jpg$@", $item, $matches)) {
					$files[] = $item;
			}
		}
		return [$directories, $files];
	}

	public function processFiles($files, $path) {
		if (!$files) {
			return null;
		}
		foreach ($files as $jpg) {
			if (isset($this->renames[$jpg])) {
				print "Renaming {$path}/{$jpg} to " . $this->renames[$jpg] . "\n";
				rename("{$path}/{$jpg}", $path . '/' .$this->renames[$jpg]);
			}
			else {
				print "Renaming {$path}/{$jpg}  to deleted_{$jpg}\n";
				rename("{$path}/{$jpg}", "{$path}/deleted_{$jpg}");
			}
		}

		$this->count += count($files);
		return;
	}

	public function handle() {
		if (file_exists('lockfile')) {
			exit("Cannot run until lockfile is deleted.");
		}
		$start = \realpath('.');
		file_put_contents("{$start}/lockfile", '');
		[$directories, $files] = $this->searchDir('.', $start);
		while ($directories) {
			$directoryQueue = [];
			foreach ($directories as $dir) {
				$path = realpath($dir);
				[$newDirectories, $files] = $this->searchDir($dir, $path);
				$directoryQueue = array_merge($directoryQueue, $newDirectories);
				$this->processFiles($files, $path);
			}
			$directories = $directoryQueue;
		}
		print "Done.\n";
		print $this->count;
	}
}

$script = new GarysRenameFiles();
$script->handle();
?>
