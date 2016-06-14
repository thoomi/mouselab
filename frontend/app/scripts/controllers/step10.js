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
      var localAccuracy   = 1;
      var acquisitionTime = 0;
      
      angular.forEach($scope.cueValues, function(value, key) {
        acquiredWeights += value.weight * $scope.showCueValues[key].show;
        // TODO: Calculate correct local accuracy
        
        
        // Calculate sum of acquired times
        acquisitionTime += value.cost * $scope.showCueValues[key].show;
      });
      
      $scope.currentScore += 100 * acquiredWeights * localAccuracy;
    
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
        chosenOption:         share === 'A' ? $scope.currentTrial.optionId : (17 - $scope.currentTrial.optionId), // Get the chosen pair option. (The option given by the trail data is always displayed left)
        timeToFinish:         $scope.timeOfLastAcquiredTrial - $scope.availableTime,
        acquisitionTime:      acquisitionTime,
        acquisitionOrder:     $scope.aquiredInfos.join(':')
      });
      
      setupNextTrial();
    };
  });
