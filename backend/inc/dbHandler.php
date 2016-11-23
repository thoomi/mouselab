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
    
    public function getPayoutParticipants()
    {
        $selectStatement = $this->dbh->prepare('SELECT *, 
                                                       (UNIX_TIMESTAMP(participated_at) * 1000 + total_time) AS endtime 
                                                FROM `tl_participant` 
                                                WHERE reward = 2
                                                ORDER BY endtime DESC');
        $selectStatement->execute();

        return $selectStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getPayoutStats()
    {
        $selectStatement = $this->dbh->prepare('SELECT 
                                                    AVG(payout) AS average,
                                                    SUM(payout) AS sum
                                                FROM tl_participant
                                                WHERE reward = 2');
        $selectStatement->execute();

        return $selectStatement->fetch(PDO::FETCH_ASSOC);
    }

    public function getNumberOfParticipants()
    {
        $selectStatement = $this->dbh->prepare('SELECT COUNT(DISTINCT id) as total FROM tl_participant');
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
    
    public function getRewardShare()
    {
        $selectStatement = $this->dbh->prepare('SELECT COUNT(CASE reward WHEN 1 then 1 ELSE null END) AS reward1,
                                                	   COUNT(CASE reward WHEN 2 then 1 ELSE null END) AS reward2
                                                FROM `tl_participant`');
        $selectStatement->execute();
        $rewards = $selectStatement->fetch(PDO::FETCH_ASSOC);

        return $rewards;
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


    public function getExperiments($participantId)
    {
            $selectStatement = $this->dbh->prepare('SELECT 
                                                    	tl_experiment.task AS task,
                                                    	tl_experiment.task_pos AS task_pos,
                                                    	tl_experiment.time_to_finish AS time_to_finish,
                                                    
                                                    	tl_stress_question.q_num_1 AS stress_q1,
                                                    	tl_stress_question.q_num_2 AS stress_q2,
                                                    	tl_stress_question.q_num_3 AS stress_q3,
                                                    	tl_stress_question.q_me4   AS stress_q4,
                                                    	tl_stress_question.q_num_8 AS stress_q8,
                                                    	tl_stress_question.time_to_answer AS time_to_answer,
                                                    	
                                                    	COUNT(tl_trial.id) AS numberOfTrials,
                                                    	SUM(tl_trial.score) AS score,
                                                    	SUM(tl_trial.time_to_finish) AS trialTimeSum,
                                                    	SUM(tl_trial.acquisition_time) AS acquisitionSum
                                                    	
                                                    FROM tl_experiment
                                                    LEFT JOIN tl_stress_question ON tl_stress_question.tl_experiment_id = tl_experiment.id
                                                    LEFT JOIN tl_trial ON tl_trial.tl_experiment_id = tl_experiment.id
                                                    WHERE tl_experiment.tl_participant_id = :participantId
                                                    GROUP BY tl_experiment.task
                                                    ORDER BY tl_experiment.task');
        $selectStatement->bindParam(':participantId', $participantId);

        $selectStatement->execute();
        
        return $selectStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getTrials($participantId)
    {
        $selectStatement = $this->dbh->prepare('SELECT 
                                                    tl_experiment.task AS task,
                                                    tl_trial.number AS number,
                                                    tl_trial.pair_comparison AS pair_comparison,
                                                    tl_trial.number_of_acquisitions AS number_of_acquisitions,
                                                    tl_trial.order_of_acqusitions AS order_of_acqusitions,
                                                    tl_trial.time_to_finish AS time_to_finish,
                                                    tl_trial.acquisition_time AS acquisition_time,
                                                    tl_trial.acquired_weights AS acquired_weights,
                                                    tl_trial.local_accuracy AS local_accuracy,
                                                    tl_trial.local_accuracy2 AS local_accuracy2,
                                                    tl_trial.acquisition_pattern AS acquisition_pattern,
                                                    tl_trial.chosen_option AS chosen_option,
                                                    tl_trial.score AS score
                                                    
                                                FROM tl_experiment
                                                LEFT JOIN tl_trial ON tl_trial.tl_experiment_id = tl_experiment.id
                                                WHERE tl_experiment.tl_participant_id = :participantId
                                                ORDER BY tl_experiment.task');
        $selectStatement->bindParam(':participantId', $participantId);                                        
                                                
        $selectStatement->execute();

        return $selectStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMaximisingAnswers($participantId)
    {
        $selectStatement = $this->dbh->prepare('SELECT * FROM tl_maximizing_question
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


    public function getDemographicData($participantId)
    {
        $selectStatement = $this->dbh->prepare('SELECT * FROM tl_demographics
                                                WHERE tl_participant_id = :participantId');
        $selectStatement->bindParam(':participantId', $participantId);

        $selectStatement->execute();

        return $selectStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getResilianceData($participantId)
    {
        $selectStatement = $this->dbh->prepare('SELECT * FROM tl_resilience_question
                                                WHERE tl_participant_id = :participantId');
        $selectStatement->bindParam(':participantId', $participantId);

        $selectStatement->execute();

        return $selectStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getRiskData($participantId)
    {
        $selectStatement = $this->dbh->prepare('SELECT * FROM tl_risk_question
                                                WHERE tl_participant_id = :participantId');
        $selectStatement->bindParam(':participantId', $participantId);

        $selectStatement->execute();

        return $selectStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getNfcData($participantId)
    {
        $selectStatement = $this->dbh->prepare('SELECT * FROM tl_nfc_question
                                                WHERE tl_participant_id = :participantId');
        $selectStatement->bindParam(':participantId', $participantId);

        $selectStatement->execute();

        return $selectStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getMetaData($participantId)
    {
        $selectStatement = $this->dbh->prepare('SELECT * FROM tl_meta_question
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
            $trials                = $this->getTrials($participant['id']);
            $maximisingAnswers     = $this->getMaximisingAnswers($participant['id']);
            $demographics          = $this->getDemographicData($participant['id']);
            $resiliance            = $this->getResilianceData($participant['id']);
            $meta                  = $this->getMetaData($participant['id']);
            $risk                  = $this->getRiskData($participant['id']);
            $nfc                   = $this->getNfcData($participant['id']);

            $data[] = array(
                'participant'  => $participant,
                'experiments'  => $experiments,
                'maximising'   => $maximisingAnswers,
                'demographic'  => $demographics,
                'resiliance'   => $resiliance,
                'risk'         => $risk,
                'nfc'          => $nfc,
                'meta'         => $meta,
                'trials'       => $trials,
            );
        }

        return $data;
    }
}