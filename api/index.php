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
$app->post('/participant/create', function() use($app)
{
	$requestData = json_decode($app->request->getBody(), true);

	if (isset($requestData['participantId'])
        && isset($requestData['participantGroup'])
        && isset($requestData['participantLocation'])
        && isset($requestData['participantCondition'])
        && isset($requestData['participantPreviously']))
	{
        // Check if the participant group matches the expectations
		if (!in_array($requestData['participantGroup'], array('G1', 'G2')))
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


		$participantDatabaseId = $app->db->saveParticipant(
            $app->request->getIp(),
            date("Y-m-d H:i:s"),
            $requestData['participantId'],
            true,
            $requestData['participantLocation'],
            $requestData['participantGroup'],
            $requestData['participantCondition'],
            $requestData['participantPreviously']);

		if ($participantDatabaseId)
		{
			$app->response->setStatus(200);
			$app->response->headers->set('Access-Control-Allow-Origin', ALLOWED_ORIGINS);
			$app->response->headers->set('Content-Type', 'application/json');

			echo json_encode(array('participantDatabaseId' => $participantDatabaseId));
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


// -----------------------------------------------------------------------------
// This route saves the participants attribute answer values
// -----------------------------------------------------------------------------
$app->post('/participant/save/attributeAnswers', function() use($app){
    $requestData = json_decode($app->request->getBody(), true);

    if (isset($requestData['participantDatabaseId'])
        && isset($requestData['answerValues'])
        && isset($requestData['sumAnswers']))
    {

        $app->db->saveAttributeAnswers($requestData['participantDatabaseId'], $requestData['answerValues'], $requestData['sumAnswers']);

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
		&& isset($requestData['condition'])
		&& isset($requestData['conditionPosition'])
		&& isset($requestData['chosenOptionRank'])
		&& isset($requestData['timeToDecision'])
        && isset($requestData['chosenOptionPosition']))
	{
		if (!in_array($requestData['condition'], array('A', 'B', 'C')))
		{
			throw new Exception("Bad participant Group");
		}

		$experimentDbId = $app->db->saveExperiment($requestData['participantDatabaseId'], $requestData['condition'], $requestData['conditionPosition'], $requestData['chosenOptionRank'], $requestData['timeToDecision'], $requestData['chosenOptionPosition']);

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
		&& isset($requestData['satisfactionAnswers'])
		&& isset($requestData['satisfactionAnswersSum'])
        && isset($requestData['stressAnswers'])
        && isset($requestData['stressAnswersSum'])
		&& isset($requestData['decisionByStrategy']))
	{

		$app->db->saveStressQuestionAnswers(
            $requestData['participantDatabaseId'],
            $requestData['experimentDatabaseId'],
            $requestData['satisfactionAnswers'],
            $requestData['satisfactionAnswersSum'],
            $requestData['stressAnswers'],
            $requestData['stressAnswersSum'],
            $requestData['decisionByStrategy']);

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
        && isset($requestData['academicDegree']))
	{
        // Determine users device
        $device = 1;

        if ($deviceDetector->isTablet()) { $device = 2; }
        if ($deviceDetector->isMobile() && !$deviceDetector->isTablet()) { $device = 3; }

		$app->db->saveDemographics($requestData['participantDatabaseId'], $requestData['age'], $requestData['gender'], $requestData['graduation'], $requestData['status'], $requestData['apprenticeship'], $requestData['academicDegree'], $device);

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
// This route saves the answers to the maximising questions
// -----------------------------------------------------------------------------
$app->post('/participant/save/additionalanswers', function() use($app) {
    $requestData = json_decode($app->request->getBody(), true);

    if (isset($requestData['participantDatabaseId'])
        && isset($requestData['environmentAnswers'])
        && isset($requestData['participantAnswers'])
        && isset($requestData['totalTime']))
    {
        $app->db->saveParticipationAnswers($requestData['participantDatabaseId'], $requestData['environmentAnswers'], $requestData['participantAnswers'], $requestData['totalTime']);

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
