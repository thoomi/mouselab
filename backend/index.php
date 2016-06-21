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
        'genderShare'                  => $app->db->getGenderShare(),
        'averageAge'                   => $app->db->getAverageAge()
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
        
        
        'MaxT-1',
        'MaxT-2',
        'MaxT-3',
        'MaxT-4',
        'MaxT-5',
        'MaxT-6',
		'MaxT-MW',				// maximising_sum
            
        'Res-1',
        'Res-2',
        'Res-3',
        'Res-4',
        'Res-5',
        'Res-6',
        'Res-7',
        'Res-8',
        'Res-9',
        'Res-10',
        'Res-11',
		'Res-MW',				// maximising_sum
		
		'Risk-1',
        'Risk-2',
        'Risk-3',
        'Risk-4',
        
        'Nfc-1',
        'Nfc-2',
        'Nfc-3',
        'Nfc-4',
		'Nfc-MW',				// maximising_sum
            
        'Meta-1',
        'Meta-2',
        'Meta-3',
        'Meta-4',
        
        
		'Position-ExpBA',	     // task_pos
		'lt_ExpG-A',	             // Verfügbare Zeit
        'numberTrials-A',          // Anzahl trials in A
        'Stress-1-A',
        'Stress-2-A',
        'Stress-3-A',
        'Stress-4-A',
        'Stress-5-A',
        'Stress-6-A',
        'Stress-7-A',
        'Stress-MW-A',
        'Stress-Time-A',
        'Time-ExpBA',
        'ppa-A',

		'Position-ExpBB',	     // task_pos
		'lt_ExpG-B',	             // Verfügbare Zeit
        'numberTrials-B',          // Anzahl trials in A
        'Stress-1-B',
        'Stress-2-B',
        'Stress-3-B',
        'Stress-4-B',
        'Stress-5-B',
        'Stress-6-B',
        'Stress-7-B',
        'Stress-MW-B',
        'Stress-Time-B',
        'Time-ExpBB',
        'ppa-B',

		'Position-ExpBC',	     // task_pos
		'lt_ExpG-C',	             // Verfügbare Zeit
        'numberTrials-C',          // Anzahl trials in A
        'Stress-1-C',
        'Stress-2-C',
        'Stress-3-C',
        'Stress-4-C',
        'Stress-5-C',
        'Stress-6-C',
        'Stress-7-C',
        'Stress-MW-C',
        'Stress-Time-C',
        'Time-ExpBC',
        'ppa-C'
	);
    
    // Generate Labels for Trials
    for ($indexOfTrial = 1; $indexOfTrial < 65; $indexOfTrial++)
    {
        $firstRow[] = 'PC_A_' . $indexOfTrial; // pair compairison
        $firstRow[] = 'AS_A_' . $indexOfTrial; // acquisition_pattern
        $firstRow[] = 'aw_SUM_A_' . $indexOfTrial; // weight sum
        $firstRow[] = 'laa_A_' . $indexOfTrial; // local_accuracy
        $firstRow[] = 'Gew_Opt_A_' . $indexOfTrial; // chosen option
        $firstRow[] = 'apt_A_' . $indexOfTrial; // number of acquisitions
        $firstRow[] = 'AC_Order_A_' . $indexOfTrial; // acquisition order
        $firstRow[] = 'tt_A_' . $indexOfTrial; // trial time to finish
        $firstRow[] = 'at_SUM_A_' . $indexOfTrial; // sum aqcuisition times
        $firstRow[] = 'ppt_A_' . $indexOfTrial; //  trial score
        
        $firstRow[] = 'AC_A_' . $indexOfTrial . '_1';
        $firstRow[] = 'AC_A_' . $indexOfTrial . '_2'; 
        $firstRow[] = 'AC_A_' . $indexOfTrial . '_3';
        $firstRow[] = 'AC_A_' . $indexOfTrial . '_4'; 
    }
    
    for ($indexOfTrial = 1; $indexOfTrial < 65; $indexOfTrial++)
    {
        $firstRow[] = 'PC_B_' . $indexOfTrial; // pair compairison
        $firstRow[] = 'AS_B_' . $indexOfTrial; // acquisition_pattern
        $firstRow[] = 'aw_SUM_B_' . $indexOfTrial; // weight sum
        $firstRow[] = 'laa_B_' . $indexOfTrial; // local_accuracy
        $firstRow[] = 'Gew_Opt_B_' . $indexOfTrial; // chosen option
        $firstRow[] = 'apt_B_' . $indexOfTrial; // number of acquisitions
        $firstRow[] = 'AC_Order_B_' . $indexOfTrial; // acquisition order
        $firstRow[] = 'tt_B_' . $indexOfTrial; // trial time to finish
        $firstRow[] = 'at_SUM_B_' . $indexOfTrial; // sum aqcuisition times
        $firstRow[] = 'ppt_B_' . $indexOfTrial; //  trial score
        
        $firstRow[] = 'AC_B_' . $indexOfTrial . '_1';
        $firstRow[] = 'AC_B_' . $indexOfTrial . '_2'; 
        $firstRow[] = 'AC_B_' . $indexOfTrial . '_3';
        $firstRow[] = 'AC_B_' . $indexOfTrial . '_4'; 
    }
    
    for ($indexOfTrial = 1; $indexOfTrial < 65; $indexOfTrial++)
    {
        $firstRow[] = 'PC_C_' . $indexOfTrial; // pair compairison
        $firstRow[] = 'AS_C_' . $indexOfTrial; // acquisition_pattern
        $firstRow[] = 'aw_SUM_C_' . $indexOfTrial; // weight sum
        $firstRow[] = 'laa_C_' . $indexOfTrial; // local_accuracy
        $firstRow[] = 'Gew_Opt_C_' . $indexOfTrial; // chosen option
        $firstRow[] = 'apt_C_' . $indexOfTrial; // number of acquisitions
        $firstRow[] = 'AC_Order_C_' . $indexOfTrial; // acquisition order
        $firstRow[] = 'tt_C_' . $indexOfTrial; // trial time to finish
        $firstRow[] = 'at_SUM_C_' . $indexOfTrial; // sum aqcuisition times
        $firstRow[] = 'ppt_C_' . $indexOfTrial; // trial score
        
        $firstRow[] = 'AC_C_' . $indexOfTrial . '_1';
        $firstRow[] = 'AC_C_' . $indexOfTrial . '_2'; 
        $firstRow[] = 'AC_C_' . $indexOfTrial . '_3';
        $firstRow[] = 'AC_C_' . $indexOfTrial . '_4'; 
    }
    
    $result[] = $firstRow;
    
    
	foreach ($data as $value) {
	    $totalScore = 0;
	    
		$expA = array(
			'task_pos'               => '#',
			'time_to_finish'         => '#',
            'stress_q1'              => '#',
            'stress_q2'              => '#',
            'stress_q3'              => '#',
            'stress_q4'              => '#',
            'stress_q5'              => '#',
            'stress_q6'              => '#',
            'stress_q7'              => '#',
            'stress_sum'             => '#',
            'time_to_answer'         => '#',
            'numberOfTrials'         => '#',
            'score'                  => '#',
            
		);
		if (isset($value['experiments'][0])) { $expA = $value['experiments'][0]; $totalScore += $expA['score']; }

		$expB = array(
			'task_pos'               => '#',
			'time_to_finish'         => '#',
            'stress_q1'              => '#',
            'stress_q2'              => '#',
            'stress_q3'              => '#',
            'stress_q4'              => '#',
            'stress_q5'              => '#',
            'stress_q6'              => '#',
            'stress_q7'              => '#',
            'stress_sum'             => '#',
            'time_to_answer'         => '#',
            'numberOfTrials'         => '#',
            'score'                  => '#',
		);
		if (isset($value['experiments'][1])) { $expB = $value['experiments'][1]; $totalScore += $expB['score']; }

		$expC = array(
			'task_pos'               => '#',
			'time_to_finish'         => '#',
            'stress_q1'              => '#',
            'stress_q2'              => '#',
            'stress_q3'              => '#',
            'stress_q4'              => '#',
            'stress_q5'              => '#',
            'stress_q6'              => '#',
            'stress_q7'              => '#',
            'stress_sum'             => '#',
            'time_to_answer'         => '#',
            'numberOfTrials'         => '#',
            'score'                  => '#',
		);
		if (isset($value['experiments'][2])) { $expC = $value['experiments'][2]; $totalScore += $expC['score']; }

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
            'q_num_2' => '#',
            'q_num_3' => '#',
            'q_num_4' => '#'
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


		$currentRow = array(
			$value['participant']['participated_at'],
			$value['participant']['id'],
			$value['participant']['participation_id'],
            $value['participant']['participation_condition'],
			$value['participant']['participation_group'],
            
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
			
			
			$maximising['q_num_1'],
            $maximising['q_num_2'],
            $maximising['q_num_3'],
            $maximising['q_num_4'],
            $maximising['q_num_5'],
            $maximising['q_num_6'],
            $maximising['q_sum'],
            
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
            $resiliance['q_sum'],
            
            $risk['q_num_1'],
            $risk['q_num_2'],
            $risk['q_num_3'],
            $risk['q_num_4'],
            
            $nfc['q_num_1'],
            $nfc['q_num_2'],
            $nfc['q_num_3'],
            $nfc['q_num_4'],
            $nfc['q_sum'],
            
            $meta['q_num_1'],
            $meta['q_num_2'],
            $meta['q_num_3'],
            $meta['q_num_4'],
            
            
			$expA['task_pos'],
			171520,
			$expA['numberOfTrials'],
            $expA['stress_q1'],
            $expA['stress_q2'],
            $expA['stress_q3'],
            $expA['stress_q4'],
            $expA['stress_q5'],
            $expA['stress_q6'],
            $expA['stress_q7'],
            $expA['stress_sum'],
            $expA['time_to_answer'],
            $expA['time_to_finish'],
            $expA['score'],

			$expB['task_pos'],
			235520,
			$expB['numberOfTrials'],
            $expB['stress_q1'],
            $expB['stress_q2'],
            $expB['stress_q3'],
            $expB['stress_q4'],
            $expB['stress_q5'],
            $expB['stress_q6'],
            $expB['stress_q7'],
            $expB['stress_sum'],
            $expB['time_to_answer'],
            $expB['time_to_finish'],
            $expB['score'],

			$expC['task_pos'],
			299520,
			$expC['numberOfTrials'],
            $expC['stress_q1'],
            $expC['stress_q2'],
            $expC['stress_q3'],
            $expC['stress_q4'],
            $expC['stress_q5'],
            $expC['stress_q6'],
            $expC['stress_q7'],
            $expC['stress_sum'],
            $expC['time_to_answer'],
            $expC['time_to_finish'],
            $expC['score']
		);
		
	    // Attach trial data
	    for ($indexOfTrial = 0; $indexOfTrial < 64; $indexOfTrial++)
        {
            if (isset($value['trials'][0]) && $value['trials'][0]['task'] === 'A')
            {
                $currentRow[] = $value['trials'][0]['pair_comparison'];
                $currentRow[] = $value['trials'][0]['acquisition_pattern'];
                $currentRow[] = $value['trials'][0]['acquired_weights'];
                $currentRow[] = $value['trials'][0]['local_accuracy'];
                $currentRow[] = $value['trials'][0]['chosen_option'];
                $currentRow[] = $value['trials'][0]['number_of_acquisitions'];
                $currentRow[] = $value['trials'][0]['order_of_acqusitions'];
                $currentRow[] = $value['trials'][0]['time_to_finish'];
                $currentRow[] = $value['trials'][0]['acquisition_time'];
                $currentRow[] = $value['trials'][0]['score'];
                
                $acqOrder = explode(':', $value['trials'][0]['order_of_acqusitions']);
                
                $currentRow[] = $acqOrder[0];
                $currentRow[] = $acqOrder[1];
                $currentRow[] = $acqOrder[2];
                $currentRow[] = $acqOrder[3];
                
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
            }
        }
        
        for ($indexOfTrial = 0; $indexOfTrial < 64; $indexOfTrial++)
        {
            if (isset($value['trials'][0]) && $value['trials'][0]['task'] === 'B')
            {
                $currentRow[] = $value['trials'][0]['pair_comparison'];
                $currentRow[] = $value['trials'][0]['acquisition_pattern'];
                $currentRow[] = $value['trials'][0]['acquired_weights'];
                $currentRow[] = $value['trials'][0]['local_accuracy'];
                $currentRow[] = $value['trials'][0]['chosen_option'];
                $currentRow[] = $value['trials'][0]['number_of_acquisitions'];
                $currentRow[] = $value['trials'][0]['order_of_acqusitions'];
                $currentRow[] = $value['trials'][0]['time_to_finish'];
                $currentRow[] = $value['trials'][0]['acquisition_time'];
                $currentRow[] = $value['trials'][0]['score'];
                
                $acqOrder = explode(':', $value['trials'][0]['order_of_acqusitions']);
                
                $currentRow[] = $acqOrder[0];
                $currentRow[] = $acqOrder[1];
                $currentRow[] = $acqOrder[2];
                $currentRow[] = $acqOrder[3];
                
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
            }
        }
        for ($indexOfTrial = 0; $indexOfTrial < 64; $indexOfTrial++)
        {
            if (isset($value['trials'][0]) && $value['trials'][0]['task'] === 'C')
            {
                $currentRow[] = $value['trials'][0]['pair_comparison'];
                $currentRow[] = $value['trials'][0]['acquisition_pattern'];
                $currentRow[] = $value['trials'][0]['acquired_weights'];
                $currentRow[] = $value['trials'][0]['local_accuracy'];
                $currentRow[] = $value['trials'][0]['chosen_option'];
                $currentRow[] = $value['trials'][0]['number_of_acquisitions'];
                $currentRow[] = $value['trials'][0]['order_of_acqusitions'];
                $currentRow[] = $value['trials'][0]['time_to_finish'];
                $currentRow[] = $value['trials'][0]['acquisition_time'];
                $currentRow[] = $value['trials'][0]['score'];
                
                $acqOrder = explode(':', $value['trials'][0]['order_of_acqusitions']);
                
                $currentRow[] = $acqOrder[0];
                $currentRow[] = $acqOrder[1];
                $currentRow[] = $acqOrder[2];
                $currentRow[] = $acqOrder[3];
                
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
