<?php

$root = __DIR__ . '/../';
$count = 0;
$ignore = [];

function scan($dir)
{
	$count = 0;
	$ignore = [];

	echo 'scan ' . $dir . PHP_EOL;

	foreach (new DirectoryIterator($dir) as $dir_or_file)
	{
		if ($dir_or_file->isDot())
			continue;

		if ($dir_or_file->isDir())
		{
			$is_ignore = false;

			foreach ($ignore as $i)
			{
				if (strstr($dir_or_file->getPathname(), $i))
				{
					$is_ignore = true;
					break;
				}
			}

			if (!$is_ignore)
				$count += scan($dir_or_file->getPathname());

			continue;
		}

		if (!$dir_or_file->isFile())
			throw new \Exception('Not file');

		if (in_array($dir_or_file->getExtension(), ['js', 'php']))
			$count += count(file($dir_or_file->getPathname()));
	}

	return $count;
}

foreach (new DirectoryIterator($root) as $dir_or_file)
{
	if ($dir_or_file->isDot())
		continue;
	if ($dir_or_file->isDir())
	{
		$is_ignore = false;

		foreach ($ignore as $i)
		{
			if (strstr($dir_or_file->getPathname(), $i))
			{
				$is_ignore = true;
				break;
			}
		}

		if (!$is_ignore)
			$count += scan($dir_or_file->getPathname());
	}
}

echo 'count: ' . $count . PHP_EOL;
