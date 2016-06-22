'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:Step17Ctrl
 * @description
 * # Step16Ctrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('Step17Ctrl', function ($scope, $location, dataService) {
    if (!dataService.everythingIsValid()) { $location.path(''); }

    dataService.incrementSiteNumber();

        $scope.age           = 0;
        $scope.gender        = '';
        $scope.graduation    = 0;
        $scope.currentStatus = 0;
        $scope.apprenticeship = '';
        $scope.academicDegree = 0;
        $scope.psychoStudies = '';
        $scope.notWaitingForRequestToFinish = true;

        $scope.onFormSubmit = function() {
            if (!$scope.age || !$scope.gender || !$scope.graduation || !$scope.currentStatus || !$scope.apprenticeship || !$scope.academicDegree || !$scope.psychoStudies)
            {
                return false;
            }
            
            $scope.notWaitingForRequestToFinish = false;
            
            var academicDegree =  $scope.academicDegree - 1;
            
            dataService.saveDemographicData($scope.age, $scope.gender, $scope.graduation, $scope.currentStatus, $scope.apprenticeship, academicDegree, $scope.psychoStudies, function(error) {
                if (!error)
                {
                    $scope.notWaitingForRequestToFinish = true;
                    $location.path(dataService.getNextQuestionSet());
                }
                else
                {
                    console.log(error);
                    // TODO: Handle error properly
                }
            });
        };
  });
