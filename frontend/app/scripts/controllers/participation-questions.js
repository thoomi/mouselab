'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:ParticipationQuestionsCtrl
 * @description
 * # ParticipationQuestionsCtrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('ParticipationQuestionsCtrl', function ($scope, $location, dataService) {
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

      dataService.saveStressQuestions($scope.valueQuestion1, $scope.valueQuestion2, function(error) {
        if (!error)
        {
          $scope.notWaitingForRequestToFinish = true;

          if (dataService.isLastRound())
          {
            $location.path('demographics');
          }
          else
          {
            dataService.startNextRound();
            $location.path('taskdecision');
          }
        }
        else
        {
          console.log(error);
          // TODO: Handle error properly
        }
      });
    };


    $scope.environmentQuestions = [
      {
        title : 'Ich wurde während der Studie gestört oder abgelenkt.',
        label : 'environment-questions-1',
        value : 0
      },
      {
        title : 'Ich habe ernsthaft und sorgfältig an der Studie teilgenommen.',
        label : 'environment-questions-2',
        value : 0
      },
      {
        title : 'Ich achte beim Einkauf von Waschmitteln auf verschiedene Produkteigenschaften.',
        label : 'environment-questions-3',
        value : 0
      }
    ];

    $scope.participantQuestions = [
      {
        title : 'Haben Sie während der Studie Hilfsmittel (z.B. Notizzettel, Taschenrechner) benutzt?',
        label : 'participant-questions-1',
        value : null
      },
      {
        title : 'Ich schätze mich im Kopfrechnen als gut ein.',
        label : 'participant-questions-2',
        value : null
      },
      {
        title : 'Ich kann mir Informationen schnell und gut über einen kurzen Zeitraum merken',
        label : 'participant-questions-3',
        value : null
      }
    ];
  });
