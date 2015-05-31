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
        templateUrl: 'views/main.html',
        controller: 'MainCtrl'
      })
      .when('/uebung1', {
        templateUrl: 'views/uebung1.html',
        controller: 'Uebung1Ctrl'
      })
      .when('/demographics', {
        templateUrl: 'views/demographics.html',
        controller: 'DemographicsCtrl'
      })
      .when('/maximising', {
        templateUrl: 'views/maximising.html',
        controller: 'MaximisingCtrl'
      })
      .when('/thanks', {
        templateUrl: 'views/thanks.html',
        controller: 'ThanksCtrl'
      })
      .when('/taskdescription', {
        templateUrl: 'views/taskdescription.html',
        controller: 'TaskdescriptionCtrl'
      })
      .when('/taskdecision', {
        templateUrl: 'views/taskdecision.html',
        controller: 'TaskdecisionCtrl'
      })
      .when('/taskquestions', {
        templateUrl: 'views/taskquestions.html',
        controller: 'TaskquestionsCtrl'
      })
      .when('/uebung1-description', {
        templateUrl: 'views/uebung1-description.html',
        controller: 'Uebung1DescriptionCtrl'
      })
      .when('/uebung2-description', {
        templateUrl: 'views/uebung2-description.html',
        controller: 'Uebung2DescriptionCtrl'
      })
      .when('/uebung2', {
        templateUrl: 'views/uebung2.html',
        controller: 'Uebung2Ctrl'
      })
      .when('/personal-code', {
        templateUrl: 'views/personal-code.html',
        controller: 'PersonalCodeCtrl'
      })
      .when('/attribute-weights', {
        templateUrl: 'views/attribute-weights.html',
        controller: 'AttributeWeightsCtrl'
      })
      .when('/decision-strategy', {
        templateUrl: 'views/decision-strategy.html',
        controller: 'DecisionStrategyCtrl'
      })
      .when('/participation-questions', {
        templateUrl: 'views/participation-questions.html',
        controller: 'ParticipationQuestionsCtrl'
      })
      .when('/washing-powders', {
        templateUrl: 'views/washing-powders.html',
        controller: 'WashingPowdersCtrl'
      })
      .when('/common-decision-strategy', {
        templateUrl: 'views/common-decision-strategy.html',
        controller: 'CommonDecisionStrategyCtrl'
      })
      .otherwise({
        redirectTo: '/'
      });
  });