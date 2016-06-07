'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:Step16Ctrl
 * @description
 * # Step16Ctrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('Step16Ctrl', function ($scope, $location, dataService) {
    if (!dataService.everythingIsValid()) { $location.path(''); }

    dataService.incrementSiteNumber();

        $scope.age           = 0;
        $scope.gender        = '';
        $scope.graduation    = 0;
        $scope.currentStatus = 0;
        $scope.apprenticeship = '';
        $scope.academicDegree = 0;
        $scope.notWaitingForRequestToFinish = true;

        $scope.onFormSubmit = function() {
            if (!$scope.age || !$scope.gender || !$scope.graduation || !$scope.currentStatus || !$scope.apprenticeship || !$scope.academicDegree)
            {
                return false;
            }
            
            $scope.notWaitingForRequestToFinish = false;

            dataService.saveDemographicData($scope.age, $scope.gender, $scope.graduation, $scope.currentStatus, $scope.apprenticeship, $scope.academicDegree, function(error) {
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
  });
