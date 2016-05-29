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
    public function saveParticipant($ipAddress, $participatedAt, $id, $dropout, $location, $group, $condition, $participatedPreviously)
    {
        $insertStatement = $this->dbh->prepare('INSERT INTO tl_participant (ip_address, participated_at, participation_id, dropout, location, participation_group, Participation_condition, previous_participant)
                                                VALUES (:ip_address, :participated_at, :participation_id, :dropout, :location, :participation_group, :participation_condition, :previous_participant)');

        $insertStatement->bindParam(':ip_address',              $ipAddress);
        $insertStatement->bindParam(':participated_at',         $participatedAt);
        $insertStatement->bindParam(':participation_id',        $id);
        $insertStatement->bindParam(':dropout',                 $dropout);
        $insertStatement->bindParam(':location',                $location);
        $insertStatement->bindParam(':participation_group',     $group);
        $insertStatement->bindParam(':participation_condition', $condition);
        $insertStatement->bindParam(':previous_participant',    $participatedPreviously);

        $insertStatement->execute();

        return $this->dbh->lastInsertId();
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


    public function saveExperiment($participantDbId, $condition, $conditionPosition, $optionRank, $timeToDecision, $optionPosition)
    {
        $insertStatement = $this->dbh->prepare('INSERT INTO tl_experiment (task, task_pos, chosen_option_rank, time_to_decision, chosen_option_position, tl_participant_id)
                                                VALUES (:task, :task_pos, :chosen_option_rank, :time_to_decision, :chosen_option_position, :participant_id)');

        $insertStatement->bindParam(':task',                   $condition);
        $insertStatement->bindParam(':task_pos',               $conditionPosition);
        $insertStatement->bindParam(':chosen_option_rank',     $optionRank);
        $insertStatement->bindParam(':time_to_decision',       $timeToDecision);
        $insertStatement->bindParam(':participant_id',         $participantDbId);
        $insertStatement->bindParam(':chosen_option_position', $optionPosition);

        $insertStatement->execute();

        return $this->dbh->lastInsertId();
    }

    public function saveStressQuestionAnswers($participantDbId, $experimentId, $satisfactionAnswers, $satisfactionAnswersSum, $stressAnswers, $stressAnswersSum, $decisionByStrategy)
    {
        // Insert stress question answers
        $insertStatement = $this->dbh->prepare('INSERT INTO tl_stress_question (q_num_1, q_num_2, q_num_3, q_num_4, q_num_5, q_num_6, q_num_7, q_num_8, q_sum, tl_participant_id, tl_experiment_id)
                                                VALUES (:answer1, :answer2, :answer3, :answer4, :answer5, :answer6, :answer7, :answer8, :sumAnswers, :participantId, :experimentId)');

        $insertStatement->bindParam(':answer1', $stressAnswers[0]);
        $insertStatement->bindParam(':answer2', $stressAnswers[1]);
        $insertStatement->bindParam(':answer3', $stressAnswers[2]);
        $insertStatement->bindParam(':answer4', $stressAnswers[3]);
        $insertStatement->bindParam(':answer5', $stressAnswers[4]);
        $insertStatement->bindParam(':answer6', $stressAnswers[5]);
        $insertStatement->bindParam(':answer7', $stressAnswers[6]);
        $insertStatement->bindParam(':answer8', $stressAnswers[7]);
        $insertStatement->bindParam(':sumAnswers',    $stressAnswersSum);
        $insertStatement->bindParam(':participantId', $participantDbId);
        $insertStatement->bindParam(':experimentId',  $experimentId);

        $insertStatement->execute();


        // Insert satisfaction question answers
        $insertStatement = $this->dbh->prepare('INSERT INTO tl_satisfaction_question (q_num_1, q_num_2, q_sum, tl_participant_id, tl_experiment_id)
                                                VALUES (:answer1, :answer2, :sumAnswers, :participantId, :experimentId)');

        $insertStatement->bindParam(':answer1', $satisfactionAnswers[0]);
        $insertStatement->bindParam(':answer2', $satisfactionAnswers[1]);
        $insertStatement->bindParam(':sumAnswers',    $satisfactionAnswersSum);
        $insertStatement->bindParam(':participantId', $participantDbId);
        $insertStatement->bindParam(':experimentId',  $experimentId);

        $insertStatement->execute();


        // Insert decision by strategy value
        $updateStatement = $this->dbh->prepare('UPDATE tl_experiment SET q_decision_by_guideline = :decisionByStrategy WHERE id = :experimentId');

        $updateStatement->bindParam(':decisionByStrategy', $decisionByStrategy);
        $updateStatement->bindParam(':experimentId',  $experimentId);

        $updateStatement->execute();
    }

    public function saveDemographics($participantDbId, $age, $gender, $graduation, $status, $apprenticeship, $academicDegree, $device)
    {
        $insertStatement = $this->dbh->prepare('INSERT INTO tl_demographics (age, gender, graduation, live_status, apprenticeship, academic_degree, device, tl_participant_id)
                                                VALUES (:age, :gender, :graduation, :liveStatus, :apprenticeship, :academic_degree, :device, :participantId)');

        $insertStatement->bindParam(':age',             $age);
        $insertStatement->bindParam(':gender',          $gender);
        $insertStatement->bindParam(':graduation',      $graduation);
        $insertStatement->bindParam(':liveStatus',      $status);
        $insertStatement->bindParam(':apprenticeship',  $apprenticeship);
        $insertStatement->bindParam(':academic_degree', $academicDegree);
        $insertStatement->bindParam(':device',          $device);
        $insertStatement->bindParam(':participantId',   $participantDbId);

        $insertStatement->execute();
    }

    public function saveMaximisingAnswers($participantDbId, $answerValues, $sumAnswers)
    {
        $insertStatement = $this->dbh->prepare('INSERT INTO tl_maximising_question (q_num_1, q_num_2, q_num_3, q_num_4, q_num_5, q_num_6, q_num_7, q_num_8, q_num_9, q_num_10, q_num_11, q_num_12, q_num_13, q_sum, tl_participant_id)
                                                VALUES (:answer1, :answer2, :answer3, :answer4, :answer5, :answer6, :answer7, :answer8, :answer9, :answer10, :answer11, :answer12, :answer13, :sumAnswers, :participantId)');

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
        $insertStatement->bindParam(':answer12', $answerValues[11]);
        $insertStatement->bindParam(':answer13', $answerValues[12]);
        $insertStatement->bindParam(':sumAnswers', $sumAnswers);
        $insertStatement->bindParam(':participantId', $participantDbId);

        $insertStatement->execute();
    }

    public function saveParticipationAnswers($participantDbId, $answerValuesEnvironment, $answerValuesParticipation, $totalTime)
    {
        $insertStatement = $this->dbh->prepare('INSERT INTO tl_additional_questions (disturbed, seriousness, interest, additional_tools, mental_arithmetic, memory_retention, tl_participant_id)
                                                VALUES (:answer1, :answer2, :answer3, :answer4, :answer5, :answer6, :participantId)');

        $insertStatement->bindParam(':answer1', $answerValuesEnvironment[0]);
        $insertStatement->bindParam(':answer2', $answerValuesEnvironment[1]);
        $insertStatement->bindParam(':answer3', $answerValuesEnvironment[2]);
        $insertStatement->bindParam(':answer4', $answerValuesParticipation[0]);
        $insertStatement->bindParam(':answer5', $answerValuesParticipation[1]);
        $insertStatement->bindParam(':answer6', $answerValuesParticipation[2]);
        $insertStatement->bindParam(':participantId', $participantDbId);

        $insertStatement->execute();


        // Update Dropout column in participant table
        $updateStatement = $this->dbh->prepare('UPDATE tl_participant SET dropout = :dropout, total_time = :totalTime WHERE id = :participantId');

        $dropout = false;

        $updateStatement->bindParam(':dropout',       $dropout);
        $updateStatement->bindParam(':totalTime',     $totalTime);
        $updateStatement->bindParam(':participantId', $participantDbId);

        $updateStatement->execute();
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