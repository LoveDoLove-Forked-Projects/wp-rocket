<?php

return [
	'shouldReturnValidOptionsWithEmptyOptions' => [
		'input' => [
			'options' => [],
		],
		'expected' => [
			'delay_js'            => 0,
			'delay_js_exclusions' => [],
			'delay_js_execution_safe_mode' => 0,
		]
	],
	'shouldReturnValidOptionsWithOptionsNotArray' => [
		'input' => [
			'options' => 'test_option',
		],
		'expected' => [
			'test_option',
			'delay_js'            => 0,
			'delay_js_exclusions' => [],
			'delay_js_execution_safe_mode' => 0,
		]
	],
	'shouldOverrideOptions' => [
		'input' => [
			'options' => [
				'delay_js'            => 1,
				'delay_js_exclusions' => [
					'any value'
				],
				'delay_js_execution_safe_mode' => 1,
			],
		],
		'expected' => [
			'delay_js'            => 0,
			'delay_js_exclusions' => [],
			'delay_js_execution_safe_mode' => 0,
		]
	],
	'shouldNotOverrideOtherOptions' => [
		'input' => [
			'options' => [
				'test_option'      => 1,
				'delay_js'         => 0,
				'delay_js_exclusions' => [
					'any value'
				],
				'delay_js_execution_safe_mode' => 0,
			],
		],
		'expected' => [
			'test_option'         => 1,
			'delay_js'            => 0,
			'delay_js_exclusions' => [],
			'delay_js_execution_safe_mode' => 0,
		]
	],
];
