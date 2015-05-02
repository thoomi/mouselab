<?php

/**
 * Class to handle all db operations
 * This class will have CRUD methods for database tables
 *
 * @author Thomas Blank
 */
class DbHandler
{
    private $dbh;


    function __construct()
    {
        require_once dirname(__FILE__) . '/dbConnect.php';

        // -----------------------------------------------------------------------------
        // Connect and save the db connection
        // -----------------------------------------------------------------------------
        $db = new DbConnect();
        $this->dbh = $db->connect();
    }


    public function getAllParticipants()
    {
        $selectStatement = $this->dbh->prepare('SELECT * FROM tl_participant');
        $selectStatement->execute();

        return $selectStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getExperiments($participantId)
    {
        $selectStatement = $this->dbh->prepare('SELECT * FROM tl_experiment
                                                JOIN tl_stress_question ON tl_experiment.id = tl_stress_question.tl_experiment_id
                                                JOIN tl_satisfaction_question ON tl_experiment.id = tl_satisfaction_question.tl_experiment_id
                                                WHERE tl_experiment.tl_participant_id = :participantId
                                                ORDER BY tl_experiment.task');
        $selectStatement->bindParam(':participantId', $participantId);

        $selectStatement->execute();

        return $selectStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMaximisingAnswers($participantId)
    {
        $selectStatement = $this->dbh->prepare('SELECT * FROM tl_maximising_question
                                                WHERE tl_participant_id = :participantId');
        $selectStatement->bindParam(':participantId', $participantId);

        $selectStatement->execute();

        return $selectStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTrainingExperiments($participantId)
    {
        $selectStatement = $this->dbh->prepare('SELECT * FROM tl_experiment_training
                                                WHERE tl_participant_id = :participantId');
        $selectStatement->bindParam(':participantId', $participantId);

        $selectStatement->execute();

        return $selectStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAttributeWeights($participantId)
    {
        $selectStatement = $this->dbh->prepare('SELECT * FROM tl_attribute_weights
                                                WHERE tl_participant_id = :participantId');
        $selectStatement->bindParam(':participantId', $participantId);

        $selectStatement->execute();

        return $selectStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAdditionalQuestions($participantId)
    {
        $selectStatement = $this->dbh->prepare('SELECT * FROM tl_additional_questions
                                                WHERE tl_participant_id = :participantId');
        $selectStatement->bindParam(':participantId', $participantId);

        $selectStatement->execute();

        return $selectStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllUsers()
    {
        $selectStatement = $this->dbh->prepare('SELECT * FROM tl_user');
        $selectStatement->execute();

        return $selectStatement->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getCurrentEvaluationData()
    {
        // Get the data
        $participants = $this->getAllParticipants();

        // Setup an php data object for all data
        $data = array();

        foreach ($participants as $participant) {
            $experiments         = $this->getExperiments($participant['id']);
            $trainingExperiments = $this->getTrainingExperiments($participant['id']);
            $maximisingAnswers   = $this->getMaximisingAnswers($participant['id']);
            $attributeWeights    = $this->getAttributeWeights($participant['id']);
            $additionalQuestions = $this->getAdditionalQuestions($participant['id']);

            $data[] = array(
                'participant' => $participant,
                'experiments' => $experiments,
                'maximising'  => $maximisingAnswers,
                'trainings'   => $trainingExperiments,
                'attributes'  => $attributeWeights,
                'additional'  => $additionalQuestions
            );
        }

        return $data;
    }
}