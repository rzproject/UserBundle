INSERT INTO `classification__context` (`id`, `name`, `enabled`, `created_at`, `updated_at`)
VALUES
	('user-age-demographics', 'User Age Demographics', 1, '2016-07-22 11:40:25', '2016-07-22 11:40:25');


INSERT INTO `classification__collection` (`context`, `media_id`, `name`, `enabled`, `slug`, `description`, `created_at`, `updated_at`, `settings`)
VALUES
	('user-age-demographics', NULL, '12-17 years old', 1, '12-17-years-old', '12-17 years old', '2015-06-22 14:26:50', '2015-06-22 14:26:50', '{\"min\":12,\"max\":17}'),
	('user-age-demographics', NULL, '18-24 years old', 1, '18-24-years-old', '18-24 years old', '2015-06-22 14:27:02', '2015-06-22 14:27:02', '{\"min\":18,\"max\":24}'),
	('user-age-demographics', NULL, '25-34 years old', 1, '25-34-years-old', '25-34 years old', '2015-06-22 14:27:18', '2015-06-22 14:27:18', '{\"min\":25,\"max\":34}'),
	('user-age-demographics', NULL, '35-44 years old', 1, '35-44-years-old', '35-44 years old', '2015-06-22 14:27:36', '2015-06-22 14:27:36', '{\"min\":35,\"max\":44}'),
	('user-age-demographics', NULL, '45-54 years old', 1, '45-54-years-old', '45-54 years old', '2015-06-22 14:27:54', '2015-06-22 14:27:54', '{\"min\":45,\"max\":54}'),
	('user-age-demographics', NULL, '55-64 years old', 1, '55-64-years-old', '55-64 years old', '2015-06-22 14:28:13', '2015-06-22 14:28:13', '{\"min\":55,\"max\":64}'),
	('user-age-demographics', NULL, '65-74 years old', 1, '65-74-years-old', '65-74 years old', '2015-06-22 14:28:27', '2015-06-22 14:28:27', '{\"min\":66,\"max\":74}'),
	('user-age-demographics', NULL, 'Under 12 years old', 1, 'under-12-years-old', 'Under 12 years old', '2015-06-22 14:28:45', '2015-06-22 14:28:45', '{\"min\":null,\"max\":11}'),
	('user-age-demographics', NULL, '75 years or older', 1, '75-years-or-older', '75 years or older', '2015-06-22 14:29:00', '2015-06-22 14:29:00', '{\"min\":75,\"max\":null}');
