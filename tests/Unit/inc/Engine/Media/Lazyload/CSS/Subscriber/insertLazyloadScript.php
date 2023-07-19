<?php

namespace WP_Rocket\Tests\Unit\inc\Engine\Media\Lazyload\CSS\Subscriber;

use Engine\Media\Lazyload\CSS\Subscriber\SubscriberTrait;
use Brain\Monkey\Functions;
use Brain\Monkey\Filters;
use WP_Rocket\Tests\Unit\TestCase;

/**
 * @covers \WP_Rocket\Engine\Media\Lazyload\CSS\Subscriber::insert_lazyload_script
 */
class Test_insertLazyloadScript extends TestCase {

	use SubscriberTrait;

	public function set_up() {
		$this->init_subscriber();
	}

    /**
     * @dataProvider configTestData
     */
    public function testShouldDoAsExpected( $config, $expected )
    {
		Functions\when('rocket_get_constant')->alias(function ($name) use ($config) {
			if('WP_ROCKET_VERSION' === $name) {
				return $config['WP_ROCKET_VERSION'];
			}
			if('WP_ROCKET_ASSETS_JS_URL' === $name) {
				return $config['WP_ROCKET_ASSETS_JS_URL'];
			}

			return null;
		});

		$this->context->expects()->is_allowed()->andReturn($config['is_allowed']);

		if($config['is_allowed']) {
			Filters\expectApplied('rocket_lazyload_threshold')->with(300)->andReturn($config['threshold']);
			Functions\expect('wp_enqueue_script')->with('rocket_lazyload_css', $expected['url'], [], $expected['version'], true);
			Functions\expect('wp_localize_script')->with('rocket_lazyload_css', 'rocket_lazyload_css_data', $expected['data']);
		}

		$this->subscriber->insert_lazyload_script();
    }
}
