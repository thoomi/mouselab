'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:Step6-1Ctrl
 * @description
 * # Step6Ctrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('Step6-1Ctrl', function ($scope, $location, dataService) {
    if (!dataService.everythingIsValid()) { $location.path(''); }
    
    dataService.incrementSiteNumber();
    
    $scope.allQuestionsAnswered = false;
    $scope.notWaitingForRequestToFinish = true;
    
    $scope.metaQuestion1 = 0;
    
    
    $scope.checkAllSet = function() {
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
  });
