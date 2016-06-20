'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:Step18Ctrl
 * @description
 * # Step17Ctrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('Step18Ctrl', function ($scope, $location, Fullscreen, dataService, randomizer) {
    if (!dataService.everythingIsValid()) { $location.path(''); }
    
    dataService.incrementSiteNumber();

        $scope.labelLeft = 'stimme nicht zu';
        $scope.labelRight = 'stimme völlig zu';

        $scope.allQuestionsAnswered = false;
        $scope.notWaitingForRequestToFinish = true;


        $scope.checkAllSet = function() {

            var allSet = true;

            angular.forEach($scope.maximisingQuestions, function(question) {
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

            // Save all Answers into an array and calculate the sum
            var answerValues = [];
            var sumAnswers   = 0;
            angular.forEach($scope.maximisingQuestions, function(question) {
                answerValues[parseInt(question.id - 1)] = question.value;
                sumAnswers += parseInt(question.value);
            });

            dataService.saveMaximisingAnswers(answerValues, sumAnswers, function(error) {
                if (!error)
                {
                    $scope.notWaitingForRequestToFinish = true;
                    $location.path(dataService.getNextQuestionSet());
                }
                else
                {
                    console.log(error);
                    // TODO: Handle error properly
                }
            });
        };


        $scope.maximisingQuestions = [
          {
              id    : 1,
              title : '	Wenn	ich	Pläne	habe,	verfolge	ich	sie	auch.	',
              label : 'max-questions-1',
              value : 0
          },
          {
              id    : 2,
              title : 'Normalerweise	schaffe	ich	alles	irgendwie.	',
              label : 'max-questions-2',
              value : 0
          },
          {
              id    : 3,
              title : '	Es	ist	mir	wichtig,	an	vielen	Dingen	interessiert	zu	bleiben.	',
              label : 'max-questions-3',
              value : 0
          },
          {
              id    : 4,
              title : '	Ich	mag	mich.	',
              label : 'max-questions-4',
              value : 0
          },
          {
              id    : 5,
              title : '	Ich	kann	mehrere	Dinge	gleichzeitig	bewältigen.	',
              label : 'max-questions-5',
              value : 0
          },
          {
              id    : 6,
              title : 'Ich	bin	entschlossen.	',
              label : 'max-questions-6',
              value : 0
          },
          {
              id    : 7,
              title : '	Ich	behalte	an	vielen	Dingen	Interesse.	',
              label : 'max-questions-7',
              value : 0
          },
          {
              id    : 8,
              title : '	Ich	finde	öfters	etwas,	worüber	ich	lachen	kann.	',
              label : 'max-questions-8',
              value : 0
          },
          {
              id    : 9,
              title : '	Normalerweise	kann	ich	eine	Situation	aus	mehreren	Perspektiven	betrachten.	',
              label : 'max-questions-9',
              value : 0
          },
          {
              id    : 10,
              title : '	Ich	kann	mich	auch	überwinden,	Dinge	zu	tun,	die	ich	eigentlich	nicht	machen	will.	',
              label : 'max-questions-10',
              value : 0
          },
          {
              id    : 11,
              title : '	In	mir	steckt	genügend	Energie,	um	alles	zu	machen,	was	ich	machen	muss.	',
              label : 'max-questions-11',
              value : 0
          }
        ];

        randomizer.shuffleArray($scope.maximisingQuestions);
  });
