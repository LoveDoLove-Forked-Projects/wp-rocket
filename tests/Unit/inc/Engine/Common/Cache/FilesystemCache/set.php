<?php

namespace WP_Rocket\Tests\Unit\inc\Engine\Common\Cache\FilesystemCache;

use Brain\Monkey\Functions;
use Mockery;
use WP_Filesystem_Direct;
use WP_Rocket\Engine\Common\Cache\FilesystemCache;
use WP_Rocket\Tests\Unit\TestCase;

/**
 * Test class covering \WP_Rocket\Engine\Common\Cache\FilesystemCache::set
 */
class TestSet extends TestCase {
	protected $root_folder;
	protected $filesystem;
	protected $filesystemcache;

	public function set_up() {
		parent::set_up();

		$this->root_folder = '/background-css';
		$this->filesystem = Mockery::mock( WP_Filesystem_Direct::class );

		$this->filesystemcache = new FilesystemCache( $this->root_folder, $this->filesystem );
	}

	/**
	 * @dataProvider configTestData
	 */
	public function testShouldReturnAsExpected( $config, $expected ) {
	Functions\when('rocket_get_filesystem_perms')->justReturn($config['rights']);
	Functions\expect('get_rocket_parse_url')->with($expected['url'])->andReturn($config['parsed_url']);
	Functions\when('rocket_get_constant')->justReturn($config['root']);
	Functions\expect('rocket_mkdir_p')->with( dirname($expected['path']), $this->filesystem );
	Functions\when('home_url')->justReturn($config['home_url']);
		Functions\when('get_current_blog_id')->justReturn( 1 );

	$this->filesystem->shouldReceive('put_contents')->with($expected['path'], $expected['content'], $config['rights'])->andReturn($config['saved']);

	$this->assertSame($expected['output'], $this->filesystemcache->set($config['key'], $config['value'], $config['ttl']));
	}
}
