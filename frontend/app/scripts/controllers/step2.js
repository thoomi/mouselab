'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:Step2Ctrl
 * @description
 * # Step2Ctrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('Step2Ctrl', function ($scope, $location, dataService) {
    if (!dataService.everythingIsValid()) { $location.path(''); }
    
    dataService.incrementSiteNumber();
    
    $scope.disabled = function () {
        if (!$scope.agreedToParticipate)
        {
            return false;
        }
        else
        {
            $location.path('step3');
        }
    };
  });
