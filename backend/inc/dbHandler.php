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

    public function getNumberOfParticipants()
    {
        $selectStatement = $this->dbh->prepare('SELECT COUNT(DISTINCT id) as total FROM tl_participant');
        $selectStatement->execute();

        return $selectStatement->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getNumberOfPreviousParticipants()
    {
        $selectStatement = $this->dbh->prepare('SELECT COUNT(DISTINCT id) as total FROM tl_participant WHERE previous_participant = 1');
        $selectStatement->execute();

        return $selectStatement->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getDropOuts()
    {
        $selectStatement = $this->dbh->prepare('SELECT COUNT(DISTINCT id) as total FROM tl_participant WHERE dropout = 1');
        $selectStatement->execute();

        return $selectStatement->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getAverageTotalTime()
    {
        $selectStatement = $this->dbh->prepare('SELECT AVG(total_time) as total FROM tl_participant');
        $selectStatement->execute();

        return $selectStatement->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getAverageMaximising()
    {
        $selectStatement = $this->dbh->prepare('SELECT AVG(q_sum) as total FROM tl_participant
                                                JOIN tl_maximising_question ON tl_participant.id = tl_maximising_question.tl_participant_id');
        $selectStatement->execute();

        return $selectStatement->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getGenderShare()
    {
        $genderShare = array('male' => array(), 'female' => array());

        $selectStatement = $this->dbh->prepare('SELECT count(gender) as total FROM tl_participant
                                                JOIN tl_demographics ON tl_participant.id = tl_demographics.tl_participant_id
                                                WHERE gender = 0');
        $selectStatement->execute();
        $genderShare['male'] = $selectStatement->fetch(PDO::FETCH_ASSOC)['total'];


        $selectStatement = $this->dbh->prepare('SELECT count(gender) as total FROM tl_participant
                                                JOIN tl_demographics ON tl_participant.id = tl_demographics.tl_participant_id
                                                WHERE gender = 1');
        $selectStatement->execute();
        $genderShare['female'] = $selectStatement->fetch(PDO::FETCH_ASSOC)['total'];

        return $genderShare;
    }

    public function getAverageAge()
    {
        $selectStatement = $this->dbh->prepare('SELECT AVG(age) as total FROM tl_participant
                                                JOIN tl_demographics ON tl_participant.id = tl_demographics.tl_participant_id');
        $selectStatement->execute();

        return $selectStatement->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getUserStats()
    {
        // Willing to participate in other
        $selectStatement = $this->dbh->prepare('SELECT COUNT(*) as total FROM tl_user WHERE participate_in_other = 1');
        $selectStatement->execute();

        $participateInOther = $selectStatement->fetch(PDO::FETCH_ASSOC)['total'];

        // Number of email adresses saved
        $selectStatement = $this->dbh->prepare('SELECT COUNT(*) as total FROM tl_user');
        $selectStatement->execute();

        $numberOfEmails = $selectStatement->fetch(PDO::FETCH_ASSOC)['total'];

        // Get newest comment
        $selectStatement = $this->dbh->prepare('SELECT comments FROM tl_user ORDER BY id DESC LIMIT 1');
        $selectStatement->execute();

        $lastComment = $selectStatement->fetch(PDO::FETCH_ASSOC)['comments'];

        return array(
            'participateInOther' => $participateInOther,
            'numberOfEmails'     => $numberOfEmails,
            'lastComment'        => $lastComment
        );
    }

    public function getDataByStrategy($strategy)
    {
        $data = array();

        // Get participant count per strategy
        $selectStatement = $this->dbh->prepare('SELECT COUNT(*) as total FROM tl_participant
                                                WHERE tl_participant.Participation_condition = :strategy');
        $selectStatement->bindParam(':strategy', $strategy);
        $selectStatement->execute();
        $data['numberOfParticipants'] = $selectStatement->fetch(PDO::FETCH_ASSOC)['total'];


        // Get data for tasks
        $tasks = array('A', 'B', 'C');
        foreach($tasks as $task)
        {
            $selectStatement = $this->dbh->prepare('SELECT
                                                    COUNT(CASE WHEN tl_experiment.time_to_decision = 0 THEN 1 ELSE null END) as timeouts,
                                                    AVG(CASE WHEN tl_experiment.time_to_decision = 0 THEN null ELSE tl_experiment.time_to_decision END) as decision_time,
                                                    AVG(CASE WHEN tl_experiment.time_to_decision = 0 THEN null ELSE tl_experiment.chosen_option_rank END) as chosen_option,
                                                    AVG(tl_satisfaction_question.q_sum) as satisfaction,
                                                    AVG(tl_stress_question.q_sum) as stress
                                                FROM tl_participant
                                                JOIN tl_experiment ON tl_experiment.tl_participant_id = tl_participant.id
                                                JOIN tl_satisfaction_question ON tl_satisfaction_question.tl_experiment_id = tl_experiment.id
                                                JOIN tl_stress_question ON tl_stress_question.tl_experiment_id = tl_experiment.id
                                                WHERE tl_participant.Participation_condition = :strategy
                                                AND tl_experiment.task = :task');
            $selectStatement->bindParam(':strategy', $strategy);
            $selectStatement->bindParam(':task', $task);

            $selectStatement->execute();

            $data[$task] = $selectStatement->fetch(PDO::FETCH_ASSOC);
        }

        return $data;
    }

    public function getExperiments($participantId)
    {
        $selectStatement = $this->dbh->prepare('SELECT
                                                    tl_experiment.task,
                                                    tl_experiment.task_pos AS task_pos,
                                                    tl_experiment.chosen_option_position AS chosen_option_position,
                                                    tl_experiment.chosen_option_rank AS chosen_option_rank,
                                                    tl_experiment.time_to_decision AS time_to_decision,
                                                    tl_satisfaction_question.q_sum AS satisfaction_sum,
                                                    tl_stress_question.q_sum AS stress_sum,
                                                    tl_experiment.q_decision_by_guideline AS by_guide_line

                                                FROM tl_experiment
                                                JOIN tl_stress_question ON tl_stress_question.tl_experiment_id = tl_experiment.id
                                                JOIN tl_satisfaction_question ON tl_satisfaction_question.tl_experiment_id = tl_experiment.id
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

    public function getStressQuestions($participantId)
    {
        $selectStatement = $this->dbh->prepare('SELECT * FROM tl_stress_question
                                                WHERE tl_participant_id = :participantId');
        $selectStatement->bindParam(':participantId', $participantId);

        $selectStatement->execute();

        return $selectStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSatisfactionQuestions($participantId)
    {
        $selectStatement = $this->dbh->prepare('SELECT * FROM tl_satisfaction_question
                                                WHERE tl_participant_id = :participantId');
        $selectStatement->bindParam(':participantId', $participantId);

        $selectStatement->execute();

        return $selectStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDemographicData($participantId)
    {
        $selectStatement = $this->dbh->prepare('SELECT * FROM tl_demographics
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
            $experiments           = $this->getExperiments($participant['id']);
            $maximisingAnswers     = $this->getMaximisingAnswers($participant['id']);
            $attributeWeights      = $this->getAttributeWeights($participant['id']);
            $additionalQuestions   = $this->getAdditionalQuestions($participant['id']);
            $demographics          = $this->getDemographicData($participant['id']);

            $data[] = array(
                'participant'  => $participant,
                'experiments'  => $experiments,
                'maximising'   => $maximisingAnswers,
                'attributes'   => $attributeWeights,
                'additional'   => $additionalQuestions,
                'demographic'  => $demographics
            );
        }

        return $data;
    }
}