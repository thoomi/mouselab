'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:Step7Ctrl
 * @description
 * # Step6Ctrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('Step7Ctrl', function ($scope, $location, $interval, dataService, randomizer) {
    if (!dataService.everythingIsValid()) { $location.path(''); }
    dataService.incrementSiteNumber();
    
      dataService.setTrainingData({
        score: 0,
        numberOfTrials: 0
      });
    
    $scope.currentScore   = 0;
    $scope.currentTime    = 0;
    $scope.finishedTrials = 0;
    $scope.currentPattern = [1, 1];
    
    $scope.buyTimerRunning     = false;
    $scope.informationAcquired = false;
    
    $scope.cueOptions = [
      {
        label:    'Familiengeführt?',
        validity: 0.84,
        weight:   0.58,
        cost:     2320,
        isShown:  false,
        intervalId: -1,
        countdownTime: 2320
      },
      {
        label:    'Firmensitz in Steueroase?',
        validity: 0.61,
        weight:   0.42,
        cost:     1680,
        isShown:  false,
        intervalId: -1,
        countdownTime: 1680
      }
    ];
    
    $scope.pairComparisons = [
      {
        id: 1.1,
        pattern: [1, 1]
      },
      {
        id: 1.2,
        pattern: [0, 0]
      },
      {
        id: 2.1,
        pattern: [1, 0]
      },
      {
        id: 2.2,
        pattern: [0, 1]
      }
    ];
    
    
    $scope.buyCue = function(index) {
      
      if ($scope.cueOptions[index].intervalId !== -1 || $scope.buyTimerRunning) { return;  }
      
      $scope.buyTimerRunning = true;
      
      $scope.cueOptions[index].intervalId = $interval(function() {
         
         if ($scope.cueOptions[index].countdownTime <= 0)
         {
           $interval.cancel($scope.cueOptions[index].intervalId);
           $scope.cueOptions[index].isShown = true;
           $scope.buyTimerRunning           = false;
           $scope.informationAcquired       = true;

           return;
         }
         
         $scope.cueOptions[index].countdownTime -= 10; 
      }, 10);
    };
    
    
    $scope.chooseShare = function(share) {
      if (!$scope.informationAcquired) { return;  }
      
      var acquiredWeights = 0;
      var localAccuracy   = 0;
      
      angular.forEach($scope.cueOptions, function(value, key) {
        acquiredWeights += value.weight * value.isShown;
        localAccuracy += value.weight * value.isShown * getCueScore(key, share);
      });
      
      localAccuracy /= getMaxLocalAccuracy();
      
      $scope.currentScore += Math.round(100 * acquiredWeights * localAccuracy);
      $scope.finishedTrials++;
      
      dataService.setTrainingData({
        score: $scope.currentScore,
        numberOfTrials: $scope.finishedTrials
      });
      
      // Prepare next trial
      $scope.buyTimerRunning             = false;
      $scope.informationAcquired         = false;
      $scope.cueOptions[0].isShown       = false;
      $scope.cueOptions[0].intervalId    = -1;
      $scope.cueOptions[0].countdownTime = $scope.cueOptions[0].cost;
      $scope.cueOptions[1].isShown       = false;
      $scope.cueOptions[1].intervalId    = -1;
      $scope.cueOptions[1].countdownTime = $scope.cueOptions[1].cost;
      
      $scope.currentPattern = $scope.pairComparisons[randomizer.numberBetween(0,3)].pattern;
    };
    
    function getCueScore(index, share) {
      if (share === 'A')
      {
        return $scope.currentPattern[index];
      }
      else
      {
        return 1 - $scope.currentPattern[index];
      }
    }
    
    function getMaxLocalAccuracy() {
      var accuracy1 = 0;
      var accuracy2 = 0;
      
      angular.forEach($scope.cueOptions, function(value, key) {
        accuracy1 += value.weight * value.isShown * getCueScore(key, 'A');
        accuracy2 += value.weight * value.isShown * getCueScore(key, 'B');
      });
      
      return Math.max(accuracy1, accuracy2);
    }
    
  });
