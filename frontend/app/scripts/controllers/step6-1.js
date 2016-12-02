'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:Step6-1Ctrl
 * @description
 * # Step6Ctrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('Step6-1Ctrl', function ($scope, $location, dataService, randomizer) {
    if (!dataService.everythingIsValid()) { $location.path(''); }
    
    dataService.incrementSiteNumber();
    
    $scope.allQuestionsAnswered = false;
    $scope.notWaitingForRequestToFinish = true;
    
    $scope.metaQuestion1 = 0;
    
    
    $scope.checkAllSet = function() {
        console.log($scope.metaQuestion1);
        if ($scope.metaQuestion1)
        {
            $scope.allQuestionsAnswered = true;
        }
    };
    
    $scope.onFormSubmit = function() {
        if (!$scope.allQuestionsAnswered) { return; } 
        
        dataService.setMetaAnswer($scope.metaQuestion1);
        $location.path('step6');
    };
    
    $scope.metaQuestion1Answers = [
      {
        title : 'viele	Informationen	recherchiert	und	dadurch	wenige	aber	dafür	sichere	Aktienkäufe	tätigt',
        value : 1
      },
      {
        title : 'wenige	Informationen	recherchiert	und	dadurch	viele	aber	dafür	unsichere	Aktienkäufe	tätigt',
        value : 2
      },
      {
        title : 'Es	ist	egal	wie	man	Aktien	kauft,	es	macht	am	Ende	des	Arbeitstages	keinen	Unterschie',
        value : 3
      },
      {
        title : 'Ich	weiß	nicht	wie	man	Aktien	kaufen	soll,	um	möglichst	viele	Punkte	zu	bekommen',
        value : 4
      }
    ];
    
    randomizer.shuffleArray($scope.metaQuestion1Answers);
  });
