<?php

/**
 * Class to handle all db operations
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


    /**
     * @param $ipAddress
     * @param $participatedAt
     * @param $id
     * @param $dropout
     * @param $location
     * @param $group
     * @return string
     */
    public function saveParticipant($ipAddress, $participatedAt, $id, $dropout, $location, $group, $condition, $participatedPreviously, $reward)
    {
        $insertStatement = $this->dbh->prepare('INSERT INTO tl_participant (ip_address, participated_at, participation_id, dropout, location, participation_group, participation_condition, previous_participant, reward)
                                                VALUES (:ip_address, :participated_at, :participation_id, :dropout, :location, :participation_group, :participation_condition, :previous_participant, :reward)');

        $insertStatement->bindParam(':ip_address',              $ipAddress);
        $insertStatement->bindParam(':participated_at',         $participatedAt);
        $insertStatement->bindParam(':participation_id',        $id);
        $insertStatement->bindParam(':dropout',                 $dropout);
        $insertStatement->bindParam(':location',                $location);
        $insertStatement->bindParam(':participation_group',     $group);
        $insertStatement->bindParam(':participation_condition', $condition);
        $insertStatement->bindParam(':previous_participant',    $participatedPreviously);
        $insertStatement->bindParam(':reward',                  $reward);

        $insertStatement->execute();

        return $this->dbh->lastInsertId();
    }
    
    /**
     *  Checks all participants and returns the next condition in order to counter balance the participants
     */
    public function getCounterBalanceCondition()
    {
        $selectStatement = $this->dbh->prepare('SELECT COUNT(CASE participation_condition WHEN "C1" then 1 ELSE null END) AS condition1,
                                                	   COUNT(CASE participation_condition WHEN "C2" then 1 ELSE null END) AS condition2
                                                FROM `tl_participant`');
        $selectStatement->execute();
        $conditions = $selectStatement->fetch(PDO::FETCH_ASSOC);
        
        return $conditions['condition1'] >= $conditions['condition2'] ? 'C2' : 'C1';
    }
    
    public function getCounterBalanceGroup()
    {
        $selectStatement = $this->dbh->prepare('SELECT COUNT(CASE participation_group WHEN "G1" then 1 ELSE null END) AS G1,
                                                	   COUNT(CASE participation_group WHEN "G2" then 1 ELSE null END) AS G2,
                                                	   COUNT(CASE participation_group WHEN "G3" then 1 ELSE null END) AS G3,
                                                	   COUNT(CASE participation_group WHEN "G4" then 1 ELSE null END) AS G4,
                                                	   COUNT(CASE participation_group WHEN "G5" then 1 ELSE null END) AS G5,
                                                	   COUNT(CASE participation_group WHEN "G6" then 1 ELSE null END) AS G6
                                                FROM `tl_participant`');
        $selectStatement->execute();
        $groups = $selectStatement->fetch(PDO::FETCH_ASSOC);
        
        
        return min(array_keys($groups, min($groups)));
    }
    
    
    
    public function updateParticipant($participantDbId, $totalTime, $payout)
    {
        // Update Dropout column in participant table
        $updateStatement = $this->dbh->prepare('UPDATE tl_participant SET dropout = :dropout, total_time = :totalTime, payout = :payout WHERE id = :participantId');
        
        $dropout = false;
        
        $updateStatement->bindParam(':dropout',       $dropout);
        $updateStatement->bindParam(':totalTime',     $totalTime);
        $updateStatement->bindParam(':payout',        $payout);
        $updateStatement->bindParam(':participantId', $participantDbId);
        $updateStatement->execute();
    }


    public function saveTraining($participantDbId, $trainingId, $optionRank, $timeToDecision)
    {
        $insertStatement = $this->dbh->prepare('INSERT INTO tl_experiment_training (training_number, chosen_option_rank, time_to_decision, tl_participant_id)
                                                VALUES (:training_number, :chosen_option_rank, :time_to_decision, :participant_id)');

        $insertStatement->bindParam(':training_number',    $trainingId);
        $insertStatement->bindParam(':chosen_option_rank', $optionRank);
        $insertStatement->bindParam(':time_to_decision',   $timeToDecision);
        $insertStatement->bindParam(':participant_id',     $participantDbId);

        $insertStatement->execute();
    }

    public function saveExperiment($participantDbId, $task, $taskPosition, $trials, $timeToFinish)
    {
        $insertStatement = $this->dbh->prepare('INSERT INTO tl_experiment (task, task_pos, time_to_finish, tl_participant_id)
                                                VALUES (:task, :task_pos, :time_to_finish, :participant_id)');

        $insertStatement->bindParam(':task',                   $task);
        $insertStatement->bindParam(':task_pos',               $taskPosition);
        $insertStatement->bindParam(':time_to_finish',         $timeToFinish);
        $insertStatement->bindParam(':participant_id',         $participantDbId);

        $insertStatement->execute();

        $experimentId = $this->dbh->lastInsertId();
        
        
        // Save the trails
        $insertTrialStatement = $this->dbh->prepare('INSERT INTO tl_trial (number, pair_comparison, number_of_acquisitions, chosen_option, order_of_acqusitions, time_to_finish, acquisition_time, acquired_weights, local_accuracy, acquisition_pattern, score, tl_experiment_id)
                                                     VALUES (:number, :pair_comparison, :number_of_acquisitions, :chosen_option, :order_of_acquisitions, :time_to_finish, :acquisition_time, :acquired_weights, :local_accuracy, :acquisition_pattern, :score, :tl_experiment_id)');
        
        
        foreach ($trials as $trial)
        {
            $insertTrialStatement->bindParam(':number',                 $trial['number']);
            $insertTrialStatement->bindParam(':pair_comparison',        $trial['pairComparison']);
            $insertTrialStatement->bindParam(':number_of_acquisitions', $trial['numberOfAcquisitions']);
            $insertTrialStatement->bindParam(':chosen_option',          $trial['chosenOption']);
            $insertTrialStatement->bindParam(':order_of_acquisitions',  $trial['acquisitionOrder']);
            $insertTrialStatement->bindParam(':time_to_finish',         $trial['timeToFinish']);
            $insertTrialStatement->bindParam(':acquisition_time',       $trial['acquisitionTime']);
            $insertTrialStatement->bindParam(':acquired_weights',       $trial['acquiredWeights']);
            $insertTrialStatement->bindParam(':local_accuracy',         $trial['localAccuracy']);
            $insertTrialStatement->bindParam(':acquisition_pattern',    $trial['acquisitionPattern']);
            $insertTrialStatement->bindParam(':score',                  $trial['score']);
            $insertTrialStatement->bindParam(':tl_experiment_id',       $experimentId);
            
            $insertTrialStatement->execute();
        }
        
        return $experimentId;
    }

    public function saveStressQuestionAnswers($participantDbId, $experimentId, $stressAnswers, $stressAnswer8, $me4Answer, $timeToAnswer)
    {
        // Insert stress question answers
        $insertStatement = $this->dbh->prepare('INSERT INTO tl_stress_question (q_num_1, q_num_2, q_num_3, q_num_8, q_me4, time_to_answer, tl_participant_id, tl_experiment_id)
                                                VALUES (:answer1, :answer2, :answer3, :answer8, :answerMe4, :timeToAnswer, :participantId, :experimentId)');

        $insertStatement->bindParam(':answer1', $stressAnswers[0]);
        $insertStatement->bindParam(':answer2', $stressAnswers[1]);
        $insertStatement->bindParam(':answer3', $stressAnswers[2]);
        $insertStatement->bindParam(':answer8', $stressAnswer8);
        $insertStatement->bindParam(':answerMe4',    $me4Answer);
        $insertStatement->bindParam(':timeToAnswer',    $timeToAnswer);
        $insertStatement->bindParam(':participantId', $participantDbId);
        $insertStatement->bindParam(':experimentId',  $experimentId);

        $insertStatement->execute();
    }

    public function saveDemographics($participantDbId, $age, $gender, $graduation, $status, $apprenticeship, $academicDegree, $psychoStudies, $device)
    {
        $insertStatement = $this->dbh->prepare('INSERT INTO tl_demographics (age, gender, graduation, live_status, apprenticeship, academic_degree, device, psycho_studies, tl_participant_id)
                                                VALUES (:age, :gender, :graduation, :liveStatus, :apprenticeship, :academic_degree, :psychoStudies, :device, :participantId)');

        $insertStatement->bindParam(':age',             $age);
        $insertStatement->bindParam(':gender',          $gender);
        $insertStatement->bindParam(':graduation',      $graduation);
        $insertStatement->bindParam(':liveStatus',      $status);
        $insertStatement->bindParam(':apprenticeship',  $apprenticeship);
        $insertStatement->bindParam(':academic_degree', $academicDegree);
        $insertStatement->bindParam(':psychoStudies',   $psychoStudies);
        $insertStatement->bindParam(':device',          $device);
        $insertStatement->bindParam(':participantId',   $participantDbId);

        $insertStatement->execute();
    }

    public function saveMaximisingAnswers($participantDbId, $answerValues, $sumAnswers)
    {
        $insertStatement = $this->dbh->prepare('INSERT INTO tl_resilience_question (q_num_1, q_num_2, q_num_3, q_num_4, q_num_5, q_num_6, q_num_7, q_num_8, q_num_9, q_num_10, q_num_11, q_sum, tl_participant_id)
                                                VALUES (:answer1, :answer2, :answer3, :answer4, :answer5, :answer6, :answer7, :answer8, :answer9, :answer10, :answer11, :sumAnswers, :participantId)');

        $insertStatement->bindParam(':answer1', $answerValues[0]);
        $insertStatement->bindParam(':answer2', $answerValues[1]);
        $insertStatement->bindParam(':answer3', $answerValues[2]);
        $insertStatement->bindParam(':answer4', $answerValues[3]);
        $insertStatement->bindParam(':answer5', $answerValues[4]);
        $insertStatement->bindParam(':answer6', $answerValues[5]);
        $insertStatement->bindParam(':answer7', $answerValues[6]);
        $insertStatement->bindParam(':answer8', $answerValues[7]);
        $insertStatement->bindParam(':answer9', $answerValues[8]);
        $insertStatement->bindParam(':answer10', $answerValues[9]);
        $insertStatement->bindParam(':answer11', $answerValues[10]);
        $insertStatement->bindParam(':sumAnswers', $sumAnswers);
        $insertStatement->bindParam(':participantId', $participantDbId);

        $insertStatement->execute();
    }
    
    public function saveResilienceAnswers($participantDbId, $answerValues, $sumAnswers)
    {
        $insertStatement = $this->dbh->prepare('INSERT INTO tl_maximizing_question (q_num_1, q_num_2, q_num_3, q_num_4, q_num_5, q_num_6, q_sum, tl_participant_id)
                                                VALUES (:answer1, :answer2, :answer3, :answer4, :answer5, :answer6, :sumAnswers, :participantId)');

        $insertStatement->bindParam(':answer1', $answerValues[0]);
        $insertStatement->bindParam(':answer2', $answerValues[1]);
        $insertStatement->bindParam(':answer3', $answerValues[2]);
        $insertStatement->bindParam(':answer4', $answerValues[3]);
        $insertStatement->bindParam(':answer5', $answerValues[4]);
        $insertStatement->bindParam(':answer6', $answerValues[5]);
        $insertStatement->bindParam(':sumAnswers', $sumAnswers);
        $insertStatement->bindParam(':participantId', $participantDbId);

        $insertStatement->execute();
    }
    
    public function saveMetaAnswers($participantDbId, $answerValues)
    {
        $insertStatement = $this->dbh->prepare('INSERT INTO tl_meta_question (q_num_1, q_num_2, q_num_3, q_num_4, q_num_5, tl_participant_id)
                                                VALUES (:answer1, :answer2, :answer3, :answer4, :answer5, :participantId)');

        $insertStatement->bindParam(':answer1', $answerValues[0]);
        $insertStatement->bindParam(':answer2', $answerValues[1]);
        $insertStatement->bindParam(':answer3', $answerValues[2]);
        $insertStatement->bindParam(':answer4', $answerValues[3]);
        $insertStatement->bindParam(':answer5', $answerValues[4]);
        $insertStatement->bindParam(':participantId', $participantDbId);

        $insertStatement->execute();
    }
    
    public function saveNfcAnswers($participantDbId, $answerValues, $sumAnswers)
    {
        $insertStatement = $this->dbh->prepare('INSERT INTO tl_nfc_question (q_num_1, q_num_2, q_num_3, q_num_4, q_sum, tl_participant_id)
                                                VALUES (:answer1, :answer2, :answer3, :answer4, :sumAnswers, :participantId)');

        $insertStatement->bindParam(':answer1', $answerValues[0]);
        $insertStatement->bindParam(':answer2', $answerValues[1]);
        $insertStatement->bindParam(':answer3', $answerValues[2]);
        $insertStatement->bindParam(':answer4', $answerValues[3]);
        $insertStatement->bindParam(':sumAnswers', $sumAnswers);
        $insertStatement->bindParam(':participantId', $participantDbId);

        $insertStatement->execute();
    }
    
        public function saveRiskAnswers($participantDbId, $answerValues)
    {
        $insertStatement = $this->dbh->prepare('INSERT INTO tl_risk_question (q_num_1, q_num_2, q_num_3, q_num_4, tl_participant_id)
                                                VALUES (:answer1, :answer2, :answer3, :answer4, :participantId)');

        $insertStatement->bindParam(':answer1', $answerValues[0]);
        $insertStatement->bindParam(':answer2', $answerValues[1]);
        $insertStatement->bindParam(':answer3', $answerValues[2]);
        $insertStatement->bindParam(':answer4', $answerValues[3]);
        $insertStatement->bindParam(':participantId', $participantDbId);

        $insertStatement->execute();
    }

    public function saveUser($email, $participateInOther, $comments, $location)
    {
        $insertStatement = $this->dbh->prepare('INSERT INTO tl_user (email, participate_in_other, location, comments)
                                                VALUES (:email, :participateInOther, :location, :comments)');

        $insertStatement->bindParam(':email',              $email);
        $insertStatement->bindParam(':participateInOther', $participateInOther);
        $insertStatement->bindParam(':location',           $location);
        $insertStatement->bindParam(':comments',           $comments);

        $insertStatement->execute();
    }
}