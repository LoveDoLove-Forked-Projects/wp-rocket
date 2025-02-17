<?php

namespace WP_Rocket\Tests\Unit\inc\Engine\Common\Cache\FilesystemCache;

use Brain\Monkey\Functions;
use Mockery;
use WP_Filesystem_Direct;
use WP_Rocket\Engine\Common\Cache\FilesystemCache;
use WP_Rocket\Tests\Unit\TestCase;

/**
 * Test class covering \WP_Rocket\Engine\Common\Cache\FilesystemCache::setMultiple
 */
class TestSetMultiple extends TestCase {
	protected $root_folder;
	protected $filesystem;
	protected $filesystemcache;

	public function set_up() {
		parent::set_up();
		$this->root_folder = '/background-css/';
		$this->filesystem = Mockery::mock( WP_Filesystem_Direct::class );

		$this->filesystemcache = new FilesystemCache( $this->root_folder, $this->filesystem );
	}

	/**
	 * @dataProvider configTestData
	 */
	public function testShouldReturnAsExpected( $config, $expected ) {
		Functions\when('rocket_get_filesystem_perms')->justReturn($config['rights']);
		Functions\when('home_url')->justReturn($config['home_url']);

		Functions\when('get_rocket_parse_url')->alias(function ($url) use ($config) {
			if(! key_exists($url, $config['parsed_url'])) {
				return;
			}
			return $config['parsed_url'][$url];
		});
		Functions\when('rocket_mkdir_p')->justReturn();

		Functions\when('rocket_get_constant')->justReturn($config['root']);
		Functions\when('get_current_blog_id')->justReturn( 1 );

		foreach ($config['saved'] as $path => $saved) {
			$this->filesystem->shouldReceive('put_contents')->with($path, $saved['content'], $config['rights'])->andReturn($saved['output']);
		}

		$this->assertSame($expected['output'], $this->filesystemcache->setMultiple($config['values'], $config['ttl']));
	}
}
