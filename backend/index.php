<?php
//error_reporting(E_ALL);

require_once 'inc/config.php';
require_once 'vendor/autoload.php';
require_once 'inc/dbHandler.php';

$app = new \Slim\Slim();


// -----------------------------------------------------------------------------
// Define the database connection handler as singleton
// -----------------------------------------------------------------------------
$app->container->singleton('db', function () {
	return new DbHandler();
});

// -----------------------------------------------------------------------------
// Get function to gain all current data
// -----------------------------------------------------------------------------
$app->get('/', function() use($app) {
	$app->response->setStatus(200);
	$app->response->headers->set('Content-Type', 'application/json');

	echo json_encode($app->db->getCurrentEvaluationData());
});

$app->get('/user', function() use($app) {

	$data = $app->db->getAllUsers();

	$result = array();

	$result[] = array(
		'Email',
		'Weitere Teilnahme',
		'Experiment Umgebung',
		'Kommentar'
	);


	foreach ($data as $value)
	{
		$result[] = array(
			$value['email'],
			$value['participate_in_other'],
			$value['location'],
			$value['comments']
		);
	}


	$file = fopen('data/user.csv', 'w');

	foreach ($result as $line)
	{
		fputcsv($file, $line);
	}

	fclose($file);


	header('Content-type: text/csv');
	header('Content-Disposition: attachment; filename="user.csv"');

	readfile('data/user.csv');
});


$app->get('/csv', function() use($app) {
	$data = $app->db->getCurrentEvaluationData();

	$result = array();
	$result[] = array(
		'ip',
		'Date',
		'VpID',  			// Participant ID
		'DO',    			// Dropout
		'ExpU',				// Location
		'ExpG',				// Group

		'PosExpBA',			// task_pos
		'RaP-GeOp-ExpBA',	// chosen_option_rank
		'BEz-ExpBA',		// time_to_decision
		'SuZs-ExpBA-Q1',	// stress_question q_num_1
		'SuZs-ExpBA-Q2',	// stress_question q_num_2

		'PosExpBB',			// task_pos
		'RaP-GeOp-ExpBB',	// chosen_option_rank
		'BEz-ExpBB',		// time_to_decision
		'SuZs-ExpBB-Q1',	// stress_question q_num_1
		'SuZs-ExpBB-Q2',	// stress_question q_num_2

		'PosExpBC',			// task_pos
		'RaP-GeOp-ExpBC',	// chosen_option_rank
		'BEz-ExpBC',		// time_to_decision
		'SuZs-ExpBC-Q1',	// stress_question q_num_1
		'SuZs-ExpBC-Q2',	// stress_question q_num_2

		'MaxT',				// maximising_sum

		'Alter',
		'Bildung',
		'Geschlecht',
		'Gesamtzeit'
	);



	foreach ($data as $value) {
		$expA = array(
			'task_pos'           => '#',
			'chosen_option_rank' => '#',
			'time_to_decision'   => '#',
			'q_num_1'            => '#',
			'q_num_2'            => '#',
		);
		if (isset($value['experiments'][0])) { $expA = $value['experiments'][0]; }

		$expB = array(
			'task_pos'           => '#',
			'chosen_option_rank' => '#',
			'time_to_decision'   => '#',
			'q_num_1'            => '#',
			'q_num_2'            => '#',
		);
		if (isset($value['experiments'][1])) { $expB = $value['experiments'][1]; }

		$expC = array(
			'task_pos'           => '#',
			'chosen_option_rank' => '#',
			'time_to_decision'   => '#',
			'q_num_1'            => '#',
			'q_num_2'            => '#',
		);
		if (isset($value['experiments'][2])) { $expC = $value['experiments'][2]; }

		$maximisingSum = 0;
		if (isset($value['maximising'][0])) { $maximisingSum = $value['maximising'][0]['q_sum']; }

		$age = '#';
		if (isset($value['participant']['age'])) { $age = $value['participant']['age']; }

		$graduation = '#';
		if (isset($value['participant']['graduation'])) { $graduation = $value['participant']['graduation']; }

		$gender = '#';
		if (isset($value['participant']['gender'])) { $gender = $value['participant']['gender']; }

		$totalTime = '#';
		if (isset($value['participant']['total_time'])) { $totalTime = $value['participant']['total_time']; }


		$result[] = array(
			$value['participant']['ip_address'],
			$value['participant']['participated_at'],
			$value['participant']['participation_id'],
			$value['participant']['dropout'],
			$value['participant']['location'],
			$value['participant']['participation_group'],

			$expA['task_pos'],
			$expA['chosen_option_rank'],
			$expA['time_to_decision'],
			$expA['q_num_1'],
			$expA['q_num_2'],

			$expB['task_pos'],
			$expB['chosen_option_rank'],
			$expB['time_to_decision'],
			$expB['q_num_1'],
			$expB['q_num_2'],

			$expC['task_pos'],
			$expC['chosen_option_rank'],
			$expC['time_to_decision'],
			$expC['q_num_1'],
			$expC['q_num_2'],

			$maximisingSum,

			$age,
			$graduation,
			$gender,
			$totalTime
		);
	}

	$file = fopen('data/data.csv', 'w');

	foreach ($result as $line)
	{
		fputcsv($file, $line);
	}

	fclose($file);


	header('Content-type: text/csv');
	header('Content-Disposition: attachment; filename="data.csv"');

	readfile('data/data.csv');
});

// -----------------------------------------------------------------------------
// Execute the application
// -----------------------------------------------------------------------------
$app->run();
