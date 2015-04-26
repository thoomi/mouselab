'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:TaskquestionsCtrl
 * @description
 * # TaskquestionsCtrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('TaskquestionsCtrl', function ($scope, $location, dataService) {
      if (!dataService.everythingIsValid()) { $location.path(''); }

      $scope.allQuestionsAnswered = false;
      $scope.decidedByStrategy    = null;
      $scope.currentRound         = dataService.getCurrentRound();
      $scope.notWaitingForRequestToFinish = true;
      $scope.labelLeft = 'trifft nicht zu';
      $scope.labelRight = 'trifft zu';


      $scope.checkAllSet = function() {
        var allSet = true;

        if ($scope.decidedByStrategy === null)
        {
          allSet = false;
        }

        angular.forEach($scope.stressQuestions, function(question) {
          if (question.value === 0)
          {
            allSet = false;
          }
        });

        angular.forEach($scope.satisfyQuestions, function(question) {
          if (question.value === 0) {
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

    $scope.satisfyQuestions = [
      {
        title : 'Ich bin mit der Art und Weise wie ich die Entscheidung treffen musste zufrieden.',
        label : 'satisfy-questions-1',
        value : 0
      },
      {
        title : 'Ich konnte die Art und Weise wie ich Entscheidungen treffen musste sehr gut anwenden.',
        label : 'satisfy-questions-2',
        value : 0
      },
      {
        title : 'Ich bin mit dem Waschmittel das ich gewählt habe zufrieden.',
        label : 'satisfy-questions-3',
        value : 0
      }
    ];

    $scope.stressQuestions = [
      {
        title : 'Ich empfand deutlichen Zeitstress als ich meine Entscheidung getroffen habe.',
        label : 'stress-questions-1',
        value : 0
      },
      {
        title : 'Ich war angespannt/nervös, während ich meine Entscheidung treffen musste',
        label : 'stress-questions-2',
        value : 0
      },
      {
        title : 'Ich empfand mich als hektisch, während ich meine Entscheidung treffen musste',
        label : 'stress-questions-3',
        value : 0
      },
      {
        title : 'Die Anzahl an Waschmitteln empfand ich als zu hoch',
        label : 'stress-questions-4',
        value : 0
      },
      {
        title : 'Die Anzahl an Eigenschaften der Waschmittel empfand ich als zu hoch',
        label : 'stress-questions-5',
        value : 0
      },
      {
        title : 'Die Entscheidungszeit empfand ich als zu kurz',
        label : 'stress-questions-6',
        value : 0
      },
      {
        title : 'Ich hatte nicht ausreichend Zeit zum nachdenken',
        label : 'stress-questions-7',
        value : 0
      }
    ];
  });
