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
    $scope.stressQuestions8 = -1;
    $scope.me4Question = -1;
   
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
      taskQuestionData.stressAnswers = [];
      angular.forEach($scope.stressQuestions, function(question) {
          taskQuestionData.stressAnswers[parseInt(question.id - 1)] = question.value;
      });
      
      var endDate = new Date();
      $scope.endTime = endDate.getTime();
      
      taskQuestionData.timeToAnswer = $scope.endTime - $scope.startTime;

      taskQuestionData.stressAnswer8 = $scope.stressQuestions8;
      taskQuestionData.me4Answer     = $scope.me4Question;
      
      console.log(taskQuestionData.stressAnswer8);
      console.log(taskQuestionData.me4Answer);
      
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
                  $location.path('getReady');
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
        title : 'Ich empfand deutlichen Zeitstress als ich meine Entscheidungen getroffen habe.',
        label : 'stress-questions-1',
        value : 0
      },
      {
        id    : 2,
        title : 'Ich war angespannt/nervös, während ich meine Entscheidungen treffen musste.',
        label : 'stress-questions-2',
        value : 0
      },
      {
        id    : 3,
        title : 'Ich empfand mich als hektisch, während ich meine Entscheidungen treffen musste.',
        label : 'stress-questions-3',
        value : 0
      }
    ];
    
    randomizer.shuffleArray($scope.stressQuestions);
    
    
    
    $scope.instructions =['views/partials/instruction1.html', 'views/partials/instruction2.html'];
    $scope.currentInstruction = 0;
    
    $scope.changeInstruction = function(index) {
      $scope.currentInstruction = index;
    };
  });
