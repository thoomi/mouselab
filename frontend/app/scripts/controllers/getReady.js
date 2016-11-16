'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:GetReadyCtrl
 * @description
 * # GetReadyCtrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('GetReadyCtrl', function ($scope, $location, dataService, $timeout) {
    if (!dataService.everythingIsValid()) { $location.path(''); }
    
    $timeout(function() {
       $location.path('step10');
    }, 3800);
  });
 