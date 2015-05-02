'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:ParticipationQuestionsCtrl
 * @description
 * # ParticipationQuestionsCtrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('ParticipationQuestionsCtrl', function ($scope, $location, dataService, randomizer) {
    if (!dataService.everythingIsValid()) { $location.path(''); }

    $scope.allQuestionsAnswered = false;
    $scope.notWaitingForRequestToFinish = true;
    $scope.labelLeft = 'Stimme gar nicht zu';
    $scope.labelRight = 'Stimme voll und ganz zu';


    $scope.checkAllSet = function() {
      var allSet = true;


      angular.forEach($scope.environmentQuestions, function(question) {
        if (question.value === 0)
        {
          allSet = false;
        }
      });

      angular.forEach($scope.participantQuestions, function(question) {
        if (question.value === null) {
          allSet = false;
        }
      });

      $scope.allQuestionsAnswered = allSet;
    };


    $scope.onFormSubmit = function() {
      $scope.notWaitingForRequestToFinish = false;

      var taskQuestionData = {};
      // Save all Answers into an array and calculate the sum
      taskQuestionData.environmentAnswers = [];
      angular.forEach($scope.environmentQuestions, function(question) {
        taskQuestionData.environmentAnswers[parseInt(question.id - 1)] = question.value;
      });

      // Save all Answers into an array and calculate the sum
      taskQuestionData.participantAnswers = [];
      angular.forEach($scope.participantQuestions, function(question) {
        taskQuestionData.participantAnswers[parseInt(question.id - 1)] = question.value;
      });


      dataService.endTime();

      dataService.saveParticipationQuestions(taskQuestionData, function(error) {
        if (!error)
        {
          $scope.notWaitingForRequestToFinish = true;
          $location.path('thanks');
        }
        else
        {
          console.log(error);
        }
      });
    };


    $scope.environmentQuestions = [
      {
        id    : 1,
        title : 'Ich wurde während der Studie gestört oder abgelenkt.',
        label : 'environment-questions-1',
        value : 0
      },
      {
        id    : 2,
        title : 'Ich habe ernsthaft und sorgfältig an der Studie teilgenommen.',
        label : 'environment-questions-2',
        value : 0
      },
      {
        id    : 3,
        title : 'Ich achte beim Einkauf von Waschmitteln auf verschiedene Produkteigenschaften.',
        label : 'environment-questions-3',
        value : 0
      }
    ];

    $scope.participantQuestions = [
      {
        id    : 1,
        title : 'Haben Sie während der Studie Hilfsmittel (z.B. Notizzettel, Taschenrechner) benutzt?',
        label : 'participant-questions-1',
        value : null
      },
      {
        id    : 2,
        title : 'Ich schätze mich im Kopfrechnen als gut ein.',
        label : 'participant-questions-2',
        value : null
      },
      {
        id    : 3,
        title : 'Ich kann mir Informationen schnell und gut über einen kurzen Zeitraum merken',
        label : 'participant-questions-3',
        value : null
      }
    ];

    randomizer.shuffleArray($scope.environmentQuestions);
    randomizer.shuffleArray($scope.participantQuestions);
  });
