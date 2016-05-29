'use strict';

/**
 * @ngdoc overview
 * @name mouselabApp
 * @description
 * # mouselabApp
 *
 * Main module of the application.
 */
angular
  .module('mouselabApp', [
    'ngAnimate',
    'ngResource',
    'ngRoute',
    'angular-progress-arc',
    'timer',
    'FBAngular'
  ])
  .config(function ($routeProvider) {
    $routeProvider
      .when('/', {
        templateUrl: 'views/step1.html',
        controller: 'Step1Ctrl'
      })
      .when('/step2', {
        templateUrl: 'views/step2.html',
        controller: 'Step2Ctrl'
      })
      .when('/step3', {
        templateUrl: 'views/step3.html',
        controller: 'Step3Ctrl'
      })
      .when('/step4', {
        templateUrl: 'views/step4.html',
        controller: 'Step4Ctrl'
      })
      .when('/step5', {
        templateUrl: 'views/step5.html',
        controller: 'Step5Ctrl' 
      })
      .when('/step6', {
        templateUrl: 'views/step6.html',
        controller: 'Step6Ctrl'
      })
      .when('/step7', {
        templateUrl: 'views/step7.html',
        controller: 'Step7Ctrl'
      })
      .when('/step8', {
        templateUrl: 'views/step8.html',
        controller: 'Step8Ctrl'
      })
      .when('/step9', {
        templateUrl: 'views/step9.html',
        controller: 'Step9Ctrl'
      })
      .when('/step10', {
        templateUrl: 'views/step10.html',
        controller: 'Step10Ctrl'
      })
      .when('/step11', {
        templateUrl: 'views/step11.html',
        controller: 'Step11Ctrl'
      })
      .when('/step16', {
        templateUrl: 'views/step16.html',
        controller: 'Step16Ctrl'
      })
      .when('/step17', {
        templateUrl: 'views/step17.html',
        controller: 'Step17Ctrl'
      })
      .when('/step18', {
        templateUrl: 'views/step18.html',
        controller: 'Step18Ctrl'
      })
      .when('/step19', {
        templateUrl: 'views/step19.html',
        controller: 'Step19Ctrl'
      })
      .when('/step20', {
        templateUrl: 'views/step20.html',
        controller: 'Step20Ctrl'
      })
      .otherwise({
        redirectTo: '/'
      });
  });