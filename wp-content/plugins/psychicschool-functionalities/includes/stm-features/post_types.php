<?php
namespace PS\STM_Features;

add_action('plugins_loaded', function () {
	// Testimonial
	STM_PostType::registerPostType('testimonial', __('Testimonial', 'psychicschool-functionalities'),
		[
			'menu_icon' => 'dashicons-testimonial',
			'supports' => ['title', 'excerpt', 'thumbnail'],
			'exclude_from_search' => true,
			'publicly_queryable' => false,
		]
	);

	// Teachers post type
	STM_PostType::registerPostType('teachers', __('Teachers', 'psychicschool-functionalities'),
		[
			'pluralTitle' => __('Teachers', 'psychicschool-functionalities'),
			'menu_icon' => 'dashicons-awards',
			'supports' => ['title', 'editor', 'thumbnail', 'comments', 'excerpt'],
			'rewrite' => ['slug' => 'teachers'],
		]
	);

	// Get experts and list them in dropdown woo products
	add_action('admin_init', 'ps_expert_list');

	function ps_expert_list()
	{
		if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
			$experts = [
				'no_expert' => 'Choose teacher for course',
			];

			$experts_args = [
				'post_type' => 'teachers',
				'post_status' => 'publish',
				'posts_per_page' => -1,
			];

			$experts_query = new \WP_Query($experts_args);

			foreach ($experts_query->posts as $expert) {
				$experts[$expert->ID] = $expert->post_title;
			}

			if (!empty($experts)) {
				STM_PostType::addMetaBox('stm_woo_product_expert',
					__('Course Teacher', 'psychicschool-functionalities'), ['product'], '', '', '',
					[
						'fields' => [
							'course_expert' => [
								'label' => __('Teacher', 'psychicschool-functionalities'),
								'type' => 'multi-select',
								'options' => $experts,
								'description' => 'Choose Teacher for course',
							],
						],
					]
				);
			}

			STM_PostType::addMetaBox('stm_woo_product_status',
				__('Course Details', 'psychicschool-functionalities'), ['product'], '', '', '',
				[
					'fields' => [
						'course_status' => [
							'label' => __('Status', 'psychicschool-functionalities'),
							'type' => 'select',
							'options' => [
								'no_status' => __('No Status', 'psychicschool-functionalities'),
								'hot' => __('Hot', 'psychicschool-functionalities'),
								'special' => __('Special', 'psychicschool-functionalities'),
								'new' => __('New', 'psychicschool-functionalities'),
							],
						],
						'duration' => [
							'label' => __('Duration', 'psychicschool-functionalities'),
							'type' => 'text',
						],
						'lectures' => [
							'label' => __('Lectures', 'psychicschool-functionalities'),
							'type' => 'text',
						],
						'video' => [
							'label' => __('Video', 'psychicschool-functionalities'),
							'type' => 'text',
						],
						'certificate' => [
							'label' => __('Certificate', 'psychicschool-functionalities'),
							'type' => 'text',
						],
					],
				]
			);
		}
	}

	STM_PostType::addMetaBox('testimonial_info', __('Testimonial Info', 'psychicschool-functionalities'),
		['testimonial'], '', '', '', [
			'fields' => [
				'testimonial_user' => [
					'label' => __('Name', 'psychicschool-functionalities'),
					'type' => 'text',
				],
				'testimonial_profession' => [
					'label' => __('Profession', 'psychicschool-functionalities'),
					'type' => 'text',
				],
			],
		]);

	STM_PostType::addMetaBox('expert_info', __('Expert Info', 'psychicschool-functionalities'), ['teachers'], '',
		'', '', [
			'fields' => [
				'expert_sphere' => [
					'label' => __('Teacher Sphere', 'psychicschool-functionalities'),
					'type' => 'text',
				],
				'expert_certified' => [
					'label' => __('Teacher Certified By', 'psychicschool-functionalities'),
					'type' => 'text',
				],
			],
		]);

	// Media library upload script for fields
	add_action('admin_enqueue_scripts', function() {
		wp_enqueue_media();
	});
});
