'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:Step21Ctrl
 * @description
 * # Step21Ctrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('Step21Ctrl', function ($scope, $location, dataService, randomizer) {
    if (!dataService.everythingIsValid()) { $location.path(''); }
    
        dataService.incrementSiteNumber();

        $scope.labelLeft   = 'trifft überhaupt nicht zu';
        $scope.labelRight  = 'triff ganz genau zu';
        $scope.labelCenter = 'weder noch';

        $scope.allQuestionsAnswered = false;
        $scope.notWaitingForRequestToFinish = true;


        $scope.checkAllSet = function() {

            var allSet = true;

            angular.forEach($scope.nfcQuestions, function(question) {
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
            angular.forEach($scope.nfcQuestions, function(question) {
                answerValues[parseInt(question.id - 1)] = question.value;
                sumAnswers += parseInt(question.value);
            });

            dataService.saveNfcQuestions(answerValues, sumAnswers, function(error) {
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


        $scope.nfcQuestions = [
          {
              id    : 1,
              title : 'Es	genügt	mir	einfach	die	Antwort	zu	kennen,	ohne	Gründe	für	die	Antwort	des	Problems	zu	verstehen.',
              label : 'nfc-questions-1',
              value : 0
          },
          {
              id    : 2,
              title : 'Ich	habe	es	gern,	wenn	mein	Leben	voller	kniffliger	Aufgaben	ist,	die	ich	lösen	muss.',
              label : 'nfc-questions-2',
              value : 0
          },
          {
              id    : 3,
              title : 'Ich	würde	komplizierte	Probleme	einfachen	Problemen	vorziehen.	',
              label : 'nfc-questions-3',
              value : 0
          },
          {
              id    : 4,
              title : 'In	erster	Linie	denke	ich,	weil	ich	muss.',
              label : 'nfc-questions-4',
              value : 0
          }
        ];

        randomizer.shuffleArray($scope.nfcQuestions);
  });
