'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:TaskquestionsCtrl
 * @description
 * # TaskquestionsCtrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('TaskquestionsCtrl', function ($scope, $location, dataService, randomizer) {
      if (!dataService.everythingIsValid()) { $location.path(''); }

    dataService.incrementSiteNumber();

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

          var taskQuestionData = {};
          taskQuestionData.decidedByStrategy = $scope.decidedByStrategy;

          // Save all Answers into an array and calculate the sum
          taskQuestionData.satisfactionAnswers    = [];
          taskQuestionData.satisfactionAnswersSum = 0;
          angular.forEach($scope.satisfyQuestions, function(question) {
            taskQuestionData.satisfactionAnswers[parseInt(question.id - 1)] = question.value;
            taskQuestionData.satisfactionAnswersSum += parseInt(question.value);
          });

          // Save all Answers into an array and calculate the sum
          taskQuestionData.stressAnswers    = [];
          taskQuestionData.stressAnswersSum = 0;
          angular.forEach($scope.stressQuestions, function(question) {
            taskQuestionData.stressAnswers[parseInt(question.id - 1)] = question.value;
            taskQuestionData.stressAnswersSum += parseInt(question.value);
          });

          dataService.saveStressQuestions(taskQuestionData, function(error) {
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
                      $location.path('taskdescription');
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
        id    : 1,
        title : 'Ich bin mit der Art und Weise wie ich die Entscheidung treffen musste zufrieden.',
        label : 'satisfy-questions-1',
        value : 0
      },
      {
        id    : 2,
        title : 'Ich konnte die Art und Weise wie ich Entscheidungen treffen musste sehr gut anwenden.',
        label : 'satisfy-questions-2',
        value : 0
      },
      {
        id    : 3,
        title : 'Ich bin mit dem Waschmittel das ich gewählt habe zufrieden.',
        label : 'satisfy-questions-3',
        value : 0
      }
    ];

    $scope.stressQuestions = [
      {
        id    : 1,
        title : 'Ich empfand deutlichen Zeitstress als ich meine Entscheidung getroffen habe.',
        label : 'stress-questions-1',
        value : 0
      },
      {
        id    : 2,
        title : 'Ich war angespannt/nervös, während ich meine Entscheidung treffen musste',
        label : 'stress-questions-2',
        value : 0
      },
      {
        id    : 3,
        title : 'Ich empfand mich als hektisch, während ich meine Entscheidung treffen musste',
        label : 'stress-questions-3',
        value : 0
      },
      {
        id    : 4,
        title : 'Die Anzahl an Waschmitteln empfand ich als zu hoch',
        label : 'stress-questions-4',
        value : 0
      },
      {
        id    : 5,
        title : 'Die Anzahl an Eigenschaften der Waschmittel empfand ich als zu hoch',
        label : 'stress-questions-5',
        value : 0
      },
      {
        id    : 6,
        title : 'Die Entscheidungszeit empfand ich als zu kurz',
        label : 'stress-questions-6',
        value : 0
      },
      {
        id    : 7,
        title : 'Ich hatte nicht ausreichend Zeit zum nachdenken',
        label : 'stress-questions-7',
        value : 0
      }
    ];

    randomizer.shuffleArray($scope.satisfyQuestions);
    randomizer.shuffleArray($scope.stressQuestions);
  });
