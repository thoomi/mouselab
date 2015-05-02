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
    public function saveParticipant($ipAddress, $participatedAt, $id, $dropout, $location, $group, $strategy, $organization, $participatedPreviously)
    {
        $insertStatement = $this->dbh->prepare('INSERT INTO tl_participant (ip_address, participated_at, participation_id, dropout, location, participation_group, Participation_condition, donation_organization, previous_participant)
                                                VALUES (:ip_address, :participated_at, :participation_id, :dropout, :location, :participation_group, :participation_condition, :donation_organization, :previous_participant)');

        $insertStatement->bindParam(':ip_address',              $ipAddress);
        $insertStatement->bindParam(':participated_at',         $participatedAt);
        $insertStatement->bindParam(':participation_id',        $id);
        $insertStatement->bindParam(':dropout',                 $dropout);
        $insertStatement->bindParam(':location',                $location);
        $insertStatement->bindParam(':participation_group',     $group);
        $insertStatement->bindParam(':participation_condition', $strategy);
        $insertStatement->bindParam(':donation_organization',   $organization);
        $insertStatement->bindParam(':previous_participant',    $participatedPreviously);

        $insertStatement->execute();

        return $this->dbh->lastInsertId();
    }

    public function saveExperiment($participantDbId, $condition, $conditionPosition, $optionRank, $timeToDecision)
    {
        $insertStatement = $this->dbh->prepare('INSERT INTO tl_experiment (task, task_pos, chosen_option_rank, time_to_decision, tl_participant_id)
                                                VALUES (:task, :task_pos, :chosen_option_rank, :time_to_decision, :participant_id)');

        $insertStatement->bindParam(':task',               $condition);
        $insertStatement->bindParam(':task_pos',           $conditionPosition);
        $insertStatement->bindParam(':chosen_option_rank', $optionRank);
        $insertStatement->bindParam(':time_to_decision',   $timeToDecision);
        $insertStatement->bindParam(':participant_id',     $participantDbId);

        $insertStatement->execute();

        return $this->dbh->lastInsertId();
    }

    public function saveStressQuestionAnswers($participantDbId, $experimentId, $valueQuestion1, $valueQuestion2, $questionSum)
    {
        $insertStatement = $this->dbh->prepare('INSERT INTO tl_stress_question (q_num_1, q_num_2, q_sum, tl_participant_id, tl_experiment_id)
                                                VALUES (:answer1, :answer2, :sumAnswers, :participantId, :experimentId)');

        $insertStatement->bindParam(':answer1',       $valueQuestion1);
        $insertStatement->bindParam(':answer2',       $valueQuestion2);
        $insertStatement->bindParam(':sumAnswers',    $questionSum);
        $insertStatement->bindParam(':participantId', $participantDbId);
        $insertStatement->bindParam(':experimentId',  $experimentId);

        $insertStatement->execute();
    }

    public function saveDemographics($participantDbId, $age, $gender, $graduation)
    {
        $insertStatement = $this->dbh->prepare('UPDATE tl_participant SET age = :age, gender = :gender, graduation = :graduation WHERE id = :participantId');

        $insertStatement->bindParam(':age',           $age);
        $insertStatement->bindParam(':gender',        $gender);
        $insertStatement->bindParam(':graduation',    $graduation);
        $insertStatement->bindParam(':participantId', $participantDbId);

        $insertStatement->execute();
    }

    public function saveMaximisingAnswers($participantDbId, $answerValues, $sumAnswers, $totalTime)
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