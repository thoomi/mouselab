'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:Step10Ctrl
 * @description
 * # Step10Ctrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('Step10Ctrl', function ($scope, $location,$interval, dataService, configData) {
      if (!dataService.everythingIsValid()) { $location.path(''); }
      
      dataService.incrementSiteNumber();
      dataService.initializeTrials();
      
      $scope.cueLabels     = configData.getCueLabels();
      $scope.cueValues     = configData.getCueValues();
      $scope.currentRound  = dataService.getCurrentRound();
      $scope.initialTime   = configData.getAvailableTime(dataService.getCurrentTask());
      $scope.availableTime = configData.getAvailableTime(dataService.getCurrentTask());
      $scope.timerRunning  = true;
      $scope.currentTrial  = dataService.getNextTrial();
      $scope.maxScore      = configData.getMaxScore(dataService.getCurrentTask());
      $scope.taskWaiting   = configData.isTaskWithoutWaiting(dataService.getCurrentTask());
      
      $scope.experimentCondition = dataService.getParticipantCondition();
      
      $scope.maxTrials           = configData.getMaxPossibleTrials(dataService.getCurrentTask());
      $scope.finishedTrials      = 0;
      $scope.currentScore        = 0;
      $scope.buyTimerRunning     = false;
      $scope.informationAcquired = false;
      $scope.aquiredInfos        = [];
      $scope.finishedTrialData   = [];
      $scope.savingInProgress    = false;
      $scope.sumOfTimeCosts      = 0;
      
      $scope.timeOfLastAcquiredTrial = configData.getAvailableTime(dataService.getCurrentTask());
      
      
      var countDownStepValue = 10;
      $scope.currentCueTime  = 0;
      $scope.currentCueEndTime = 0;
      
      
      $scope.showCueValues  = [
        {
          id: '1',
          show: false,
          isAcquired: false,
          intervalId: -1,
          countdownTime: $scope.cueValues[0].cost
        },
        {
          id: '2',
          show: false,
          isAcquired: false,
          intervalId: -1,
          countdownTime: $scope.cueValues[1].cost
        },
        {
          id: '3',
          show: false,
          isAcquired: false,
          intervalId: -1,
          countdownTime: $scope.cueValues[2].cost
        },
        {
          id: '4',
          show: false,
          isAcquired: false,
          intervalId: -1,
          countdownTime: $scope.cueValues[3].cost
        }];
      
      
      
      
      function saveExperiment() {
          if ($scope.savingInProgress) { return; }
          
          var timeToFinish = configData.getAvailableTime(dataService.getCurrentTask());
          
          if ($scope.availableTime > 0)
          {
            timeToFinish = configData.getAvailableTime(dataService.getCurrentTask()) - $scope.availableTime;
          }
          
          // Cancel all running intervals
          $interval.cancel(intervalId);
          
          angular.forEach($scope.showCueValues, function(value, key) {
            $interval.cancel(value.intervalId);
          });
        
          
          $scope.availableTime    = 0;
          $scope.remainingSeconds = 0;
          $scope.remainingMinutes = 0;
          
          $scope.savingInProgress = true;
          $scope.buyTimerRunning  = true;
          $scope.informationAcquired = false;
          
          dataService.saveExperiment($scope.finishedTrialData, timeToFinish, $scope.currentScore,  function(error){
            if (!error)
            {
              $location.path('step11');
            }
            else
            {
              console.log(error);
              // TODO: Handle error properly
            }
          });
        }
        
        
      function setupNextTrial() {
        $scope.timeOfLastAcquiredTrial = $scope.availableTime;
        $scope.finishedTrials++;
        $scope.buyTimerRunning     = false;
        $scope.informationAcquired = false;
        $scope.aquiredInfos        = [];
        $scope.currentTrial        = dataService.getNextTrial();
        $scope.sumOfTimeCosts      = 0;
        
        angular.forEach($scope.showCueValues, function(value, key) {
          value.show = false;
          value.isAcquired = false;
          $interval.cancel(value.intervalId);
          value.intervalId = -1;
          value.countdownTime = $scope.cueValues[key].cost;
        });
        
        // All trials done
        if ($scope.finishedTrials === $scope.maxTrials)
        {
          $scope.informationAcquired = false;
          saveExperiment();
        }
      }


      var intervalId = $interval(function() {
        if ($scope.availableTime <= 0)
        {
          $interval.cancel(intervalId);
          $scope.timerRunning = false;
          $scope.informationAcquired = false;
          saveExperiment();
          return;
        }
    
        $scope.availableTime -= countDownStepValue;
        
        if ($scope.currentCueTime > 0)
        {
          $scope.currentCueTime -= countDownStepValue;
        }
        
        $scope.remainingMinutes = Math.floor(($scope.availableTime / (1000.0 * 60.0)) % 60);
        $scope.remainingSeconds = ($scope.availableTime / 1000.0) % 60;
    
      }, 10);
  
  
    $scope.$on('$destroy', function() {
      // Make sure that the interval is destroyed too
      $interval.cancel(intervalId);
      
      angular.forEach($scope.showCueValues, function(value, key) {
        $interval.cancel(value.intervalId);
      });
    });
  
    
    $scope.buyCue = function(index) {
      
      if ($scope.showCueValues[index].intervalId !== -1 || $scope.buyTimerRunning) { return;  }
      
      $scope.informationAcquired = false;
      
      $scope.buyTimerRunning = true;
      
      if ($scope.taskWaiting)
      {
        $scope.showCueValues[index].isAcquired = true;
        
        var acquisitionPattern = determineAcquisitionPattern();
        var timeCost = determineTimeCost(acquisitionPattern, dataService.getCurrentTask());
        
        var localTimeCost = timeCost - $scope.sumOfTimeCosts;
        
        var countDownSteps = $scope.showCueValues[index].countdownTime / 10;
        countDownStepValue = ($scope.showCueValues[index].countdownTime + localTimeCost) / countDownSteps;
        
        //$scope.showCueValues[index].countdownTime += localTimeCost;
        
        $scope.sumOfTimeCosts = timeCost;
        $scope.currentCueTime  = $scope.showCueValues[index].countdownTime + localTimeCost;
        $scope.currentCueEndTime = $scope.availableTime - ($scope.showCueValues[index].countdownTime + localTimeCost);
        
        $scope.showCueValues[index].intervalId = $interval(function() {
       
           if ($scope.showCueValues[index].countdownTime <= 0)
           {
             countDownStepValue = 10;
             
             $interval.cancel($scope.showCueValues[index].intervalId);
             $scope.showCueValues[index].show = true;
             $scope.buyTimerRunning = false;
             $scope.informationAcquired = true;
             $scope.aquiredInfos.push($scope.showCueValues[index].id);
             
             $scope.currentCueTime = 0;
             $scope.currentCueEndTime = 0;
             
             return;
           }
           
           $scope.showCueValues[index].countdownTime -= 10;
        }, 10);
      }
      else
      {
          $scope.showCueValues[index].intervalId = 0;
          $scope.showCueValues[index].show = true;
          $scope.showCueValues[index].isAcquired = true;
          $scope.buyTimerRunning = false;
          $scope.informationAcquired = true;
          $scope.aquiredInfos.push($scope.showCueValues[index].id);
          
          var acquisitionPattern = determineAcquisitionPattern();
          var timeCost = determineTimeCost(acquisitionPattern, dataService.getCurrentTask());
          
          var localTimeCost = timeCost - $scope.sumOfTimeCosts;
          
          $scope.availableTime -= ($scope.showCueValues[index].countdownTime + localTimeCost);
          
          $scope.sumOfTimeCosts = timeCost;
      }
    };
    
    
    $scope.chooseShare = function(share) {
      if (!$scope.informationAcquired) { return;  }
      $scope.informationAcquired = false;
      
      var acquiredWeights = 0;
      var localAccuracy   = 0;
      var acquisitionTime = 0;
      
      angular.forEach($scope.cueValues, function(value, key) {
        acquiredWeights += value.weight * $scope.showCueValues[key].show;
        localAccuracy += value.weight * $scope.showCueValues[key].show * getCueScore(key, share);
        
        // Calculate sum of acquired times
        
        acquisitionTime += value.cost * $scope.showCueValues[key].show;
      });
      
      
      localAccuracy /= getMaxLocalAccuracy();
      var localAccuracy2 = localAccuracy < 1 ? 0 : 1;
      
      var trialScore =  Math.round(100 * acquiredWeights * localAccuracy2);
      $scope.currentScore += trialScore;
      
    
      var numberOfAcquisitions = 4;
      for (var indexOfAcquisition = $scope.aquiredInfos.length; indexOfAcquisition < 4; indexOfAcquisition++)
      {
        $scope.aquiredInfos.push(0);
        numberOfAcquisitions -= 1;
      }
      
      var aqcuisitionPattern = determineAcquisitionPattern(numberOfAcquisitions);
      
      // Get the time cost and subtract it from current time
      var timeCost = determineTimeCost(aqcuisitionPattern, dataService.getCurrentTask());
      
      //if ($scope.taskWaiting)
      //{
        //$scope.availableTime -= timeCost;
      //}
      

      $scope.finishedTrialData.push({
        number:               $scope.finishedTrials + 1,
        pairComparison:       $scope.currentTrial.pairId,
        numberOfAcquisitions: numberOfAcquisitions,
        acquiredWeights:      acquiredWeights,
        localAccuracy:        localAccuracy,
        localAccuracy2:       localAccuracy2,
        score:                trialScore,
        acquisitionPattern:   aqcuisitionPattern,
        chosenOption:         share === 'A' ? $scope.currentTrial.optionId : (17 - $scope.currentTrial.pairId), // Get the chosen pair option. (The option given by the trail data is always displayed left)
        timeToFinish:         $scope.timeOfLastAcquiredTrial - $scope.availableTime,
        acquisitionTime:      acquisitionTime,
        acquisitionOrder:     $scope.aquiredInfos.join(':')
      });
      
      setupNextTrial();
    };
    
    
    function getCueScore(index, share) {
      if (share === 'A')
      {
        return $scope.currentTrial.pattern[index];
      }
      else
      {
        return 1 - $scope.currentTrial.pattern[index];
      }
    }
    
    function getMaxLocalAccuracy() {
      var accuracy1 = 0;
      var accuracy2 = 0;
      
      angular.forEach($scope.cueValues, function(value, key) {
        accuracy1 += value.weight * $scope.showCueValues[key].show * getCueScore(key, 'A');
        accuracy2 += value.weight * $scope.showCueValues[key].show * getCueScore(key, 'B');
      });
      
      return Math.max(accuracy1, accuracy2);
    }
    
    function determineAcquisitionPattern() {
      var cue1 = $scope.showCueValues[0].isAcquired;
      var cue2 = $scope.showCueValues[1].isAcquired;
      var cue3 = $scope.showCueValues[2].isAcquired;
      var cue4 = $scope.showCueValues[3].isAcquired;
      
      if (!cue1 && !cue2 && !cue3 &&  cue4) { return 15; }
      if (!cue1 && !cue2 &&  cue3 && !cue4) { return 14; }
      if (!cue1 &&  cue2 && !cue3 && !cue4) { return 13; }
      if ( cue1 && !cue2 && !cue3 && !cue4) { return 12; }
      
      if (!cue1 && !cue2 &&  cue3 &&  cue4) { return 11; }
      if (!cue1 &&  cue2 && !cue3 &&  cue4) { return 10; }
      if (!cue1 &&  cue2 &&  cue3 && !cue4) { return  9; }
      if ( cue1 && !cue2 && !cue3 &&  cue4) { return  8; }
      if ( cue1 && !cue2 &&  cue3 && !cue4) { return  7; }
      if ( cue1 &&  cue2 && !cue3 && !cue4) { return  6; }

      if (!cue1 &&  cue2 &&  cue3 &&  cue4) { return  5; }
      if ( cue1 && !cue2 &&  cue3 &&  cue4) { return  4; }
      if ( cue1 &&  cue2 && !cue3 &&  cue4) { return  3; }
      if ( cue1 &&  cue2 &&  cue3 && !cue4) { return  2; }
      
      if ( cue1 &&  cue2 &&  cue3 &&  cue4) { return  1; }
    }
    
    function determineTimeCost(acquisitionPattern) {
      var timeCosts = [4150, 3050, 2920, 2740, 2630, 1820, 1640, 1530, 1520, 1400, 1220, 420, 300, 120, 0];
      
      return timeCosts[acquisitionPattern - 1];
    }
  });
