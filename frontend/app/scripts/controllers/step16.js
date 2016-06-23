'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:Step16Ctrl
 * @description
 * # Step6Ctrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('Step16Ctrl', function ($scope, $location, dataService, randomizer) {
    if (!dataService.everythingIsValid()) { $location.path(''); }
    dataService.incrementSiteNumber();
    
    
    $scope.notWaitingForRequestToFinish = true;
    
    $scope.currentRiskExcercise = 1;
    $scope.investmentAmount = '';
    
    
    $scope.chooseRisk = function(value) {
        $scope.riskQuestions[$scope.currentRiskExcercise - 1].value = value;
        $scope.currentRiskExcercise++;
    };
    
    $scope.onFormSubmit = function() {
        
        console.log($scope.investmentAmount);
        
        if ($scope.investmentAmount === '') { return; } 
            
        $scope.notWaitingForRequestToFinish = false;
         
        var riskValues = [];
        angular.forEach($scope.riskQuestions, function(question) {
            riskValues[parseInt(question.id - 1)] = question.value;
        });
         
        riskValues[3] = $scope.investmentAmount;
    
        dataService.saveRiskAnswers(riskValues, function(error) {
            if (!error)
            {
                $scope.notWaitingForRequestToFinish = true;
                $location.path('step17');
            }
            else
            {
                console.log(error);
                // TODO: Handle error properly
            }
        });
    };
    
    $scope.riskQuestions = [
        {
            id: 1,
            value: 0
        },
        {
            id: 2,
            value: 0
        },
        {
            id: 3,
            value: 0
        }
    ];
    
    //randomizer.shuffleArray($scope.riskQuestions);
  });
