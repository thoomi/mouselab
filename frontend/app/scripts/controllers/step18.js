'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:MaximisingCtrl
 * @description
 * # MaximisingCtrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('Step18Ctrl', function ($scope, $location, Fullscreen, dataService, randomizer) {
    if (!dataService.everythingIsValid()) { $location.path(''); }
    
    dataService.incrementSiteNumber();

        $scope.labelLeft = 'trifft nicht zu';
        $scope.labelRight = 'trifft zu';

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

            dataService.saveResilienceAnswers(answerValues, sumAnswers, function(error) {
                if (!error)
                {
                    $scope.notWaitingForRequestToFinish = true;
                    $location.path('step19');
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
              title : 'Wenn	ich	im	Auto	Radio	höre,	prüfe	ich	oft	die	anderen	Radiosender	daraufhin,	ob	etwas	besseres	gespielt	wird,	sogar	wenn	ich	relativ	zufrieden	mit	dem	bin,	was	ich	gerade	höre.',
              label : 'max-questions-1',
              value : 0
          },
          {
              id    : 2,
              title : '	Egal	wie	zufrieden	ich	mit	meinem	Beruf	bin,	es	ist	immer	sinnvoll,	nach	besseren	Optionen	Ausschau	zu	halten.	',
              label : 'max-questions-2',
              value : 0
          },
          {
              id    : 3,
              title : '	Es	fällt	mir	häufig	schwer,	ein	Geschenk	für	einen	Freund	zu	kaufen.	',
              label : 'max-questions-3',
              value : 0
          },
          {
              id    : 4,
              title : 'Videos	auszuleihen	ist	sehr	schwierig.	Ich	mühe	mich	stets	damit	ab,	das	Beste	auszusuchen.',
              label : 'max-questions-4',
              value : 0
          },
          {
              id    : 5,
              title : '	Egal	was	ich	tue:	Ich	messe	mich	am	höchsten	Standard.',
              label : 'max-questions-5',
              value : 0
          },
          {
              id    : 6,
              title : '	Ich	gebe	mich	nie	mit	dem	zweitbesten	zufrieden.	',
              label : 'max-questions-6',
              value : 0
          }
        ];

        randomizer.shuffleArray($scope.maximisingQuestions);
  });
