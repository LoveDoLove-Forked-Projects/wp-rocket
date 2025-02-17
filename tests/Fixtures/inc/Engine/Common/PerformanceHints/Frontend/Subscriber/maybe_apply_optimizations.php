<?php

$html_input = file_get_contents(__DIR__ . '/HTML/input.html');
$html_input_without_closing_body_tag = file_get_contents(__DIR__ . '/HTML/no_closing_body_tag_input.html');
$html_input_without_closing_body_tag_output = file_get_contents(__DIR__ . '/HTML/no_closing_body_tag_output.html');
$html_with_double_body = file_get_contents(__DIR__ . '/HTML/double_body_tag.html');
$html_with_double_body_output = file_get_contents(__DIR__ . '/HTML/output_double_body_tag.html');
$html_output = file_get_contents(__DIR__ . '/HTML/output.html');
$html_output_with_preload = file_get_contents(__DIR__ . '/HTML/output_w_preload.html');
$html_output_with_beacon = file_get_contents(__DIR__ . '/HTML/output_w_beacon.html');
$html_input_with_bg_image_lcp = file_get_contents(__DIR__ . '/HTML/input_w_bg_image_lcp.html');
$html_output_with_bg_image_lcp = file_get_contents(__DIR__ . '/HTML/output_w_bg_image_lcp.html');
$html_input_with_picture_img_lcp = file_get_contents(__DIR__ . '/HTML/input_w_picture_img_lcp.html');
$html_output_with_picture_img_lcp = file_get_contents(__DIR__ . '/HTML/output_w_picture_img_lcp.html');
$html_input_with_img_lcp = file_get_contents(__DIR__ . '/HTML/input_w_img_lcp.html');
$html_output_with_img_lcp = file_get_contents(__DIR__ . '/HTML/output_w_img_lcp.html');
$html_input_with_relative_img_lcp = file_get_contents(__DIR__ . '/HTML/input_with_relative_img_lcp.html');
$html_input_with_absolute_img_lcp = file_get_contents(__DIR__ . '/HTML/input_with_absolute_img_lcp.html');
$html_input_with_domain_img_lcp = file_get_contents(__DIR__ . '/HTML/input_lcp_image.html');
$html_output_with_beacon_and_lcp_opt = file_get_contents(__DIR__ . '/HTML/output_with_beacon_and_atf_opt.html');
$html_input_with_only_lrc_opt = file_get_contents(__DIR__ . '/HTML/input_with_only_lrc_opt.html');
$html_output_with_beacon_and_only_lrc_opt = file_get_contents(__DIR__ . '/HTML/output_with_beacon_and_only_lrc_opt.html');

$lrc = [
	'row' => [
		'status' => 'completed',
		'url' => 'http://example.org',
		'below_the_fold'      => json_encode( [
			'3ead51141d9876165378a22a92d90415',
			'eb156891061284662641900bc9136fae',
			'1b496e8f8129c0eb6eda409a2b17c24d',
		] ),
	],
];

return [
	'test_data' => [
		'shouldReturnOriginalWhenBypassAndRow' => [
			'config' => [
				'query_string' => 'nowprocket',
				'html' => $html_input,
				'atf' => [
					'row' => [
						'status' => 'completed',
						'url' => 'http://example.org',
						'lcp'      => json_encode( (object) [
							'type' => 'img',
							'src'  => 'http://example.org/wp-content/uploads/image.jpg',
						] ),
						'viewport' => json_encode( [
							0 => (object) [
								'type' => 'img',
								'src'  => 'http://example.org/wp-content/uploads/image2.jpg',
							],
						] ),
					],
				],
				'lrc' => $lrc,
			],
			'expected' => $html_input,
		],
		'shouldReturnOriginalWhenQueryString' => [
			'config' => [
				'query_string' => 'wpr_lazyrendercontent',
				'html' => $html_input,
				'atf' => [
					'row' => null,
				],
				'lrc' => [
					'row' => null,
				],
			],
			'expected' => $html_input,
		],
		'shouldReturnOriginalWhenDonotoptimize' => [
			'config' => [
				'donotrocketoptimize' => true,
				'sass_visit' => false,
				'html' => $html_input,
				'atf' => [
					'row' => null,
				],
				'lrc' => [
					'row' => null,
				],
			],
			'expected' => $html_input,
		],
		'shouldAddBeaconWhenDonotoptimizeAndSaaSVisit' => [
			'config' => [
				'donotrocketoptimize' => true,
				'sass_visit' => true,
				'html' => $html_input,
				'atf' => [
					'row' => null,
				],
				'lrc' => [
					'row' => null,
				],
			],
			'expected' => $html_output_with_beacon,
		],
		'shouldAddBeaconToPage' => [
			'config' => [
				'html' => $html_input,
				'atf' => [
					'row' => null,
				],
				'lrc' => [
					'row' => null,
				],
			],
			'expected' => $html_output_with_beacon,
		],
		'shouldAddBeaconToPageWithDefaultDelayWhenBadCustomDelay' => [
			'config' => [
				'html' => $html_input,
				'filter_delay' => 'string',
				'atf' => [
					'row' => null,
				],
				'lrc' => [
					'row' => null,
				],
			],
			'expected' => $html_output_with_beacon,
		],
		'shouldNotAddBeaconToPage' => [
			'config' => [
				'html' => $html_input,
				'atf' => [
					'row' => [
						'status' => 'completed',
						'url' => 'http://example.org',
						'lcp'      => json_encode( (object) [
							'type' => 'img',
							'src'  => 'http://example.org/wp-content/uploads/image.jpg',
						] ),
						'viewport' => json_encode( [
							0 => (object) [
								'type' => 'img',
								'src'  => 'http://example.org/wp-content/uploads/image2.jpg',
							],
						] ),
					],
				],
				'lrc' => $lrc,
			],
			'expected' => $html_output_with_preload,
		],
		'shoudNotAddBeaconToPageWhenLoggedInAndUserCacheEnabled' => [
			'config' => [
				'html' => $html_input,
				'atf' => [],
				'lrc' => [],
				'is_logged_in' => true,
				'user_cache_enabled' => 1,
			],
			'expected' => $html_output,
		],
		'shouldNotAddBeaconToPageWhenPerformanceHintsFailed' => [
			'config' => [
				'html' => $html_input,
				'atf' => [
					'row' => [
						'status' => 'failed',
						'url' => 'http://example.org',
						'lcp'      => json_encode( (object) [] ),
						'viewport' => json_encode( [] ),
					],
				],
				'lrc' => $lrc,
			],
			'expected' => $html_output,
		],
		'shouldPreloadLcpResponsiveImgset' => [
			'config' => [
				'html' => file_get_contents(__DIR__ . '/HTML/input_lcp_bg_responsive_imgset_template.php'),
				'atf' => [
					'row' => [
						'status' => 'completed',
						'url' => 'http://example.org',
						'lcp' => json_encode( (object) [
							'type' => 'bg-img-set',
							'bg_set'  => [
								['src' => "http://example.org/wp-content/rocket-test-data/images/lcp/testavif.avif 1dppx"],
								['src' => "http://example.org/wp-content/rocket-test-data/images/lcp/testwebp.webp 2dppx"]
							]
						]),
						'viewport' => json_encode ( [] ),
					],
				],
				'lrc' => $lrc,
			],
			'expected' => file_get_contents(__DIR__ . '/HTML/output_lcp_bg_responsive_imgset_template.php'),
		],
		'shouldPreloadLcpResponsiveWebkit' => [
			'config' => [
				'html' => file_get_contents(__DIR__ . '/HTML/input_lcp_bg_responsive_webkit_template.php'),
				'atf' => [
					'row' => [
						'status' => 'completed',
						'url' => 'http://example.org',
						'lcp' => json_encode( (object) [
							'type' => 'bg-img-set',
							'bg_set'  => [
								['src' => "https://fastly.picsum.photos/id/976/200/300.jpg?hmac=s1Uz9fgJv32r8elfaIYn7pLpQXps7X9oYNwC5XirhO8 1dppx"],
								['src' => "https://rocketlabsqa.ovh/wp-content/rocket-test-data/images/fixtheissue.jpg 2dppx"]
							]
						]),
						'viewport' => json_encode ( [] ),
					],
				],
				'lrc' => $lrc,
			],
			'expected' => file_get_contents(__DIR__ . '/HTML/output_lcp_bg_responsive_webkit_template.php'),
		],
		'shouldPreloadLcpLayeredBackground' => [
			'config' => [
				'html' => file_get_contents(__DIR__ . '/HTML/input_lcp_layered_bg.php'),
				'atf' => [
					'row' => [
						'status' => 'completed',
						'url' => 'http://example.org',
						'lcp' => json_encode( (object) [
							'type' => 'bg-img',
							'bg_set'  => [
								['src' => "https://fastly.picsum.photos/id/976/200/300.jpg?hmac=s1Uz9fgJv32r8elfaIYn7pLpQXps7X9oYNwC5XirhO8"],
								['src' => "https://rocketlabsqa.ovh/wp-content/rocket-test-data/images/fixtheissue.jpg"]
							]
						]),
						'viewport' => json_encode ( [] ),
					],
				],
				'lrc' => $lrc,
			],
			'expected' => file_get_contents(__DIR__ . '/HTML/output_lcp_layered_bg.php'),
		],
		'shouldPreloadLcpSingleBackground' => [
			'config' => [
				'html' => file_get_contents(__DIR__ . '/HTML/input_lcp_single_bg.php'),
				'atf' => [
					'row' => [
						'status' => 'completed',
						'url' => 'http://example.org',
						'lcp' => json_encode( (object) [
							'type' => 'bg-img',
							'bg_set'  => [
								['src' => "http://example.org/wp-content/rocket-test-data/images/lcp/testavif.avif"],
							]
						]),
						'viewport' => json_encode ( [] ),
					],
				],
				'lrc' => $lrc,
			],
			'expected' => file_get_contents(__DIR__ . '/HTML/output_lcp_single_bg.php'),
		],
		'shouldPreloadLcpResponsiveImage' => [
			'config' => [
				'html' => file_get_contents(__DIR__ . '/HTML/input_lcp_responsive.php'),
				'atf' => [
					'row' => [
						'status' => 'completed',
						'url' => 'http://example.org',
						'lcp' => json_encode( (object) [
							'type' => 'img-srcset',
							'src' => 'wolf.jpg',
							"srcset" => "wolf_400px.jpg 400w, wolf_800px.jpg 800w, wolf_1600px.jpg 1600w",
							"sizes" => "50vw",
						]),
						'viewport' => json_encode ( [] ),
					],
				],
				'lrc' => $lrc,
			],
			'expected' => file_get_contents(__DIR__ . '/HTML/output_lcp_responsive.php'),
		],
		'shouldApplyFetchPriorityToReturnRelativeImage' => [
			'config' => [
				'html' => $html_input_with_relative_img_lcp,
				'atf' => [
					'row' => [
						'status' => 'completed',
						'url' => 'http://example.org',
						'lcp'      => json_encode( (object) [
							'type' => 'img',
							'src'  => 'http://example.org/wp-content/uploads/sample_relative_image.jpg',
						] ),
						'viewport' => json_encode ( [] ),
					],
				],
				'lrc' => $lrc,
			],
			'expected' => file_get_contents(__DIR__ . '/HTML/output_with_relative_img_lcp.php'),
		],
		'shouldApplyFetchPriorityToAbsoluteImage' => [
			'config' => [
				'html' => $html_input_with_absolute_img_lcp,
				'atf' => [
					'row' => [
						'status' => 'completed',
						'url' => 'http://example.org',
						'lcp'      => json_encode( (object) [
							'type' => 'img',
							'src'  => 'http://example.com/wp-content/uploads/sample_absolute_image.jpg',
						] ),
						'viewport' => json_encode ( [] ),
					],
				],
				'lrc' => $lrc,
			],
			'expected' => file_get_contents(__DIR__ . '/HTML/output_with_absolute_img_lcp.php'),
		],
		'shouldApplyFetchPriorityToImageWithDomain' => [
			'config' => [
				'html' => $html_input_with_domain_img_lcp,
				'atf' => [
					'row' => [
						'status' => 'completed',
						'url' => 'http://example.org',
						'lcp'      => json_encode( (object) [
							'type' => 'img',
							'src'  => 'http://example.org/wp-content/uploads/sample_url_image.png',
						] ),
						'viewport' => json_encode ( [] ),
					],
				],
				'lrc' => $lrc,
			],
			'expected' => file_get_contents(__DIR__ . '/HTML/output_lcp_image.php'),
		],
		'shouldNotApplyFetchPriorityToImageWithFetchpriority' => [
			'config' => [
				'html' => file_get_contents(__DIR__ . '/HTML/input_lcp_with_fetchpriority.html'),
				'atf' => [
					'row' => [
						'status' => 'completed',
						'url' => 'http://example.org',
						'lcp'      => json_encode( (object) [
							'type' => 'img',
							'src'  => 'http://example.org/wp-content/uploads/sample_relative_image.jpg',
						] ),
						'viewport' => json_encode ( [] ),
					],
				],
				'lrc' => $lrc,
			],
			'expected' => file_get_contents(__DIR__ . '/HTML/output_lcp_with_fetchpriority.html'),
		],
		'shouldNotApplyFetchPriorityToImageWithDuplicateMarkup' => [
			'config' => [
				'html' => file_get_contents(__DIR__ . '/HTML/input_lcp_with_markup_comment.html'),
				'atf' => [
					'row' => [
						'status' => 'completed',
						'url' => 'http://example.org',
						'lcp'      => json_encode( (object) [
							'type' => 'img',
							'src'  => 'http://example.org/wp-content/uploads/sample_relative_image.jpg',
						] ),
						'viewport' => json_encode ( [] ),
					],
				],
				'lrc' => $lrc,
			],
			'expected' => file_get_contents(__DIR__ . '/HTML/output_lcp_with_markup_comment.html'),
		],
		'shouldNotApplyFetchPriorityToTheWrongElement' => [
			'config' => [
				'html' => $html_input_with_bg_image_lcp,
				'atf' => [
					'row' => [
						'status' => 'completed',
						'url' => 'http://example.org',
						'lcp'      => json_encode( (object) [
							'type' => 'img',
							'src'  => 'http://example.org/wp-content/uploads/image.jpg',
						] ),
						'viewport' => json_encode( [
							0 => (object) [
								'type' => 'img',
								'src'  => 'http://example.org/wp-content/uploads/image2.jpg',
							],
							1 => (object) [
								'type' => 'img',
								'src'  => 'http://example.org/wp-content/uploads/image3.jpg',
							],
						] ),
					],
				],
				'lrc' =>$lrc,
			],
			'expected' => $html_output_with_bg_image_lcp,
		],
		'shouldApplyFetchPriorityToTheImgTagWithPictureElement' => [
			'config' => [
				'html' => $html_input_with_picture_img_lcp,
				'atf' => [
					'row' => [
						'status' => 'completed',
						'url' => 'http://example.org',
						'lcp'      => json_encode( (object) [
							'type' => 'img',
							'src'  => 'http://example.org/wp-content/uploads/image.jpg',
						] ),
						'viewport' => json_encode( [
							0 => (object) [
								'type' => 'img',
								'src'  => 'http://example.org/wp-content/uploads/image2.jpg',
							],
							1 => (object) [
								'type' => 'img',
								'src'  => 'http://example.org/wp-content/uploads/image3.jpg',
							],
						] ),
					],
				],
				'lrc' => $lrc,
			],
			'expected' => $html_output_with_picture_img_lcp,
		],
		'shouldApplyFetchPriorityToTheImgElement' => [
			'config' => [
				'html' => $html_input_with_img_lcp,
				'atf' => [
					'row' => [
						'status' => 'completed',
						'url' => 'http://example.org',
						'lcp'      => json_encode( (object) [
							'type' => 'img',
							'src'  => 'http://example.org/wp-content/uploads/image.jpg',
						] ),
						'viewport' => json_encode( [
							0 => (object) [
								'type' => 'img',
								'src'  => 'http://example.org/wp-content/uploads/image2.jpg',
							],
							1 => (object) [
								'type' => 'img',
								'src'  => 'http://example.org/wp-content/uploads/image3.jpg',
							],
						] ),
					],
				],
				'lrc' => $lrc,
			],
			'expected' => $html_output_with_img_lcp,
		],
		'shouldNotDoAnythingIfNoPerformanceHintsCandidates' => [
			'config' => [
				'html' => $html_input,
				'atf' => [
					'row' => [
						'status' => 'completed',
						'url' => 'http://example.org',
						'lcp'      => 'not found',
						'viewport' => json_encode( [
						] ),
					],
				],
				'lrc' => $lrc,
			],
			'expected' => $html_output,
		],
		'shouldPreloadPictureTag1' => [
			'config' => [
				'html' => file_get_contents(__DIR__ . '/HTML/input_lcp_picture.php'),
				'atf' => [
					'row' => [
						'status' => 'completed',
						'url' => 'http://example.org',
						'lcp' => json_encode( (object) [
							'type' => 'picture',
							'src' => 'large_cat.jpg',
							'sources' => [
								[
									'srcset' => 'small_cat.jpg',
									'media' => '(max-width: 400px)',
									'type'  => '',
									'sizes' => '',
								],
								[
									'srcset' => 'medium_cat.jpg',
									'media' => '(max-width: 800px)',
									'type'  => '',
									'sizes' => '',
								]
							]
						]),
						'viewport' => json_encode ( [] ),
					],
				],
				'lrc' => $lrc,
			],
			'expected' => file_get_contents(__DIR__ . '/HTML/output_lcp_picture.php'),
		],
		'shouldPreloadPictureTag2' => [
			'config' => [
				'html' => file_get_contents(__DIR__ . '/HTML/input_lcp_picture_2.php'),
				'atf' => [
					'row' => [
						'status' => 'completed',
						'url' => 'http://example.org',
						'lcp' => json_encode( (object) [
							'type' => 'picture',
							'src' => '',
							'sources' => [
								[
									'srcset' => 'https://imagify.rocketlabsqa.ovh/wp-content/uploads/2024/05/home-new-bg-free-img-—-kopia-1024x576.jpg.avif 1024w, https://imagify.rocketlabsqa.ovh/wp-content/uploads/2024/05/home-new-bg-free-img-—-kopia-300x169.jpg.avif 300w, https://imagify.rocketlabsqa.ovh/wp-content/uploads/2024/05/home-new-bg-free-img-—-kopia-768x432.jpg.avif 768w, https://imagify.rocketlabsqa.ovh/wp-content/uploads/2024/05/home-new-bg-free-img-—-kopia-1536x864.jpg.avif 1536w, https://imagify.rocketlabsqa.ovh/wp-content/uploads/2024/05/home-new-bg-free-img-—-kopia-1200x675.jpg.avif 1200w, https://imagify.rocketlabsqa.ovh/wp-content/uploads/2024/05/home-new-bg-free-img-—-kopia-600x338.jpg.avif 600w, https://imagify.rocketlabsqa.ovh/wp-content/uploads/2024/05/home-new-bg-free-img-—-kopia.jpg.avif 1920w',
									'media' => '',
									'type' => 'image/avif',
									'sizes' => '(max-width: 1024px) 100vw, 1024px'
								],
								[
									'srcset' => 'https://imagify.rocketlabsqa.ovh/wp-content/uploads/2024/05/home-new-bg-free-img-—-kopia-1024x576.jpg.webp 1024w, https://imagify.rocketlabsqa.ovh/wp-content/uploads/2024/05/home-new-bg-free-img-—-kopia-300x169.jpg.webp 300w, https://imagify.rocketlabsqa.ovh/wp-content/uploads/2024/05/home-new-bg-free-img-—-kopia-768x432.jpg.webp 768w, https://imagify.rocketlabsqa.ovh/wp-content/uploads/2024/05/home-new-bg-free-img-—-kopia-1536x864.jpg.webp 1536w, https://imagify.rocketlabsqa.ovh/wp-content/uploads/2024/05/home-new-bg-free-img-—-kopia-1200x675.jpg.webp 1200w, https://imagify.rocketlabsqa.ovh/wp-content/uploads/2024/05/home-new-bg-free-img-—-kopia-600x338.jpg.webp 600w',
									'media' => '',
									'type' => 'image/webp',
									'sizes' => '(max-width: 1024px) 100vw, 1024px'
								]
							]
						]),
						'viewport' => json_encode ( [] ),
					],
				],
				'lrc' => $lrc,
			],
			'expected' => file_get_contents(__DIR__ . '/HTML/output_lcp_picture_2.php'),
		],
		'shouldPreloadPictureTag3' => [
			'config' => [
				'html' => file_get_contents(__DIR__ . '/HTML/input_lcp_picture_3.php'),
				'atf' => [
					'row' => [
						'status' => 'completed',
						'url' => 'http://example.org',
						'lcp' => json_encode( (object) [
							'type' => 'picture',
							'src' => 'https://imagify.rocketlabsqa.ovh/wp-content/uploads/2024/05/home-new-bg-free-img-%E2%80%94-kopia-1024x576.jpg',
							'sources' => [
								[
									'srcset' => 'https://imagify.rocketlabsqa.ovh/wp-content/uploads/2024/05/home-new-bg-free-img-—-kopia-1024x576.jpg.avif 1024w, https://imagify.rocketlabsqa.ovh/wp-content/uploads/2024/05/home-new-bg-free-img-—-kopia-300x169.jpg.avif 300w, https://imagify.rocketlabsqa.ovh/wp-content/uploads/2024/05/home-new-bg-free-img-—-kopia-768x432.jpg.avif 768w, https://imagify.rocketlabsqa.ovh/wp-content/uploads/2024/05/home-new-bg-free-img-—-kopia-1536x864.jpg.avif 1536w, https://imagify.rocketlabsqa.ovh/wp-content/uploads/2024/05/home-new-bg-free-img-—-kopia-1200x675.jpg.avif 1200w, https://imagify.rocketlabsqa.ovh/wp-content/uploads/2024/05/home-new-bg-free-img-—-kopia-600x338.jpg.avif 600w, https://imagify.rocketlabsqa.ovh/wp-content/uploads/2024/05/home-new-bg-free-img-—-kopia.jpg.avif 1920w',
									'media' => '',
									'type' => 'image/avif',
									'sizes' => '(max-width: 1024px) 100vw, 1024px'
								],
								[
									'srcset' => 'https://imagify.rocketlabsqa.ovh/wp-content/uploads/2024/05/home-new-bg-free-img-—-kopia-1024x576.jpg.webp 1024w, https://imagify.rocketlabsqa.ovh/wp-content/uploads/2024/05/home-new-bg-free-img-—-kopia-300x169.jpg.webp 300w, https://imagify.rocketlabsqa.ovh/wp-content/uploads/2024/05/home-new-bg-free-img-—-kopia-768x432.jpg.webp 768w, https://imagify.rocketlabsqa.ovh/wp-content/uploads/2024/05/home-new-bg-free-img-—-kopia-1536x864.jpg.webp 1536w, https://imagify.rocketlabsqa.ovh/wp-content/uploads/2024/05/home-new-bg-free-img-—-kopia-1200x675.jpg.webp 1200w, https://imagify.rocketlabsqa.ovh/wp-content/uploads/2024/05/home-new-bg-free-img-—-kopia-600x338.jpg.webp 600w',
									'media' => '',
									'type' => 'image/webp',
									'sizes' => '(max-width: 1024px) 100vw, 1024px'
								]
							]
						]),
						'viewport' => json_encode ( [] ),
					],
				],
				'lrc' => $lrc,
			],
			'expected' => file_get_contents(__DIR__ . '/HTML/output_lcp_picture_3.php'),
		],
		'shouldPreloadPictureTag4' => [
			'config' => [
				'html' => file_get_contents(__DIR__ . '/HTML/input_lcp_picture_4.php'),
				'atf' => [
					'row' => [
						'status' => 'completed',
						'url' => 'http://example.org',
						'lcp' => json_encode( (object) [
							'type' => 'picture',
							'src' => 'https://variance.pl/wp-content/uploads/2024/05/Kwiatowy-Ksiezyc-1348x900.webp',
							'sources' => [
								[
									'srcset' => 'https://variance.pl/wp-content/uploads/2024/05/Kwiatowy-Ksiezyc-400x600.webp',
									'media' => '(max-width: 500px)',
									'type' => 'image/webp',
								],
								[
									'srcset' => 'https://variance.pl/wp-content/uploads/2024/05/Kwiatowy-Ksiezyc-768x513.webp',
									'media' => '(min-width: 501px) and (max-width: 768px)',
									'type' => 'image/webp',
								]
							]
						]),
						'viewport' => json_encode ( [] ),
					],
				],
				'lrc' => $lrc,
			],
			'expected' => file_get_contents(__DIR__ . '/HTML/output_lcp_picture_4.php'),
		],
		'ShouldAddBeaconToPageWhenOnlyLcpIsInDb' => [
			'config' => [
				'html' => $html_input_with_domain_img_lcp,
				'atf' => [
					'row' => [
						'status' => 'completed',
						'url' => 'http://example.org',
						'lcp'      => json_encode( (object) [
							'type' => 'img',
							'src'  => 'http://example.org/wp-content/uploads/sample_url_image.png',
						] ),
						'viewport' => json_encode ( [] ),
					],
				],
				'lrc' => [
					'row' => null,
				],
			],
			'expected' => $html_output_with_beacon_and_lcp_opt,
		],
		'ShouldAddBeaconToPageWhenOnlyLrcIsInDb' => [
			'config' => [
				'html' => $html_input_with_only_lrc_opt,
				'atf' => [
					'row' => null,
				],
				'lrc' => $lrc,
			],
			'expected' => $html_output_with_beacon_and_only_lrc_opt,
		],
		'shouldNotDuplicateBeaconOnAPage' => [
			'config' => [
				'html' => $html_with_double_body,
				'atf' => [
					'row' => null,
				],
				'lrc' => [
					'row' => null,
				],
			],
			'expected' => $html_with_double_body_output,
		],
		'shouldAddBeaconWithoutClosingBodyTag' => [
			'config' => [
				'html' => $html_input_without_closing_body_tag,
				'atf' => [
					'row' => null,
				],
				'lrc' => [
					'row' => null,
				],
			],
			'expected' => $html_input_without_closing_body_tag_output,
		],
	],
];
