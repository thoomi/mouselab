'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:Step11Ctrl
 * @description
 * # Step11Ctrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('Step11Ctrl', function ($scope, $location, dataService, randomizer) {
    if (!dataService.everythingIsValid()) { $location.path(''); }
    dataService.incrementSiteNumber();
    
    
    $scope.allQuestionsAnswered = false;
    $scope.currentRound         = dataService.getCurrentRound();
    $scope.notWaitingForRequestToFinish = true;
    $scope.labelLeft = 'trifft nicht zu';
    $scope.labelRight = 'trifft zu';
   
    var startDate = new Date();
    $scope.startTime = startDate.getTime();
    
    
    $scope.checkAllSet = function() {
        var allSet = true;

        angular.forEach($scope.stressQuestions, function(question) {
          if (question.value === 0)
          {
            allSet = false;
          }
        });

        $scope.allQuestionsAnswered = allSet;
      };
    
    $scope.onFormSubmit = function() {
      if (!$scope.allQuestionsAnswered) { return; } 
      
      $scope.notWaitingForRequestToFinish = false;

      var taskQuestionData = {};
      
      // Save all Answers into an array and calculate the sum
      taskQuestionData.stressAnswers    = [];
      taskQuestionData.stressAnswersSum = 0;
      angular.forEach($scope.stressQuestions, function(question) {
        taskQuestionData.stressAnswers[parseInt(question.id - 1)] = question.value;
        taskQuestionData.stressAnswersSum += parseInt(question.value);
      });
      
      var endDate = new Date();
      $scope.endTime = endDate.getTime();
      
      taskQuestionData.timeToAnswer = $scope.endTime - $scope.startTime;

      dataService.saveStressQuestions(taskQuestionData, function(error) {
          if (!error)
          {
              $scope.notWaitingForRequestToFinish = true;

              if (dataService.isLastRound())
              {
                  $location.path('step16');
              }
              else
              {
                  dataService.startNextRound();
                  $location.path('step10');
              }
          }
          else
          {
              console.log(error);
              // TODO: Handle error properly
          }
      });
    };
    
    
    $scope.stressQuestions = [
      {
        id    : 1,
        title : 'Ich empfand deutlichen Zeitstress als ich meine Entscheidung getroffen habe.',
        label : 'stress-questions-1',
        value : 0
      },
      {
        id    : 2,
        title : 'Ich war angespannt/nervös, während ich meine Entscheidung treffen musste.',
        label : 'stress-questions-2',
        value : 0
      },
      {
        id    : 3,
        title : 'Ich empfand mich als hektisch, während ich meine Entscheidung treffen musste.',
        label : 'stress-questions-3',
        value : 0
      },
      {
        id    : 4,
        title : 'Die Anzahl an Unternehmen empfand ich als zu hoch.',
        label : 'stress-questions-4',
        value : 0
      },
      {
        id    : 5,
        title : 'Die Anzahl an Eigenschaften der Unternehmen empfand ich als zu hoch',
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
    
    randomizer.shuffleArray($scope.stressQuestions);
    
  });
