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
        'ExpI',             // Strategy
		'ExpG',				// Group

        'Gewicht 1',
        'Gewicht 2',
        'Gewicht 3',
        'Gewicht 4',
        'Gewicht 5',
        'Gewicht 6',
        'Gewicht 7',
        'Gewicht-MW',

        'Training-1-Zeit',
        'Training-1-Opt-Rang',
        'Training-2-Zeit',
        'Training-2-Opt-Rang',

		'Position-ExpBA',	     // task_pos
        'Position-Option-ExpBA', // chosen_option_position
		'Rang-Option-ExpBA',	 // chosen_option_rank
		'B-Zeit-ExpBA',		     // time_to_decision
		'V-Zeit-ExpBA',	         // Verfügbare Zeit
        'Entscheidung-Vorgabe-ExpBA',  // decision by guideline
        'Zufriedenheit-1-A',
        'Zufriedenheit-2-A',
        'Zufriedenheit-MW-A',
        'Stress-1-A',
        'Stress-2-A',
        'Stress-3-A',
        'Stress-4-A',
        'Stress-5-A',
        'Stress-6-A',
        'Stress-7-A',
        'Stress-MW-A',

        'Position-ExpBB',	     // task_pos
        'Position-Option-ExpBB', // chosen_option_position
        'Rang-Option-ExpBB',	 // chosen_option_rank
        'B-Zeit-ExpBB',		     // time_to_decision
        'V-Zeit-ExpBB',	         // Verfügbare Zeit
        'Entscheidung-Vorgabe-ExpBB',  // decision by guideline
        'Zufriedenheit-1-B',
        'Zufriedenheit-2-B',
        'Zufriedenheit-MW-B',
        'Stress-1-B',
        'Stress-2-B',
        'Stress-3-B',
        'Stress-4-B',
        'Stress-5-B',
        'Stress-6-B',
        'Stress-7-B',
        'Stress-MW-B',

        'Position-ExpBC',	     // task_pos
        'Position-Option-ExpBC', // chosen_option_position
        'Rang-Option-ExpBC',	 // chosen_option_rank
        'B-Zeit-ExpBC',		     // time_to_decision
        'V-Zeit-ExpBC',	         // Verfügbare Zeit
        'Entscheidung-Vorgabe-ExpBC',  // decision by guideline
        'Zufriedenheit-1-C',
        'Zufriedenheit-2-C',
        'Zufriedenheit-MW-C',
        'Stress-1-C',
        'Stress-2-C',
        'Stress-3-C',
        'Stress-4-C',
        'Stress-5-C',
        'Stress-6-C',
        'Stress-7-C',
        'Stress-MW-C',

        'MaxT-1',
        'MaxT-2',
        'MaxT-3',
        'MaxT-4',
        'MaxT-5',
        'MaxT-6',
        'MaxT-7',
        'MaxT-8',
        'MaxT-9',
        'MaxT-10',
        'MaxT-11',
        'MaxT-12',
        'MaxT-13',
		'MaxT-MW',				// maximising_sum

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
            'by_guide_line'          => '#',
            'satisfaction_q1'        => '#',
            'satisfaction_q2'        => '#',
            'satisfaction_sum'       => '#',
            'stress_q1'              => '#',
            'stress_q2'              => '#',
            'stress_q3'              => '#',
            'stress_q4'              => '#',
            'stress_q5'              => '#',
            'stress_q6'              => '#',
            'stress_q7'              => '#',
            'stress_sum'             => '#',
		);
		if (isset($value['experiments'][0])) { $expA = $value['experiments'][0]; }

		$expB = array(
            'task_pos'               => '#',
            'chosen_option_position' => '#',
            'chosen_option_rank'     => '#',
            'time_to_decision'       => '#',
            'by_guide_line'          => '#',
            'satisfaction_q1'        => '#',
            'satisfaction_q2'        => '#',
            'satisfaction_sum'       => '#',
            'stress_q1'              => '#',
            'stress_q2'              => '#',
            'stress_q3'              => '#',
            'stress_q4'              => '#',
            'stress_q5'              => '#',
            'stress_q6'              => '#',
            'stress_q7'              => '#',
            'stress_sum'             => '#',
		);
		if (isset($value['experiments'][1])) { $expB = $value['experiments'][1]; }

		$expC = array(
            'task_pos'               => '#',
            'chosen_option_position' => '#',
            'chosen_option_rank'     => '#',
            'time_to_decision'       => '#',
            'by_guide_line'          => '#',
            'satisfaction_q1'        => '#',
            'satisfaction_q2'        => '#',
            'satisfaction_sum'       => '#',
            'stress_q1'              => '#',
            'stress_q2'              => '#',
            'stress_q3'              => '#',
            'stress_q4'              => '#',
            'stress_q5'              => '#',
            'stress_q6'              => '#',
            'stress_q7'              => '#',
            'stress_sum'             => '#',
		);
		if (isset($value['experiments'][2])) { $expC = $value['experiments'][2]; }

        $maximising = array(
            'q_num_1' => '#',
            'q_num_2' => '#',
            'q_num_3' => '#',
            'q_num_4' => '#',
            'q_num_5' => '#',
            'q_num_6' => '#',
            'q_num_7' => '#',
            'q_num_8' => '#',
            'q_num_9' => '#',
            'q_num_10' => '#',
            'q_num_11' => '#',
            'q_num_12' => '#',
            'q_num_13' => '#',
            'q_sum' => '#'
        );
		if (isset($value['maximising'][0])) { $maximising = $value['maximising'][0]; }

		$age = '#';
		if (isset($value['demographic'][0])) { $age = $value['demographic'][0]['age']; }

		$graduation = '#';
		if (isset($value['demographic'][0])) { $graduation = $value['demographic'][0]['graduation']; }

		$gender = '#';
		if (isset($value['demographic'][0])) { $gender = $value['demographic'][0]['gender']; }

        $job = '#';
        if (isset($value['demographic'][0])) { $job = $value['demographic'][0]['live_status']; }

		$totalTime = '#';
		if (isset($value['participant']['total_time'])) { $totalTime = $value['participant']['total_time']; }

        // Check the additional data
        $disturbed = '#';
        if (isset($value['additional'][0])) { $disturbed = $value['additional'][0]['disturbed']; }

        $seriousness = '#';
        if (isset($value['additional'][0])) { $seriousness = $value['additional'][0]['seriousness']; }

        $interest = '#';
        if (isset($value['additional'][0])) { $interest = $value['additional'][0]['interest']; }

        $additionalTools = '#';
        if (isset($value['additional'][0])) { $additionalTools = $value['additional'][0]['additional_tools']; }

        $mentalArithmetic = '#';
        if (isset($value['additional'][0])) { $mentalArithmetic = $value['additional'][0]['mental_arithmetic']; }

        $mentalRetention = '#';
        if (isset($value['additional'][0])) { $mentalRetention = $value['additional'][0]['memory_retention']; }

        $device = '#';
        if (isset($value['demographic'][0])) { $device = $value['demographic'][0]['device']; }


        $attributes = array(
            'q_num_1' => '#',
            'q_num_2' => '#',
            'q_num_3' => '#',
            'q_num_4' => '#',
            'q_num_5' => '#',
            'q_num_6' => '#',
            'q_num_7' => '#',
            'q_sum' => '#'
        );
        if (isset($value['attributes'][0])) { $attributes = $value['attributes'][0]; }


        $training = array(
            't-1-time'   => '#',
            't-1-option' => '#',
            't-2-time'   => '#',
            't-2-option' => '#'
        );
        if (isset($value['training'][0]))
        {
            $training['t-1-time']   = $value['training'][0]['time_to_decision'];
            $training['t-1-option'] = $value['training'][0]['chosen_option_rank'];
        }
        if (isset($value['training'][1]))
        {
            $training['t-2-time']   = $value['training'][1]['time_to_decision'];
            $training['t-2-option'] = $value['training'][1]['chosen_option_rank'];
        }


		$result[] = array(
			$value['participant']['ip_address'],
			$value['participant']['participated_at'],
            $device,
			$value['participant']['participation_id'],
            $value['participant']['donation_organization_id'],
            $value['participant']['previous_participant'],
			$value['participant']['dropout'],
            $value['participant']['Participation_condition'],
			$value['participant']['participation_group'],

            $attributes['q_num_1'],
            $attributes['q_num_2'],
            $attributes['q_num_3'],
            $attributes['q_num_4'],
            $attributes['q_num_5'],
            $attributes['q_num_6'],
            $attributes['q_num_7'],
            $attributes['q_sum'],

            $training['t-1-time'],
            $training['t-1-option'],
            $training['t-2-time'],
            $training['t-2-option'],

			$expA['task_pos'],
            $expA['chosen_option_position'],
			$expA['chosen_option_rank'],
			$expA['time_to_decision'],
            11500,
            $expA['by_guide_line'],
            $expA['satisfaction_q1'],
            $expA['satisfaction_q2'],
            $expA['satisfaction_sum'],
            $expA['stress_q1'],
            $expA['stress_q2'],
            $expA['stress_q3'],
            $expA['stress_q4'],
            $expA['stress_q5'],
            $expA['stress_q6'],
            $expA['stress_q7'],
            $expA['stress_sum'],

            $expB['task_pos'],
            $expB['chosen_option_position'],
            $expB['chosen_option_rank'],
            $expB['time_to_decision'],
            14000,
            $expB['by_guide_line'],
            $expB['satisfaction_q1'],
            $expB['satisfaction_q2'],
            $expB['satisfaction_sum'],
            $expB['stress_q1'],
            $expB['stress_q2'],
            $expB['stress_q3'],
            $expB['stress_q4'],
            $expB['stress_q5'],
            $expB['stress_q6'],
            $expB['stress_q7'],
            $expB['stress_sum'],

            $expC['task_pos'],
            $expC['chosen_option_position'],
            $expC['chosen_option_rank'],
            $expC['time_to_decision'],
            19500,
            $expC['by_guide_line'],
            $expC['satisfaction_q1'],
            $expC['satisfaction_q2'],
            $expC['satisfaction_sum'],
            $expC['stress_q1'],
            $expC['stress_q2'],
            $expC['stress_q3'],
            $expC['stress_q4'],
            $expC['stress_q5'],
            $expC['stress_q6'],
            $expC['stress_q7'],
            $expC['stress_sum'],

            $maximising['q_num_1'],
            $maximising['q_num_2'],
            $maximising['q_num_3'],
            $maximising['q_num_4'],
            $maximising['q_num_5'],
            $maximising['q_num_6'],
            $maximising['q_num_7'],
            $maximising['q_num_8'],
            $maximising['q_num_9'],
            $maximising['q_num_10'],
            $maximising['q_num_11'],
            $maximising['q_num_12'],
            $maximising['q_num_13'],
            $maximising['q_sum'],

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
		fputcsv($file, $line, ';');
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
