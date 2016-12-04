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
        'numberOfParticipants' => $app->db->getNumberOfParticipants(),
        'genderShare'          => $app->db->getGenderShare(),
        'averageAge'           => $app->db->getAverageAge(),
        'rewardShare'          => $app->db->getRewardShare(),
        'payoutParticipants'   => $app->db->getPayoutParticipants(),
        'payout'               => $app->db->getPayoutStats(),
        'averageTotalTime'     => $app->db->getAverageTotalTime()
    );


    $userStats = $app->db->getUserStats();

    $app->render('dashboard.php', array(
        'general'  => $generalData,
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

	$result   = array();
	$firstRow = array(
		'Date',
		'VpN',  		
		'Code',	            // Participant ID
        'Entlohnung',
        'ExpB',             // Condition
		'ExpG',				// Group
            
        'Alter',
		'Schule',
        'Ausbildung',
		'Akad',
		'Beruf',
		'Geschlecht',
		'Psycho-Studies',
		'Gesamtzeit',
		'Dropout',
		'pps', // Punkte in Studie
		'payout',
        
        
        'MaxT_1',
        'MaxT_2',
        'MaxT_3',
        'MaxT_4',
        'MaxT_5',
        'MaxT_6',
		'MaxT_MW',				// maximising_sum
            
        'Res_1',
        'Res_2',
        'Res_3',
        'Res_4',
        'Res_5',
        'Res_6',
        'Res_7',
        'Res_8',
        'Res_9',
        'Res_10',
        'Res_11',
		'Res_MW',				// maximising_sum
		
		'Risk_1',
        'Risk_2',
        'Risk_3',
        'Risk_4',
        
        'Nfc_1',
        'Nfc_2',
        'Nfc_3',
        'Nfc_4',
		'Nfc_MW',				// maximising_sum
            
        'Meta_1.1',
        'Meta_1.2',
        'Meta_2',
        'Meta_5',
        
        
		'Position_ExpB_X_A',	     // task_pos
		'lt_ExpG_X_A',	             // Verfügbare Zeit
        'nT_X_A',          // Anzahl trials in A
        'Stress_1_X_A',
        'Stress_2_X_A',
        'Stress_3_X_A',
        'ME_4.1_X_A',
        'Stress_Time_X_A',
        'Time_ExpB_X_A',
        'ppa_X_A',
        'tt1_X_A_Sum',
        'tt2_X_A_Sum',
        'at_X_A_Sum',

		'Position_ExpB_X_B',	     // task_pos
		'lt_ExpG_X_B',	             // Verfügbare Zeit
        'nT_X_B',          // Anzahl trials in A
        'Stress_1_X_B',
        'Stress_2_X_B',
        'Stress_3_X_B',
        'ME_4.1_X_B',
        'Stress_Time_X_B',
        'Time_ExpB_X_B',
        'ppa_X_B',
        'tt1_X_B_Sum',
        'tt2_X_B_Sum',
        'at_X_B_Sum',

		'Position_ExpB_X_C',	     // task_pos
		'lt_ExpG_X_C',	             // Verfügbare Zeit
        'nT_X_C',          // Anzahl trials in A
        'Stress_1_X_C',
        'Stress_2_X_C',
        'Stress_3_X_C',
        'ME_4.1_X_C',
        'Stress_Time_X_C',
        'Time_ExpB_X_C',
        'ppa_X_C',
        'tt1_X_C_Sum',
        'tt2_X_C_Sum',
        'at_X_C_Sum',
        
        'Position_ExpB_Y_A',	     // task_pos
		'lt_ExpG_Y_A',	             // Verfügbare Zeit
        'nT_Y_A',          // Anzahl trials in A
        'Stress_1_Y_A',
        'Stress_2_Y_A',
        'Stress_3_Y_A',
        'ME_4.1_Y_A',
        'Stress_Time_Y_A',
        'Time_ExpB_Y_A',
        'ppa_Y_A',
        'tt1_Y_A_Sum',
        'tt2_Y_A_Sum',
        'at_Y_A_Sum',

		'Position_ExpB_Y_B',	     // task_pos
		'lt_ExpG_Y_B',	             // Verfügbare Zeit
        'nT_Y_B',          // Anzahl trials in A
        'Stress_1_Y_B',
        'Stress_2_Y_B',
        'Stress_3_Y_B',
        'ME_4.1_Y_B',
        'Stress_Time_Y_B',
        'Time_ExpB_Y_B',
        'ppa_Y_B',
        'tt1_Y_B_Sum',
        'tt2_Y_B_Sum',
        'at_Y_B_Sum',

		'Position_ExpB_Y_C',	     // task_pos
		'lt_ExpG_Y_C',	             // Verfügbare Zeit
        'nT_Y_C',          // Anzahl trials in A
        'Stress_1_Y_C',
        'Stress_2_Y_C',
        'Stress_3_Y_C',
        'ME_4.1_Y_C',
        'Stress_Time_Y_C',
        'Time_ExpB_Y_C',
        'ppa_Y_C',
        'tt1_Y_C_Sum',
        'tt2_Y_C_Sum',
        'at_Y_C_Sum',
	);
    
    // Generate Labels for Trials
    for ($indexOfTrial = 1; $indexOfTrial < 9; $indexOfTrial++)
    {
        $firstRow[] = 'PC_X_A_' . $indexOfTrial; // pair compairison
        $firstRow[] = 'AS_X_A_' . $indexOfTrial; // acquisition_pattern
        $firstRow[] = 'aw_SUM_X_A_' . $indexOfTrial; // weight sum
        $firstRow[] = 'laa_X_A_' . $indexOfTrial; // local_accuracy
        $firstRow[] = 'laa2_X_A_' . $indexOfTrial; // local_accuracy
        $firstRow[] = 'Gew_Opt_X_A_' . $indexOfTrial; // chosen option
        $firstRow[] = 'apt_X_A_' . $indexOfTrial; // number of acquisitions
        $firstRow[] = 'AC_Order_X_A_' . $indexOfTrial; // acquisition order
        $firstRow[] = 'tt1_X_A_' . $indexOfTrial; // trial time to finish
        $firstRow[] = 'tt2_X_A_' . $indexOfTrial; // trial time to finish
        $firstRow[] = 'dt_X_A_' . $indexOfTrial; // trial time to finish
        $firstRow[] = 'at_SUM_X_A_' . $indexOfTrial; // sum aqcuisition times
        $firstRow[] = 'ppt2_X_A_' . $indexOfTrial; //  trial score
        
        $firstRow[] = 'ACO_X_A_' . $indexOfTrial . '_1';
        $firstRow[] = 'ACO_X_A_' . $indexOfTrial . '_2'; 
        $firstRow[] = 'ACO_X_A_' . $indexOfTrial . '_3';
        $firstRow[] = 'ACO_X_A_' . $indexOfTrial . '_4'; 
    }
    
    for ($indexOfTrial = 1; $indexOfTrial < 37; $indexOfTrial++)
    {
        $firstRow[] = 'PC_X_B_' . $indexOfTrial; // pair compairison
        $firstRow[] = 'AS_X_B_' . $indexOfTrial; // acquisition_pattern
        $firstRow[] = 'aw_SUM_X_B_' . $indexOfTrial; // weight sum
        $firstRow[] = 'laa_X_B_' . $indexOfTrial; // local_accuracy
        $firstRow[] = 'laa2_X_B_' . $indexOfTrial; // local_accuracy
        $firstRow[] = 'Gew_Opt_X_B_' . $indexOfTrial; // chosen option
        $firstRow[] = 'apt_X_B_' . $indexOfTrial; // number of acquisitions
        $firstRow[] = 'AC_Order_X_B_' . $indexOfTrial; // acquisition order
        $firstRow[] = 'tt1_X_B_' . $indexOfTrial; // trial time to finish after cost
        $firstRow[] = 'tt2_X_B_' . $indexOfTrial; // trial time to finish
        $firstRow[] = 'dt_X_B_' . $indexOfTrial; // trial time to finish
        $firstRow[] = 'at_SUM_X_B_' . $indexOfTrial; // sum aqcuisition times
        $firstRow[] = 'ppt2_X_B_' . $indexOfTrial; //  trial score
        
        $firstRow[] = 'ACO_X_B_' . $indexOfTrial . '_1';
        $firstRow[] = 'ACO_X_B_' . $indexOfTrial . '_2'; 
        $firstRow[] = 'ACO_X_B_' . $indexOfTrial . '_3';
        $firstRow[] = 'ACO_X_B_' . $indexOfTrial . '_4'; 
    }
    
    for ($indexOfTrial = 1; $indexOfTrial < 65; $indexOfTrial++)
    {
        $firstRow[] = 'PC_X_C_' . $indexOfTrial; // pair compairison
        $firstRow[] = 'AS_X_C_' . $indexOfTrial; // acquisition_pattern
        $firstRow[] = 'aw_SUM_X_C_' . $indexOfTrial; // weight sum
        $firstRow[] = 'laa_X_C_' . $indexOfTrial; // local_accuracy
        $firstRow[] = 'laa2_X_C_' . $indexOfTrial; // local_accuracy
        $firstRow[] = 'Gew_Opt_X_C_' . $indexOfTrial; // chosen option
        $firstRow[] = 'apt_X_C_' . $indexOfTrial; // number of acquisitions
        $firstRow[] = 'AC_Order_X_C_' . $indexOfTrial; // acquisition order
        $firstRow[] = 'tt1_X_C_' . $indexOfTrial; // trial time to finish
        $firstRow[] = 'tt2_X_C_' . $indexOfTrial; // trial time to finish
        $firstRow[] = 'dt_X_C_' . $indexOfTrial; // trial time to finish
        $firstRow[] = 'at_SUM_X_C_' . $indexOfTrial; // sum aqcuisition times
        $firstRow[] = 'ppt2_X_C_' . $indexOfTrial; // trial score
        
        $firstRow[] = 'ACO_X_C_' . $indexOfTrial . '_1';
        $firstRow[] = 'ACO_X_C_' . $indexOfTrial . '_2'; 
        $firstRow[] = 'ACO_X_C_' . $indexOfTrial . '_3';
        $firstRow[] = 'ACO_X_C_' . $indexOfTrial . '_4'; 
    }
    
    
    
        // Generate Labels for Trials
    for ($indexOfTrial = 1; $indexOfTrial < 9; $indexOfTrial++)
    {
        $firstRow[] = 'PC_Y_A_' . $indexOfTrial; // pair compairison
        $firstRow[] = 'AS_Y_A_' . $indexOfTrial; // acquisition_pattern
        $firstRow[] = 'aw_SUM_Y_A_' . $indexOfTrial; // weight sum
        $firstRow[] = 'laa_Y_A_' . $indexOfTrial; // local_accuracy
        $firstRow[] = 'laa2_Y_A_' . $indexOfTrial; // local_accuracy
        $firstRow[] = 'Gew_Opt_Y_A_' . $indexOfTrial; // chosen option
        $firstRow[] = 'apt_Y_A_' . $indexOfTrial; // number of acquisitions
        $firstRow[] = 'AC_Order_Y_A_' . $indexOfTrial; // acquisition order
        $firstRow[] = 'tt1_Y_A_' . $indexOfTrial; // trial time to finish
        $firstRow[] = 'tt2_Y_A_' . $indexOfTrial; // trial time to finish
        $firstRow[] = 'dt_Y_A_' . $indexOfTrial; // trial time to finish
        $firstRow[] = 'at_SUM_Y_A_' . $indexOfTrial; // sum aqcuisition times
        $firstRow[] = 'ppt2_Y_A_' . $indexOfTrial; //  trial score
        
        $firstRow[] = 'ACO_Y_A_' . $indexOfTrial . '_1';
        $firstRow[] = 'ACO_Y_A_' . $indexOfTrial . '_2'; 
        $firstRow[] = 'ACO_Y_A_' . $indexOfTrial . '_3';
        $firstRow[] = 'ACO_Y_A_' . $indexOfTrial . '_4'; 
    }
    
    for ($indexOfTrial = 1; $indexOfTrial < 37; $indexOfTrial++)
    {
        $firstRow[] = 'PC_Y_B_' . $indexOfTrial; // pair compairison
        $firstRow[] = 'AS_Y_B_' . $indexOfTrial; // acquisition_pattern
        $firstRow[] = 'aw_SUM_Y_B_' . $indexOfTrial; // weight sum
        $firstRow[] = 'laa_Y_B_' . $indexOfTrial; // local_accuracy
        $firstRow[] = 'laa2_Y_B_' . $indexOfTrial; // local_accuracy
        $firstRow[] = 'Gew_Opt_Y_B_' . $indexOfTrial; // chosen option
        $firstRow[] = 'apt_Y_B_' . $indexOfTrial; // number of acquisitions
        $firstRow[] = 'AC_Order_Y_B_' . $indexOfTrial; // acquisition order
        $firstRow[] = 'tt1_Y_B_' . $indexOfTrial; // trial time to finish after cost
        $firstRow[] = 'tt2_Y_B_' . $indexOfTrial; // trial time to finish
        $firstRow[] = 'dt_Y_B_' . $indexOfTrial; // trial time to finish
        $firstRow[] = 'at_SUM_Y_B_' . $indexOfTrial; // sum aqcuisition times
        $firstRow[] = 'ppt2_Y_B_' . $indexOfTrial; //  trial score
        
        $firstRow[] = 'ACO_Y_B_' . $indexOfTrial . '_1';
        $firstRow[] = 'ACO_Y_B_' . $indexOfTrial . '_2'; 
        $firstRow[] = 'ACO_Y_B_' . $indexOfTrial . '_3';
        $firstRow[] = 'ACO_Y_B_' . $indexOfTrial . '_4'; 
    }
    
    for ($indexOfTrial = 1; $indexOfTrial < 65; $indexOfTrial++)
    {
        $firstRow[] = 'PC_Y_C_' . $indexOfTrial; // pair compairison
        $firstRow[] = 'AS_Y_C_' . $indexOfTrial; // acquisition_pattern
        $firstRow[] = 'aw_SUM_Y_C_' . $indexOfTrial; // weight sum
        $firstRow[] = 'laa_Y_C_' . $indexOfTrial; // local_accuracy
        $firstRow[] = 'laa2_Y_C_' . $indexOfTrial; // local_accuracy
        $firstRow[] = 'Gew_Opt_Y_C_' . $indexOfTrial; // chosen option
        $firstRow[] = 'apt_Y_C_' . $indexOfTrial; // number of acquisitions
        $firstRow[] = 'AC_Order_Y_C_' . $indexOfTrial; // acquisition order
        $firstRow[] = 'tt1_Y_C_' . $indexOfTrial; // trial time to finish
        $firstRow[] = 'tt2_Y_C_' . $indexOfTrial; // trial time to finish
        $firstRow[] = 'dt_Y_C_' . $indexOfTrial; // trial time to finish
        $firstRow[] = 'at_SUM_Y_C_' . $indexOfTrial; // sum aqcuisition times
        $firstRow[] = 'ppt2_Y_C_' . $indexOfTrial; // trial score
        
        $firstRow[] = 'ACO_Y_C_' . $indexOfTrial . '_1';
        $firstRow[] = 'ACO_Y_C_' . $indexOfTrial . '_2'; 
        $firstRow[] = 'ACO_Y_C_' . $indexOfTrial . '_3';
        $firstRow[] = 'ACO_Y_C_' . $indexOfTrial . '_4'; 
    }
    
    $result[] = $firstRow;
    
    var_dump($value['experiments']);
    
	foreach ($data as $value) {
	    $totalScore = 0;
	    
		$expXA = array(
			'task_pos'               => '#',
			'time_to_finish'         => '#',
            'stress_q1'              => '#',
            'stress_q2'              => '#',
            'stress_q3'              => '#',
            'q_me4'                  => '#',
            'stress_sum'             => '#',
            'time_to_answer'         => '#',
            'numberOfTrials'         => '#',
            'score'                  => '#',
            'trialTimeSum'           => '#',
            'acquisitionSum'         => '#',
		);
		if (isset($value['experiments'][0])) { $expXA = $value['experiments'][0]; $totalScore += $expXA['score']; }

		$expXB = array(
			'task_pos'               => '#',
			'time_to_finish'         => '#',
            'stress_q1'              => '#',
            'stress_q2'              => '#',
            'stress_q3'              => '#',
            'q_me4'                  => '#',
            'stress_sum'             => '#',
            'time_to_answer'         => '#',
            'numberOfTrials'         => '#',
            'score'                  => '#',
            'trialTimeSum'           => '#',
            'acquisitionSum'         => '#',
		);
		if (isset($value['experiments'][1])) { $expXB = $value['experiments'][1]; $totalScore += $expXB['score']; }

		$expXC = array(
			'task_pos'               => '#',
			'time_to_finish'         => '#',
            'stress_q1'              => '#',
            'stress_q2'              => '#',
            'stress_q3'              => '#',
            'q_me4'                  => '#',
            'stress_sum'             => '#',
            'time_to_answer'         => '#',
            'numberOfTrials'         => '#',
            'score'                  => '#',
            'trialTimeSum'           => '#',
            'acquisitionSum'         => '#',
		);
		if (isset($value['experiments'][2])) { $expXC = $value['experiments'][2]; $totalScore += $expXC['score']; }
		
		$expYA = array(
			'task_pos'               => '#',
			'time_to_finish'         => '#',
            'stress_q1'              => '#',
            'stress_q2'              => '#',
            'stress_q3'              => '#',
            'q_me4'                  => '#',
            'stress_sum'             => '#',
            'time_to_answer'         => '#',
            'numberOfTrials'         => '#',
            'score'                  => '#',
            'trialTimeSum'           => '#',
            'acquisitionSum'         => '#',
		);
		if (isset($value['experiments'][3])) { $expYA = $value['experiments'][3]; $totalScore += $expYA['score']; }
		
		$expYB = array(
			'task_pos'               => '#',
			'time_to_finish'         => '#',
            'stress_q1'              => '#',
            'stress_q2'              => '#',
            'stress_q3'              => '#',
            'q_me4'                  => '#',
            'stress_sum'             => '#',
            'time_to_answer'         => '#',
            'numberOfTrials'         => '#',
            'score'                  => '#',
            'trialTimeSum'           => '#',
            'acquisitionSum'         => '#',
		);
		if (isset($value['experiments'][4])) { $expYB = $value['experiments'][4]; $totalScore += $expYB['score']; }
		
		$expYC = array(
			'task_pos'               => '#',
			'time_to_finish'         => '#',
            'stress_q1'              => '#',
            'stress_q2'              => '#',
            'stress_q3'              => '#',
            'q_me4'                  => '#',
            'stress_sum'             => '#',
            'time_to_answer'         => '#',
            'numberOfTrials'         => '#',
            'score'                  => '#',
            'trialTimeSum'           => '#',
            'acquisitionSum'         => '#',
		);
		if (isset($value['experiments'][5])) { $expYC = $value['experiments'][5]; $totalScore += $expYC['score']; }

        $maximising = array(
            'q_num_1' => '#',
            'q_num_2' => '#',
            'q_num_3' => '#',
            'q_num_4' => '#',
            'q_num_5' => '#',
            'q_num_6' => '#',
            'q_sum' => '#'
        );
		if (isset($value['maximising'][0])) { $maximising = $value['maximising'][0]; }
		
		$resiliance = array(
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
            'q_sum' => '#'
        );
		if (isset($value['resiliance'][0])) { $resiliance = $value['resiliance'][0]; }
		
		$risk = array(
            'q_num_1' => '#',
            'q_num_2' => '#',
            'q_num_3' => '#',
            'q_num_4' => '#'
        );
		if (isset($value['risk'][0])) { $risk = $value['risk'][0]; }
		
		$nfc = array(
            'q_num_1' => '#',
            'q_num_2' => '#',
            'q_num_3' => '#',
            'q_num_4' => '#',
            'q_sum' => '#'
        );
		if (isset($value['nfc'][0])) { $nfc = $value['nfc'][0]; }
		
		$meta = array(
            'q_num_1' => '#',
            'q_num_11' => '#',
            'q_num_2' => '#',
            'q_num_3' => '#',
            'q_num_5' => '#',
        );
		if (isset($value['meta'][0])) { $meta = $value['meta'][0]; }

		$age = '#';
		if (isset($value['demographic'][0])) { $age = $value['demographic'][0]['age']; }
        
        $school = '#';
		if (isset($value['demographic'][0])) { $school = $value['demographic'][0]['graduation']; }
        
		$apprenticeship = '#';
		if (isset($value['demographic'][0])) { $apprenticeship = $value['demographic'][0]['apprenticeship']; }
        
        $akademic = '#';
		if (isset($value['demographic'][0])) { $akademic = $value['demographic'][0]['academic_degree']; }
        
		$gender = '#';
		if (isset($value['demographic'][0])) { $gender = $value['demographic'][0]['gender']; }

        $job = '#';
        if (isset($value['demographic'][0])) { $job = $value['demographic'][0]['live_status']; }
        
        $psychoStudies = '#';
        if (isset($value['demographic'][0])) { $psychoStudies = $value['demographic'][0]['psycho_studies']; }

		$totalTime = '#';
		if (isset($value['participant']['total_time'])) { $totalTime = $value['participant']['total_time']; }
		
        $dropout = '#';
		if (isset($value['participant']['dropout'])) { $dropout = $value['participant']['dropout']; }

        $reward = '#';
        if (isset($value['participant']['reward'])) { $reward = $value['participant']['reward']; }
        
        $payout = '#';
        if (isset($value['participant']['payout'])) { $payout = $value['participant']['payout']; }
        
        
        $timeCosts = [
            'X_A' => [2330, 3590, 3730, 3930, 4070, 1840, 2050, 2180, 2190, 2320,  960, 310, 440, 190, 0],
            'X_B' => [3510, 2640, 2790, 2990, 2350, 1620, 1820, 1550, 1560, 1340, 1230, 430, 330, 150, 0],
            'X_C' => [4510, 3030, 2680, 2890, 2580, 1840, 1570, 1480, 1490, 1430, 1270, 440, 310, 100, 0],
            
            'Y_A' => [2330, 3590, 3730, 3930, 4070, 1840, 2050, 2180, 2190, 2320,  960, 310, 440, 190, 0],
            'Y_B' => [3510, 2640, 2790, 2990, 2350, 1620, 1820, 1550, 1560, 1340, 1230, 430, 330, 150, 0],
            'Y_C' => [4510, 3030, 2680, 2890, 2580, 1840, 1570, 1480, 1490, 1430, 1270, 440, 310, 100, 0]
        ];
        
        // Calculate tt1 sum
        $tt1Sums = array('X_A' => 0, 'X_B' => 0, 'X_C' => 0, 'Y_A' => 0, 'Y_B' => 0, 'Y_C' => 0);
        foreach ($value['trials'] as $trial)
        {
            if ($trial['task'] === 'X_A')
            {
                $tt1Sums['X_A'] += ($trial['time_to_finish'] - $timeCosts['X_A'][max($trial['acquisition_pattern'], 1) - 1]);
            }
            else if ($trial['task'] === 'X_B')
            {
                $tt1Sums['X_B'] += ($trial['time_to_finish'] - $timeCosts['X_B'][max($trial['acquisition_pattern'], 1) - 1]);
            }
            else if ($trial['task'] === 'X_C')
            {
                $tt1Sums['X_C'] += ($trial['time_to_finish'] - $timeCosts['X_C'][max($trial['acquisition_pattern'], 1) - 1]);
            }
            else if ($trial['task'] === 'Y_A')
            {
                $tt1Sums['Y_A'] += ($trial['time_to_finish'] - $timeCosts['Y_A'][max($trial['acquisition_pattern'], 1) - 1]);
            }
            else if ($trial['task'] === 'Y_B')
            {
                $tt1Sums['Y_B'] += ($trial['time_to_finish'] - $timeCosts['Y_B'][max($trial['acquisition_pattern'], 1) - 1]);
            }
            else if ($trial['task'] === 'Y_C')
            {
                $tt1Sums['Y_C'] += ($trial['time_to_finish'] - $timeCosts['Y_C'][max($trial['acquisition_pattern'], 1) - 1]);
            }
        }
        
        
		$currentRow = array(
			$value['participant']['participated_at'],
			$value['participant']['id'],
			$value['participant']['participation_id'],
            $reward,
            str_replace('C', '', $value['participant']['participation_condition']),
			str_replace('G', '', $value['participant']['participation_group']),
            
            $age,
			$school,
			$apprenticeship,
			$akademic,
			$job,
			$gender,
			$psychoStudies,
			$totalTime,
			$dropout,
			$totalScore,
			$payout,
			
			
			$maximising['q_num_1'],
            $maximising['q_num_2'],
            $maximising['q_num_3'],
            $maximising['q_num_4'],
            $maximising['q_num_5'],
            $maximising['q_num_6'],
            round($maximising['q_sum'] / 6, 1),
            
            $resiliance['q_num_1'],
            $resiliance['q_num_2'],
            $resiliance['q_num_3'],
            $resiliance['q_num_4'],
            $resiliance['q_num_5'],
            $resiliance['q_num_6'],
            $resiliance['q_num_7'],
            $resiliance['q_num_8'],
            $resiliance['q_num_9'],
            $resiliance['q_num_10'],
            $resiliance['q_num_11'],
            round($resiliance['q_sum'] / 11, 1),
            
            $risk['q_num_1'],
            $risk['q_num_2'],
            $risk['q_num_3'],
            $risk['q_num_4'],
            
            $nfc['q_num_1'],
            $nfc['q_num_2'],
            $nfc['q_num_3'],
            $nfc['q_num_4'],
            round($nfc['q_sum'] / 4, 1),
            
            $meta['q_num_11'],
            $meta['q_num_1'],
            $meta['q_num_2'],
            $meta['q_num_5'],
            
            
			$expXA['task_pos'],
			18060,
			$expXA['numberOfTrials'],
            $expXA['stress_q1'],
            $expXA['stress_q2'],
            $expXA['stress_q3'],
            $expXA['q_me4'],
            $expXA['time_to_answer'],
            $expXA['time_to_finish'],
            $expXA['score'],
            $tt1Sums['X_A'],
            $expXA['trialTimeSum'],
            $expXA['acquisitionSum'],

			$expXB['task_pos'],
			81270,
			$expXB['numberOfTrials'],
            $expXB['stress_q1'],
            $expXB['stress_q2'],
            $expXB['stress_q3'],
            $expXB['q_me4'],
            $expXB['time_to_answer'],
            $expXB['time_to_finish'],
            $expXB['score'],
            $tt1Sums['X_B'],
            $expXB['trialTimeSum'],
            $expXB['acquisitionSum'],
            
			$expXC['task_pos'],
			144480,
			$expXC['numberOfTrials'],
            $expXC['stress_q1'],
            $expXC['stress_q2'],
            $expXC['stress_q3'],
            $expXC['q_me4'],
            $expXC['time_to_answer'],
            $expXC['time_to_finish'],
            $expXC['score'],
            $tt1Sums['X_C'],
            $expXC['trialTimeSum'],
            $expXC['acquisitionSum'],
            
            $expYA['task_pos'],
			18060,
			$expYA['numberOfTrials'],
            $expYA['stress_q1'],
            $expYA['stress_q2'],
            $expYA['stress_q3'],
            $expYA['q_me4'],
            $expYA['time_to_answer'],
            $expYA['time_to_finish'],
            $expYA['score'],
            $tt1Sums['Y_A'],
            $expYA['trialTimeSum'],
            $expYA['acquisitionSum'],
            
            $expYB['task_pos'],
			81270,
			$expYB['numberOfTrials'],
            $expYB['stress_q1'],
            $expYB['stress_q2'],
            $expYB['stress_q3'],
            $expYB['q_me4'],
            $expYB['time_to_answer'],
            $expYB['time_to_finish'],
            $expYB['score'],
            $tt1Sums['Y_B'],
            $expYB['trialTimeSum'],
            $expYB['acquisitionSum'],
            
            $expYC['task_pos'],
			144480,
			$expYC['numberOfTrials'],
            $expYC['stress_q1'],
            $expYC['stress_q2'],
            $expYC['stress_q3'],
            $expYC['q_me4'],
            $expYC['time_to_answer'],
            $expYC['time_to_finish'],
            $expYC['score'],
            $tt1Sums['Y_C'],
            $expYC['trialTimeSum'],
            $expYC['acquisitionSum']
		);
		

		
	    // Attach trial data
	    for ($indexOfTrial = 0; $indexOfTrial < 8; $indexOfTrial++)
        {
            if (isset($value['trials'][0]) && $value['trials'][0]['task'] === 'X_A')
            {
                $currentRow[] = $value['trials'][0]['pair_comparison'];
                $currentRow[] = $value['trials'][0]['acquisition_pattern'];
                $currentRow[] = $value['trials'][0]['acquired_weights'];
                $currentRow[] = $value['trials'][0]['local_accuracy'];
                $currentRow[] = $value['trials'][0]['local_accuracy2'];
                $currentRow[] = $value['trials'][0]['chosen_option'];
                $currentRow[] = $value['trials'][0]['number_of_acquisitions'];
                $currentRow[] = $value['trials'][0]['order_of_acqusitions'];
                $currentRow[] = $value['trials'][0]['time_to_finish'] - $timeCosts['X_A'][max($value['trials'][0]['acquisition_pattern'], 1) - 1];
                $currentRow[] = $value['trials'][0]['time_to_finish'];
                $currentRow[] = ($value['trials'][0]['time_to_finish'] - $timeCosts['X_A'][max($value['trials'][0]['acquisition_pattern'], 1) - 1]) - $value['trials'][0]['acquisition_time'];
                $currentRow[] = $value['trials'][0]['acquisition_time'];
                $currentRow[] = $value['trials'][0]['score'];
                
                if (isset($value['trials'][0]['order_of_acqusitions']))
                {
                    $acqOrder = explode(':', $value['trials'][0]['order_of_acqusitions']);                

                    $currentRow[] = $acqOrder[0];
                    $currentRow[] = $acqOrder[1];
                    $currentRow[] = $acqOrder[2];
                    $currentRow[] = $acqOrder[3];
                }
                else
                {
                    $currentRow[] = '#####';
                    $currentRow[] = '#####';
                    $currentRow[] = '#####';
                    $currentRow[] = '#####';
                }

                
                array_shift($value['trials']);
            }
            else 
            {
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
            }
        }
        
        for ($indexOfTrial = 0; $indexOfTrial < 36; $indexOfTrial++)
        {
            if (isset($value['trials'][0]) && $value['trials'][0]['task'] === 'X_B')
            {
                $currentRow[] = $value['trials'][0]['pair_comparison'];
                $currentRow[] = $value['trials'][0]['acquisition_pattern'];
                $currentRow[] = $value['trials'][0]['acquired_weights'];
                $currentRow[] = $value['trials'][0]['local_accuracy'];
                $currentRow[] = $value['trials'][0]['local_accuracy2'];
                $currentRow[] = $value['trials'][0]['chosen_option'];
                $currentRow[] = $value['trials'][0]['number_of_acquisitions'];
                $currentRow[] = $value['trials'][0]['order_of_acqusitions'];
                $currentRow[] = $value['trials'][0]['time_to_finish'] - $timeCosts['X_B'][max($value['trials'][0]['acquisition_pattern'], 1) - 1];
                $currentRow[] = $value['trials'][0]['time_to_finish'];
                $currentRow[] = ($value['trials'][0]['time_to_finish'] - $timeCosts['X_B'][max($value['trials'][0]['acquisition_pattern'], 1) - 1]) - $value['trials'][0]['acquisition_time'];
                $currentRow[] = $value['trials'][0]['acquisition_time'];
                $currentRow[] = $value['trials'][0]['score'];
                
                if (isset($value['trials'][0]['order_of_acqusitions']))
                {
                    $acqOrder = explode(':', $value['trials'][0]['order_of_acqusitions']);                

                    $currentRow[] = $acqOrder[0];
                    $currentRow[] = $acqOrder[1];
                    $currentRow[] = $acqOrder[2];
                    $currentRow[] = $acqOrder[3];
                }
                else
                {
                    $currentRow[] = '#####';
                    $currentRow[] = '#####';
                    $currentRow[] = '#####';
                    $currentRow[] = '#####';
                }
                
                array_shift($value['trials']);
            }
            else 
            {
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
            }
        }
        for ($indexOfTrial = 0; $indexOfTrial < 64; $indexOfTrial++)
        {
            if (isset($value['trials'][0]) && $value['trials'][0]['task'] === 'X_C')
            {
                $currentRow[] = $value['trials'][0]['pair_comparison'];
                $currentRow[] = $value['trials'][0]['acquisition_pattern'];
                $currentRow[] = $value['trials'][0]['acquired_weights'];
                $currentRow[] = $value['trials'][0]['local_accuracy'];
                $currentRow[] = $value['trials'][0]['local_accuracy2'];
                $currentRow[] = $value['trials'][0]['chosen_option'];
                $currentRow[] = $value['trials'][0]['number_of_acquisitions'];
                $currentRow[] = $value['trials'][0]['order_of_acqusitions'];
                $currentRow[] = $value['trials'][0]['time_to_finish'] - $timeCosts['X_C'][max($value['trials'][0]['acquisition_pattern'], 1) - 1];
                $currentRow[] = $value['trials'][0]['time_to_finish'];
                $currentRow[] = ($value['trials'][0]['time_to_finish'] - $timeCosts['X_C'][max($value['trials'][0]['acquisition_pattern'], 1) - 1]) - $value['trials'][0]['acquisition_time'];
                $currentRow[] = $value['trials'][0]['acquisition_time'];
                $currentRow[] = $value['trials'][0]['score'];
                
                if (isset($value['trials'][0]['order_of_acqusitions']))
                {
                    $acqOrder = explode(':', $value['trials'][0]['order_of_acqusitions']);                

                    $currentRow[] = $acqOrder[0];
                    $currentRow[] = $acqOrder[1];
                    $currentRow[] = $acqOrder[2];
                    $currentRow[] = $acqOrder[3];
                }
                else
                {
                    $currentRow[] = '#####';
                    $currentRow[] = '#####';
                    $currentRow[] = '#####';
                    $currentRow[] = '#####';
                }
                
                array_shift($value['trials']);
            }
            else 
            {
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
            }
        }
        
        
        
        
         // Attach trial data
	    for ($indexOfTrial = 0; $indexOfTrial < 8; $indexOfTrial++)
        {
            if (isset($value['trials'][0]) && $value['trials'][0]['task'] === 'Y_A')
            {
                $currentRow[] = $value['trials'][0]['pair_comparison'];
                $currentRow[] = $value['trials'][0]['acquisition_pattern'];
                $currentRow[] = $value['trials'][0]['acquired_weights'];
                $currentRow[] = $value['trials'][0]['local_accuracy'];
                $currentRow[] = $value['trials'][0]['local_accuracy2'];
                $currentRow[] = $value['trials'][0]['chosen_option'];
                $currentRow[] = $value['trials'][0]['number_of_acquisitions'];
                $currentRow[] = $value['trials'][0]['order_of_acqusitions'];
                $currentRow[] = $value['trials'][0]['time_to_finish'] - $timeCosts['Y_A'][max($value['trials'][0]['acquisition_pattern'], 1) - 1];
                $currentRow[] = $value['trials'][0]['time_to_finish'];
                $currentRow[] = ($value['trials'][0]['time_to_finish'] - $timeCosts['Y_A'][max($value['trials'][0]['acquisition_pattern'], 1) - 1]) - $value['trials'][0]['acquisition_time'];
                $currentRow[] = $value['trials'][0]['acquisition_time'];
                $currentRow[] = $value['trials'][0]['score'];
                
                if (isset($value['trials'][0]['order_of_acqusitions']))
                {
                    $acqOrder = explode(':', $value['trials'][0]['order_of_acqusitions']);                

                    $currentRow[] = $acqOrder[0];
                    $currentRow[] = $acqOrder[1];
                    $currentRow[] = $acqOrder[2];
                    $currentRow[] = $acqOrder[3];
                }
                else
                {
                    $currentRow[] = '#####';
                    $currentRow[] = '#####';
                    $currentRow[] = '#####';
                    $currentRow[] = '#####';
                }

                
                array_shift($value['trials']);
            }
            else 
            {
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
            }
        }
        
        for ($indexOfTrial = 0; $indexOfTrial < 36; $indexOfTrial++)
        {
            if (isset($value['trials'][0]) && $value['trials'][0]['task'] === 'Y_B')
            {
                $currentRow[] = $value['trials'][0]['pair_comparison'];
                $currentRow[] = $value['trials'][0]['acquisition_pattern'];
                $currentRow[] = $value['trials'][0]['acquired_weights'];
                $currentRow[] = $value['trials'][0]['local_accuracy'];
                $currentRow[] = $value['trials'][0]['local_accuracy2'];
                $currentRow[] = $value['trials'][0]['chosen_option'];
                $currentRow[] = $value['trials'][0]['number_of_acquisitions'];
                $currentRow[] = $value['trials'][0]['order_of_acqusitions'];
                $currentRow[] = $value['trials'][0]['time_to_finish'] - $timeCosts['Y_B'][max($value['trials'][0]['acquisition_pattern'], 1) - 1];
                $currentRow[] = $value['trials'][0]['time_to_finish'];
                $currentRow[] = ($value['trials'][0]['time_to_finish'] - $timeCosts['Y_B'][max($value['trials'][0]['acquisition_pattern'], 1) - 1]) - $value['trials'][0]['acquisition_time'];
                $currentRow[] = $value['trials'][0]['acquisition_time'];
                $currentRow[] = $value['trials'][0]['score'];
                
                if (isset($value['trials'][0]['order_of_acqusitions']))
                {
                    $acqOrder = explode(':', $value['trials'][0]['order_of_acqusitions']);                

                    $currentRow[] = $acqOrder[0];
                    $currentRow[] = $acqOrder[1];
                    $currentRow[] = $acqOrder[2];
                    $currentRow[] = $acqOrder[3];
                }
                else
                {
                    $currentRow[] = '#####';
                    $currentRow[] = '#####';
                    $currentRow[] = '#####';
                    $currentRow[] = '#####';
                }
                
                array_shift($value['trials']);
            }
            else 
            {
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
            }
        }
        for ($indexOfTrial = 0; $indexOfTrial < 64; $indexOfTrial++)
        {
            if (isset($value['trials'][0]) && $value['trials'][0]['task'] === 'Y_C')
            {
                $currentRow[] = $value['trials'][0]['pair_comparison'];
                $currentRow[] = $value['trials'][0]['acquisition_pattern'];
                $currentRow[] = $value['trials'][0]['acquired_weights'];
                $currentRow[] = $value['trials'][0]['local_accuracy'];
                $currentRow[] = $value['trials'][0]['local_accuracy2'];
                $currentRow[] = $value['trials'][0]['chosen_option'];
                $currentRow[] = $value['trials'][0]['number_of_acquisitions'];
                $currentRow[] = $value['trials'][0]['order_of_acqusitions'];
                $currentRow[] = $value['trials'][0]['time_to_finish'] - $timeCosts['Y_C'][max($value['trials'][0]['acquisition_pattern'], 1) - 1];
                $currentRow[] = $value['trials'][0]['time_to_finish'];
                $currentRow[] = ($value['trials'][0]['time_to_finish'] - $timeCosts['Y_C'][max($value['trials'][0]['acquisition_pattern'], 1) - 1]) - $value['trials'][0]['acquisition_time'];
                $currentRow[] = $value['trials'][0]['acquisition_time'];
                $currentRow[] = $value['trials'][0]['score'];
                
                if (isset($value['trials'][0]['order_of_acqusitions']))
                {
                    $acqOrder = explode(':', $value['trials'][0]['order_of_acqusitions']);                

                    $currentRow[] = $acqOrder[0];
                    $currentRow[] = $acqOrder[1];
                    $currentRow[] = $acqOrder[2];
                    $currentRow[] = $acqOrder[3];
                }
                else
                {
                    $currentRow[] = '#####';
                    $currentRow[] = '#####';
                    $currentRow[] = '#####';
                    $currentRow[] = '#####';
                }
                
                array_shift($value['trials']);
            }
            else 
            {
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
                $currentRow[] = '#####';
            }
        }
         
         
        $result[] = $currentRow;       
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
