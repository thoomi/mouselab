<?php
error_reporting(E_ALL);

require_once 'inc/config.php';
require_once 'vendor/autoload.php';
require_once 'inc/dbHandler.php';

$app = new \Slim\Slim();
$deviceDetector = new Mobile_Detect;


$app->container->singleton('db', function () {
	return new DbHandler();
});

// -----------------------------------------------------------------------------
// Return 200 for options requests on any route and pre set the access control
// headers
// -----------------------------------------------------------------------------
$app->map('/:x+', function($x) use ($app) {

	$app->response->setStatus(200);
	$app->response->headers->set('Access-Control-Allow-Origin', ALLOWED_ORIGINS);
	$app->response->headers->set('Access-Control-Allow-Headers', 'content-type');

})->via('OPTIONS');


// -----------------------------------------------------------------------------
// This route creates a new participant
// -----------------------------------------------------------------------------
$app->post('/participant/create', function() use($app) {
	$requestData = json_decode($app->request->getBody(), true);

	if (isset($requestData['participantId'])
        && isset($requestData['participantGroup'])
        && isset($requestData['participantLocation'])
        && isset($requestData['participantCondition'])
        && isset($requestData['participantPreviously'])
        && isset($requestData['participantReward']))
	{
        // Check if the participant group matches the expectations
		if (!in_array($requestData['participantGroup'], array('G1', 'G2', 'G3', 'G4', 'G5', 'G6')))
		{
			throw new Exception("Bad participant Group");
		}

        // Check the participant location for allowed values
        // L -> Labor
        // O -> Öffentlich
        // T -> Test
		if (!in_array($requestData['participantLocation'], array('L', 'O', 'T')))
		{
			throw new Exception('Bad participant location');
		}
		
		$participantCondition = $app->db->getCounterBalanceCondition();
		$participantGroup     = $app->db->getCounterBalanceGroup();
		

		$participantDatabaseId = $app->db->saveParticipant(
            $app->request->getIp(),
            date("Y-m-d H:i:s"),
            $requestData['participantId'],
            true,
            $requestData['participantLocation'],
            $participantGroup,
            $participantCondition,
            $requestData['participantPreviously'],
            $requestData['participantReward']);

		if ($participantDatabaseId)
		{
			$app->response->setStatus(200);
			$app->response->headers->set('Access-Control-Allow-Origin', ALLOWED_ORIGINS);
			$app->response->headers->set('Content-Type', 'application/json');

			echo json_encode(array(
				'participantDatabaseId' => $participantDatabaseId,
				'participantCondition'  => $participantCondition,
				'participantGroup'      => $participantGroup
			));
		}
		else
		{
			throw new Exception('Database operation failed.');
		}

	}
	else
	{
		throw new Exception("Bad request body");
	}
});



$app->post('/participant/update', function() use($app) {
	$requestData = json_decode($app->request->getBody(), true);

	if (isset($requestData['participantDatabaseId'])
        && isset($requestData['totalTime'])
        && isset($requestData['payout']))
	{
		$app->db->updateParticipant($requestData['participantDatabaseId'], $requestData['totalTime'], $requestData['payout']);

		$app->response->setStatus(200);
        $app->response->headers->set('Access-Control-Allow-Origin', ALLOWED_ORIGINS);
        $app->response->headers->set('Content-Type', 'application/json');

        echo json_encode(array());
	}
	else
	{
		throw new Exception("Bad request body");
	}
});


// -----------------------------------------------------------------------------
// This route saves the participants attribute answer values
// -----------------------------------------------------------------------------
$app->post('/participant/save/training', function() use($app){
    $requestData = json_decode($app->request->getBody(), true);

    if (isset($requestData['participantDatabaseId'])
        && isset($requestData['trainingId'])
        && isset($requestData['optionRank'])
        && isset($requestData['timeToDecision']))
    {

        $app->db->saveTraining($requestData['participantDatabaseId'], $requestData['trainingId'], $requestData['optionRank'], $requestData['timeToDecision']);

        $app->response->setStatus(200);
        $app->response->headers->set('Access-Control-Allow-Origin', ALLOWED_ORIGINS);
        $app->response->headers->set('Content-Type', 'application/json');

        echo json_encode(array());
    }
    else
    {
        throw new Exception("Bad request body");
    }
});


// -----------------------------------------------------------------------------
// This route creates a experiment entry
// -----------------------------------------------------------------------------
$app->post('/experiment/create', function() use($app){
	$requestData = json_decode($app->request->getBody(), true);

	if (isset($requestData['participantDatabaseId'])
		&& isset($requestData['task'])
		&& isset($requestData['taskPosition'])
		&& isset($requestData['trials'])
		&& isset($requestData['timeToFinish']))
	{
		if (!in_array($requestData['task'], array('X_A',  'X_B',  'X_C', 'Y_A',  'Y_B',  'Y_C')))
		{
			throw new Exception("Bad participant Group");
		}

		$experimentDbId = $app->db->saveExperiment($requestData['participantDatabaseId'], $requestData['task'], $requestData['taskPosition'], $requestData['trials'], $requestData['timeToFinish']);

		$app->response->setStatus(200);
		$app->response->headers->set('Access-Control-Allow-Origin', ALLOWED_ORIGINS);
		$app->response->headers->set('Content-Type', 'application/json');

		echo json_encode(array('experimentDbId' => $experimentDbId));
	}
	else
	{
		throw new Exception("Bad request body");
	}
});


// -----------------------------------------------------------------------------
// This route saves the answers to the stress questions
// -----------------------------------------------------------------------------
$app->post('/experiment/save/stressquestions', function() use($app) {
	$requestData = json_decode($app->request->getBody(), true);

	if (isset($requestData['participantDatabaseId'])
		&& isset($requestData['experimentDatabaseId'])
        && isset($requestData['stressAnswers'])
        && isset($requestData['stressAnswer8'])
        && isset($requestData['me4Answer'])
		&& isset($requestData['timeToAnswer']))
	{

		$app->db->saveStressQuestionAnswers(
            $requestData['participantDatabaseId'],
            $requestData['experimentDatabaseId'],
            $requestData['stressAnswers'],
            $requestData['stressAnswer8'],
            $requestData['me4Answer'],
            $requestData['timeToAnswer']);

		$app->response->setStatus(200);
		$app->response->headers->set('Access-Control-Allow-Origin', ALLOWED_ORIGINS);
		$app->response->headers->set('Content-Type', 'application/json');

		echo json_encode(array());
	}
	else
	{
		throw new Exception("Bad request body");
	}
});


// -----------------------------------------------------------------------------
// This route saves the demographic data of the user
// -----------------------------------------------------------------------------
$app->post('/participant/save/demographics', function() use($app, $deviceDetector) {
	$requestData = json_decode($app->request->getBody(), true);

	if (isset($requestData['participantDatabaseId'])
		&& isset($requestData['age'])
		&& isset($requestData['gender'])
		&& isset($requestData['graduation'])
        && isset($requestData['status'])
        && isset($requestData['apprenticeship'])
        && isset($requestData['academicDegree'])
        && isset($requestData['psychoStudies']))
	{
        // Determine users device
        $device = 1;

        if ($deviceDetector->isTablet()) { $device = 2; }
        if ($deviceDetector->isMobile() && !$deviceDetector->isTablet()) { $device = 3; }

		$app->db->saveDemographics($requestData['participantDatabaseId'], $requestData['age'], $requestData['gender'], $requestData['graduation'], $requestData['status'], $requestData['apprenticeship'], $requestData['academicDegree'], $requestData['psychoStudies'], $device);

		$app->response->setStatus(200);
		$app->response->headers->set('Access-Control-Allow-Origin', ALLOWED_ORIGINS);
		$app->response->headers->set('Content-Type', 'application/json');

		echo json_encode(array());
	}
	else
	{
		throw new Exception("Bad request body");
	}
});


// -----------------------------------------------------------------------------
// This route saves the answers to the maximising questions
// -----------------------------------------------------------------------------
$app->post('/participant/save/maximisinganswers', function() use($app) {
	$requestData = json_decode($app->request->getBody(), true);

	if (isset($requestData['participantDatabaseId'])
		&& isset($requestData['answerValues'])
		&& isset($requestData['sumAnswers']))
	{
		$app->db->saveMaximisingAnswers($requestData['participantDatabaseId'], $requestData['answerValues'], $requestData['sumAnswers']);

		$app->response->setStatus(200);
		$app->response->headers->set('Access-Control-Allow-Origin', ALLOWED_ORIGINS);
		$app->response->headers->set('Content-Type', 'application/json');

		echo json_encode(array());
	}
	else
	{
		throw new Exception("Bad request body");
	}
});


// -----------------------------------------------------------------------------
// This route saves the answers to the resilienceanswers questions
// -----------------------------------------------------------------------------
$app->post('/participant/save/resilienceanswers', function() use($app) {
	$requestData = json_decode($app->request->getBody(), true);

	if (isset($requestData['participantDatabaseId'])
		&& isset($requestData['answerValues'])
		&& isset($requestData['sumAnswers']))
	{
		$app->db->saveResilienceAnswers($requestData['participantDatabaseId'], $requestData['answerValues'], $requestData['sumAnswers']);

		$app->response->setStatus(200);
		$app->response->headers->set('Access-Control-Allow-Origin', ALLOWED_ORIGINS);
		$app->response->headers->set('Content-Type', 'application/json');

		echo json_encode(array());
	}
	else
	{
		throw new Exception("Bad request body");
	}
});

// -----------------------------------------------------------------------------
// This route saves the answers to the meta answers questions
// -----------------------------------------------------------------------------
$app->post('/participant/save/metaanswers', function() use($app) {
	$requestData = json_decode($app->request->getBody(), true);

	if (isset($requestData['participantDatabaseId'])
		&& isset($requestData['answerValues']))
	{
		$app->db->saveMetaAnswers($requestData['participantDatabaseId'], $requestData['answerValues']);

		$app->response->setStatus(200);
		$app->response->headers->set('Access-Control-Allow-Origin', ALLOWED_ORIGINS);
		$app->response->headers->set('Content-Type', 'application/json');

		echo json_encode(array());
	}
	else
	{
		throw new Exception("Bad request body");
	}
});

// -----------------------------------------------------------------------------
// This route saves the answers to the nfc answers questions
// -----------------------------------------------------------------------------
$app->post('/participant/save/nfcanswers', function() use($app) {
	$requestData = json_decode($app->request->getBody(), true);

	if (isset($requestData['participantDatabaseId'])
		&& isset($requestData['answerValues'])
		&& isset($requestData['sumAnswers']))
	{
		$app->db->saveNfcAnswers($requestData['participantDatabaseId'], $requestData['answerValues'], $requestData['sumAnswers']);

		$app->response->setStatus(200);
		$app->response->headers->set('Access-Control-Allow-Origin', ALLOWED_ORIGINS);
		$app->response->headers->set('Content-Type', 'application/json');

		echo json_encode(array());
	}
	else
	{
		throw new Exception("Bad request body");
	}
});

// -----------------------------------------------------------------------------
// This route saves the answers to the risk answers questions
// -----------------------------------------------------------------------------
$app->post('/participant/save/riskanswers', function() use($app) {
	$requestData = json_decode($app->request->getBody(), true);

	if (isset($requestData['participantDatabaseId'])
		&& isset($requestData['answerValues']))
	{
		$app->db->saveRiskAnswers($requestData['participantDatabaseId'], $requestData['answerValues']);

		$app->response->setStatus(200);
		$app->response->headers->set('Access-Control-Allow-Origin', ALLOWED_ORIGINS);
		$app->response->headers->set('Content-Type', 'application/json');

		echo json_encode(array());
	}
	else
	{
		throw new Exception("Bad request body");
	}
});

// -----------------------------------------------------------------------------
// This route creates a new user entry
// -----------------------------------------------------------------------------
$app->post('/user/create', function() use($app) {
	$requestData = json_decode($app->request->getBody(), true);

	if (isset($requestData['email'])
		&& isset($requestData['participateInOther'])
		&& isset($requestData['comments'])
		&& isset($requestData['location']))
	{
		$app->db->saveUser($requestData['email'], $requestData['participateInOther'], $requestData['comments'], $requestData['location']);

		$app->response->setStatus(200);
		$app->response->headers->set('Access-Control-Allow-Origin', ALLOWED_ORIGINS);
		$app->response->headers->set('Content-Type', 'application/json');

		echo json_encode(array());
	}
	else
	{
		throw new Exception("Bad request body");
	}
});


// -----------------------------------------------------------------------------
// Execute the application
// -----------------------------------------------------------------------------
$app->run();
