'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:Uebung1DescriptionCtrl
 * @description
 * # Uebung1DescriptionCtrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('Uebung1DescriptionCtrl', function ($scope, $location, dataService) {
        if (!dataService.everythingIsValid()) { $location.path(''); }
  });
