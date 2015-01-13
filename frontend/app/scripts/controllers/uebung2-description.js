'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:Uebung2DescriptionCtrl
 * @description
 * # Uebung2DescriptionCtrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('Uebung2DescriptionCtrl', function ($location, dataService) {
      if (!dataService.everythingIsValid()) { $location.path(''); }
  });
