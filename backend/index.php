<?php
//error_reporting(E_ALL);

require_once 'inc/config.php';
require_once 'vendor/autoload.php';
require_once 'inc/dbHandler.php';

$app = new \Slim\Slim(array(
    'templates.path' => './templates'
));


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

    $generalData = array(
        'numberOfParticipants'         => $app->db->getNumberOfParticipants(),
        'numberOfPreviousParticipants' => $app->db->getNumberOfPreviousParticipants(),
        'numberOfDropOuts'             => $app->db->getDropOuts(),
        'averageTotalTime'             => $app->db->getAverageTotalTime(),
        'averageMaximising'            => $app->db->getAverageMaximising(),
        'genderShare'                  => $app->db->getGenderShare(),
        'averageAge'                   => $app->db->getAverageAge()
    );

    // Get data per strategy
    $strategies   = array('lex', 'eba', 'eqw', 'wadd');
    $strategyData = array();

    foreach($strategies as $strategy)
    {
        $strategyData[$strategy] = $app->db->getDataByStrategy($strategy);
    }

    $userStats = $app->db->getUserStats();

    $app->render('dashboard.php', array(
        'general'  => $generalData,
        'strategy' => $strategyData,
        'user'     => $userStats
    ), 200);
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
		fputcsv($file, $line, ';');
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
        'Gerät',            // device
		'VpN',  			// Participant ID
        'Spende',
        'Vorstudie',
		'DO',    			// Dropout
        'Gewicht',
        'ExpI',             // Strategy
		'ExpG',				// Group

		'Position-ExpBA',	     // task_pos
        'Position-Option-ExpBA', // chosen_option_position
		'Rang-Option-ExpBA',	 // chosen_option_rank
		'B-Zeit-ExpBA',		     // time_to_decision
		'V-Zeit-ExpBA',	         // Verfügbare Zeit
		'Zufriedenheit-ExpBA',	 // satisfaction questions
        'Stress-ExpBA',          // Stress questions
        'Entscheidung-Vorgabe',  // decision by guideline

        'Position-ExpBB',	     // task_pos
        'Position-Option-ExpBB', // chosen_option_position
        'Rang-Option-ExpBB',	 // chosen_option_rank
        'B-Zeit-ExpBB',		     // time_to_decision
        'V-Zeit-ExpBB',	         // Verfügbare Zeit
        'Zufriedenheit-ExpBB',	 // satisfaction questions
        'Stress-ExpBB',          // Stress questions
        'Entscheidung-Vorgabe',  // decision by guideline

        'Position-ExpBC',	     // task_pos
        'Position-Option-ExpBC', // chosen_option_position
        'Rang-Option-ExpBC',	 // chosen_option_rank
        'B-Zeit-ExpBC',		     // time_to_decision
        'V-Zeit-ExpBC',	         // Verfügbare Zeit
        'Zufriedenheit-ExpBC',	 // satisfaction questions
        'Stress-ExpBC',          // Stress questions
        'Entscheidung-Vorgabe',  // decision by guideline

		'MaxT',				// maximising_sum

        'Störung',
        'Ernsthaftigkeit',
        'Interesse',
        'Hilfsmittel',
        'Kopfrechnen',
        'Merkfähigkeit',


		'Alter',
		'Bildung',
        'Beruf',
		'Geschlecht',
		'Gesamtzeit'
	);



	foreach ($data as $value) {
		$expA = array(
			'task_pos'               => '#',
            'chosen_option_position' => '#',
			'chosen_option_rank'     => '#',
			'time_to_decision'       => '#',
			'satisfaction_sum'       => '#',
            'stress_sum'             => '#',
            'by_guide_line'          => '#'
		);
		if (isset($value['experiments'][0])) { $expA = $value['experiments'][0]; }

		$expB = array(
            'task_pos'               => '#',
            'chosen_option_position' => '#',
            'chosen_option_rank'     => '#',
            'time_to_decision'       => '#',
            'satisfaction_sum'       => '#',
            'stress_sum'             => '#',
            'by_guide_line'          => '#'
		);
		if (isset($value['experiments'][1])) { $expB = $value['experiments'][1]; }

		$expC = array(
            'task_pos'               => '#',
            'chosen_option_position' => '#',
            'chosen_option_rank'     => '#',
            'time_to_decision'       => '#',
            'satisfaction_sum'       => '#',
            'stress_sum'             => '#',
            'by_guide_line'          => '#'
		);
		if (isset($value['experiments'][2])) { $expC = $value['experiments'][2]; }

		$maximisingSum = 0;
		if (isset($value['maximising'][0])) { $maximisingSum = $value['maximising'][0]['q_sum']; }

		$age = '#';
		if (isset($value['demographic']['age'])) { $age = $value['demographic']['age']; }

		$graduation = '#';
		if (isset($value['demographic']['graduation'])) { $graduation = $value['demographic']['graduation']; }

		$gender = '#';
		if (isset($value['demographic']['gender'])) { $gender = $value['demographic']['gender']; }

        $job = '#';
        if (isset($value['demographic']['live_status'])) { $gender = $value['demographic']['live_status']; }

		$totalTime = '#';
		if (isset($value['participant']['total_time'])) { $totalTime = $value['participant']['total_time']; }

        // Check the additional data
        $disturbed = '#';
        if (isset($value['additional']['disturbed'])) { $disturbed = $value['additional']['disturbed']; }

        $seriousness = '#';
        if (isset($value['additional']['seriousness'])) { $seriousness = $value['additional']['seriousness']; }

        $interest = '#';
        if (isset($value['additional']['interest'])) { $interest = $value['additional']['interest']; }

        $additionalTools = '#';
        if (isset($value['additional']['additional_tools'])) { $additionalTools = $value['additional']['additional_tools']; }

        $mentalArithmetic = '#';
        if (isset($value['additional']['mental_arithmetic'])) { $mentalArithmetic = $value['additional']['mental_arithmetic']; }

        $mentalRetention = '#';
        if (isset($value['additional']['mental_retention'])) { $mentalRetention = $value['additional']['mental_retention']; }

        $device = '#';
        if (isset($value['demographic']['device'])) { $device = $value['demographic']['device']; }

        $attributes = '#';
        if (isset($value['attributes']['q_sum'])) { $attributes = $value['attributes']['q_sum']; }


		$result[] = array(
			$value['participant']['ip_address'],
			$value['participant']['participated_at'],
            $device,
			$value['participant']['participation_id'],
            $value['participant']['donation_organization_id'],
            $value['participant']['previous_participant'],
			$value['participant']['dropout'],
            $attributes,
            $value['participant']['Participation_condition'],
			$value['participant']['participation_group'],

			$expA['task_pos'],
            $expA['chosen_option_position'],
			$expA['chosen_option_rank'],
			$expA['time_to_decision'],
            11500,
			$expA['satisfaction_sum'],
			$expA['stress_sum'],
            $expA['by_guide_line'],

            $expB['task_pos'],
            $expB['chosen_option_position'],
            $expB['chosen_option_rank'],
            $expB['time_to_decision'],
            14000,
            $expB['satisfaction_sum'],
            $expB['stress_sum'],
            $expB['by_guide_line'],

            $expC['task_pos'],
            $expC['chosen_option_position'],
            $expC['chosen_option_rank'],
            $expC['time_to_decision'],
            19500,
            $expC['satisfaction_sum'],
            $expC['stress_sum'],
            $expC['by_guide_line'],

			$maximisingSum,

            $disturbed,
            $seriousness,
            $interest,
            $additionalTools,
            $mentalArithmetic,
            $mentalRetention,

			$age,
			$graduation,
            $job,
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
