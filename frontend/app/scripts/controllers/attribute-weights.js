'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:AttributeWeightsCtrl
 * @description
 * # AttributeWeightsCtrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('AttributeWeightsCtrl', function ($scope, $location, dataService) {
    if (!dataService.everythingIsValid()) { $location.path(''); }


    $scope.labelLeft = 'nicht wichtig';
    $scope.labelRight = 'sehr wichtig';

    $scope.allQuestionsAnswered = false;
    $scope.notWaitingForRequestToFinish = true;


    $scope.checkAllSet = function() {

      var allSet = true;

      angular.forEach($scope.attributeQuestions, function(question) {
        if (question.value === 0)
        {
          allSet = false;
        }
      });

      $scope.allQuestionsAnswered = allSet;
    };

    $scope.onFormSubmit = function() {
      $scope.notWaitingForRequestToFinish = false;

      // Save all Answers into an array and calculate the sum
      var answerValues = [];
      var sumAnswers   = 0;
      angular.forEach($scope.attributeQuestions, function(question) {
        answerValues.push(question.value);
        sumAnswers += parseInt(question.value);
      });


      dataService.saveAttributeAnswers(answerValues, sumAnswers, function(error) {
        if (!error)
        {
          $scope.notWaitingForRequestToFinish = true;
          $location.path('decision-strategy');
        }
        else
        {
          console.log(error);
        }
      });
    };

    $scope.attributeQuestions = [
      {
        title : 'Preis',
        label : 'attribute-questions-1',
        value : 0
      },
      {
        title : 'Schmutzentfernung',
        label : 'attribute-questions-2',
        value : 0
      },
      {
        title : 'Fleckenentfernung',
        label : 'attribute-questions-3',
        value : 0
      },
      {
        title : 'Vergrauung',
        label : 'attribute-questions-4',
        value : 0
      },
      {
        title : 'Farberhaltung',
        label : 'attribute-questions-5',
        value : 0
      },
      {
        title : 'Faserschutz',
        label : 'attribute-questions-6',
        value : 0
      },
      {
        title : 'Umwelteigenschaften',
        label : 'attribute-questions-7',
        value : 0
      }
    ];
  });
