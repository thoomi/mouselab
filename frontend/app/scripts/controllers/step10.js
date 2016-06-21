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
      $scope.availableTime = configData.getAvailableTime(dataService.getCurrentTask());
      $scope.timerRunning  = true;
      $scope.currentTrial  = dataService.getNextTrial();
      
      $scope.experimentCondition = dataService.getParticipantCondition();
      
      $scope.maxTrials           = 64;
      $scope.finishedTrials      = 0;
      $scope.currentScore        = 0;
      $scope.buyTimerRunning     = false;
      $scope.informationAcquired = false;
      $scope.aquiredInfos        = [];
      $scope.finishedTrialData   = [];
      
      $scope.timeOfLastAcquiredTrial = configData.getAvailableTime(dataService.getCurrentTask());
      
      $scope.showCueValues  = [
        {
          id: 'A',
          show: false,
          intervalId: -1,
          countdownTime: $scope.cueValues[0].cost
        },
        {
          id: 'B',
          show: false,
          intervalId: -1,
          countdownTime: $scope.cueValues[1].cost
        },
        {
          id: 'C',
          show: false,
          intervalId: -1,
          countdownTime: $scope.cueValues[2].cost
        },
        {
          id: 'D',
          show: false,
          intervalId: -1,
          countdownTime: $scope.cueValues[3].cost
        }];
      
      
      
      
      function saveExperiment() {
          var timeToFinish = configData.getAvailableTime(dataService.getCurrentTask());
          
          if ($scope.availableTime > 0)
          {
            timeToFinish = configData.getAvailableTime(dataService.getCurrentTask()) - $scope.availableTime;
          }
          
          dataService.addScore($scope.currentScore);
          
          dataService.saveExperiment($scope.finishedTrialData, timeToFinish, function(error){
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
        
        angular.forEach($scope.showCueValues, function(value, key) {
          value.show = false;
          value.intervalId = -1;
          value.countdownTime = $scope.cueValues[key].cost;
        });
        
        console.log($scope.finishedTrialData);
      }


      var intervalId = $interval(function() {
        if ($scope.availableTime <= 0)
        {
          $interval.cancel(intervalId);
          $scope.timerRunning = false;
          saveExperiment();
          return;
        }
    
        $scope.availableTime -= 10;
        
        $scope.remainingMinutes = Math.floor(($scope.availableTime / (1000.0 * 60.0)) % 60);
        $scope.remainingSeconds = ($scope.availableTime / 1000.0) % 60;
    
      }, 10);
  
  
    $scope.$on('$destroy', function() {
      // Make sure that the interval is destroyed too
      $interval.cancel(intervalId);
    });
  
  
  
    $scope.$on('timer-stopped', function (event, data) {
      if (!event.targetScope.countdown)
      {
        saveExperiment(data.millis);
      }
    });
    
    
    
    $scope.buyCue = function(index) {
      
      if ($scope.showCueValues[index].intervalId !== -1 || $scope.buyTimerRunning) { return;  }
      
      $scope.informationAcquired = false;
      
      $scope.buyTimerRunning = true;
      
      $scope.showCueValues[index].intervalId = $interval(function() {
         
         if ($scope.showCueValues[index].countdownTime <= 0)
         {
           $interval.cancel($scope.showCueValues[index].intervalId);
           $scope.showCueValues[index].show = true;
           $scope.buyTimerRunning = false;
           $scope.informationAcquired = true;
           $scope.aquiredInfos.push($scope.showCueValues[index].id);
           
           return;
         }
         
         $scope.showCueValues[index].countdownTime -= 10; 
      }, 10);
    };
    
    
    $scope.chooseShare = function(share) {
      if (!$scope.informationAcquired) { return;  }
      
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
      
      var trialScore =  Math.round(100 * acquiredWeights * localAccuracy);
      $scope.currentScore += trialScore;
      
    
      var numberOfAcquisitions = 4;
      for (var indexOfAcquisition = $scope.aquiredInfos.length; indexOfAcquisition < 4; indexOfAcquisition++)
      {
        $scope.aquiredInfos.push(0);
        numberOfAcquisitions -= 1;
      }
      
      
      $scope.finishedTrialData.push({
        number:               $scope.finishedTrials + 1,
        pairComparison:       $scope.currentTrial.pairId,
        numberOfAcquisitions: numberOfAcquisitions,
        acquiredWeights:      acquiredWeights,
        localAccuracy:        localAccuracy,
        score:                trialScore,
        acquisitionPattern:   determineAcquisitionPattern(numberOfAcquisitions),
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
    
    function determineAcquisitionPattern(acquisitionCount) {
      var cue1 = $scope.showCueValues[0].show;
      var cue2 = $scope.showCueValues[1].show;
      var cue3 = $scope.showCueValues[2].show;
      var cue4 = $scope.showCueValues[3].show;
      
      if (acquisitionCount === 1)
      {
        if (cue1 && !cue2 && !cue3 && !cue4) { return 12; }
        if (!cue1 && cue2 && !cue3 && !cue4) { return 13; }
        if (!cue1 && !cue2 && cue3 && !cue4) { return 14; }
        if (!cue1 && !cue2 && !cue3 && cue4) { return 15; }
      }
      else if (acquisitionCount === 2)
      {
        if (cue1 && cue2 && !cue3 && !cue4) { return 6; }
        if (cue1 && !cue2 && cue3 && !cue4) { return 7; }
        if (cue1 && !cue2 && !cue3 && cue4) { return 8; }
        if (!cue1 && cue2 && cue3 && !cue4) { return 9; }
        if (!cue1 && cue2 && !cue3 && cue4) { return 10; }
        if (!cue1 && !cue2 && !cue3 && cue4) { return 11; }
      }
      else if (acquisitionCount === 3)
      {
        if (cue1 && cue2 && cue3 && !cue4) { return 2; }
        if (cue1 && cue2 && !cue3 && cue4) { return 3; }
        if (cue1 && !cue2 && cue3 && cue4) { return 4; }
        if (!cue1 && cue2 && cue3 && cue4) { return 5; }
      }
      else if (acquisitionCount === 4)
      {
        return 1;
      }
    }
  });
