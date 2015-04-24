'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('MainCtrl', function ($scope, $location, dataService) {
    dataService.clearAllData();

    $scope.organization = '';


    $scope.onSubmit = function() {
      dataService.setSelectedOrganization($scope.organization);
      $location.path('personal-code');
    };
  });
