<?php
//error_reporting(E_ALL);

require_once 'inc/config.php';
require_once 'vendor/autoload.php';
require_once 'inc/dbHandler.php';

$app = new \Slim\Slim();


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

	if (isset($requestData['participantId']) && isset($requestData['participantGroup']) && isset($requestData['participantLocation']))
	{
		if (!in_array($requestData['participantGroup'], array('G1', 'G2', 'G3')))
		{
			throw new Exception("Bad participant Group");
		}

		if (!in_array($requestData['participantLocation'], array('L', 'O', 'T')))
		{
			throw new Exception('Bad participant location');
		}


		$participantDatabaseId = $app->db->saveParticipant($app->request->getIp(), date("Y-m-d H:i:s"), $requestData['participantId'], true, $requestData['participantLocation'], $requestData['participantGroup']);

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
// This route creates a experiment entry
// -----------------------------------------------------------------------------
$app->post('/experiment/create', function() use($app){
	$requestData = json_decode($app->request->getBody(), true);

	if (isset($requestData['participantDatabaseId'])
		&& isset($requestData['condition'])
		&& isset($requestData['conditionPosition'])
		&& isset($requestData['chosenOptionRank'])
		&& isset($requestData['timeToDecision']))
	{
		if (!in_array($requestData['condition'], array('A', 'B', 'C')))
		{
			throw new Exception("Bad participant Group");
		}

		$experimentDbId = $app->db->saveExperiment($requestData['participantDatabaseId'], $requestData['condition'], $requestData['conditionPosition'], $requestData['chosenOptionRank'], $requestData['timeToDecision']);

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

$app->post('/experiment/save/stressquestions', function() use($app) {
	$requestData = json_decode($app->request->getBody(), true);

	if (isset($requestData['participantDatabaseId'])
		&& isset($requestData['experimentDatabaseId'])
		&& isset($requestData['valueQuestion1'])
		&& isset($requestData['valueQuestion2'])
		&& isset($requestData['questionSum']))
	{

		$app->db->saveStressQuestionAnswers($requestData['participantDatabaseId'], $requestData['experimentDatabaseId'], $requestData['valueQuestion1'], $requestData['valueQuestion2'], $requestData['questionSum']);

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

$app->post('/participant/save/demographics', function() use($app) {
	$requestData = json_decode($app->request->getBody(), true);

	if (isset($requestData['participantDatabaseId'])
		&& isset($requestData['age'])
		&& isset($requestData['gender'])
		&& isset($requestData['graduation']))
	{
		$app->db->saveDemographics($requestData['participantDatabaseId'], $requestData['age'], $requestData['gender'], $requestData['graduation']);

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

$app->post('/participant/save/maximisinganswers', function() use($app) {
	$requestData = json_decode($app->request->getBody(), true);

	if (isset($requestData['participantDatabaseId'])
		&& isset($requestData['answerValues'])
		&& isset($requestData['sumAnswers'])
		&& isset($requestData['totalTime']))
	{
		$app->db->saveMaximisingAnswers($requestData['participantDatabaseId'], $requestData['answerValues'], $requestData['sumAnswers'], $requestData['totalTime']);

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
