'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:DemographicsCtrl
 * @description
 * # DemographicsCtrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('DemographicsCtrl', function ($scope, $location, dataService) {
        if (!dataService.everythingIsValid()) { $location.path(''); }

        $scope.age        = 0;
        $scope.gender     = '';
        $scope.graduation = 0;
        $scope.notWaitingForRequestToFinish = true;

        $scope.onFormSubmit = function() {
            $scope.notWaitingForRequestToFinish = false;

            dataService.saveDemographicData($scope.age, $scope.gender, $scope.graduation, function(error) {
                if (!error)
                {
                    $scope.notWaitingForRequestToFinish = true;
                    $location.path('maximising');
                }
                else
                {
                    console.log(error);
                    // TODO: Handle error properly
                }
            });
        };
  });
