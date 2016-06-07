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
      
      $scope.cueLabels = configData.getCueLabels();
      $scope.cueValues = configData.getCueValues();
      $scope.currentRound  = dataService.getCurrentRound();
      $scope.availableTime = configData.getAvailableTime(dataService.getCurrentTask());
      $scope.timerRunning  = true;
      $scope.currentTrial  = dataService.getNextTrial();
      $scope.currentPair   = configData.getPairComparison($scope.currentTrial.pairId);
      
      $scope.maxTrials      = 64;
      $scope.finishedTrials = 0;
      $scope.currentScore   = 0;
      $scope.buyTimerRunning = false;
      $scope.informationAcquired = false;
      $scope.showCueValues  = [
        {
          show: false,
          intervalId: -1,
          countdownTime: $scope.cueValues[0].cost
        },
        {
          show: false,
          intervalId: -1,
          countdownTime: $scope.cueValues[1].cost
        },
        {
          show: false,
          intervalId: -1,
          countdownTime: $scope.cueValues[2].cost
        },
        {
          show: false,
          intervalId: -1,
          countdownTime: $scope.cueValues[3].cost
        }];
      
      
      
      
      function saveExperiment() {
          dataService.saveExperiment(function(error){
            if (!error)
            {
              $location.path('taskquestions');
            }
            else
            {
              console.log(error);
              // TODO: Handle error properly
            }
          });
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
           return;
         }
         
         $scope.showCueValues[index].countdownTime -= 10; 
      }, 10);
    };
    
    
    $scope.chooseShare = function(share) {
      if (!$scope.informationAcquired) { return;  }
      
      // TODO: Calculate points and save trial
      // TODO: Reset Information
      
    };
  });
