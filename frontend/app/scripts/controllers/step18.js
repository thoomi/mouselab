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
        //if (!dataService.everythingIsValid()) { $location.path(''); }
    
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
              title : 'Wenn ich fernsehe, zappe ich durch die Programme und überfliege oft die zur Verfügung stehenden Alternativen, sogar wenn ich eigentlich eine bestimmte Sendung sehen möchte.',
              label : 'max-questions-1',
              value : 0
          },
          {
              id    : 2,
              title : 'Wenn ich im Auto Radio höre, prüfe ich oft die anderen Radiosender daraufhin, ob etwas besseres gespielt wird, sogar wenn ich relativ zufrieden mit dem bin, was ich gerade höre.',
              label : 'max-questions-2',
              value : 0
          },
          {
              id    : 3,
              title : 'Mit Beziehungen ist es wie mit Kleidungsstücken: Ich gehe davon aus, dass ich viele ausprobieren muss, bevor ich die perfekte Passung finde.',
              label : 'max-questions-3',
              value : 0
          },
          {
              id    : 4,
              title : 'Egal wie zufrieden ich mit meinem Beruf bin, es ist immer sinnvoll, nach besseren Optionen Ausschau zu halten.',
              label : 'max-questions-4',
              value : 0
          },
          {
              id    : 5,
              title : 'Ich fantasiere oft darüber, ein Leben zu leben, das sich sehr von meinem jetzigen unterscheidet.',
              label : 'max-questions-5',
              value : 0
          },
          {
              id    : 6,
              title : 'Ich bin ein großer Freund von Ranglisten (die besten Filme, die besten Sänger, die besten Sportler, die besten Bücher, ...).',
              label : 'max-questions-6',
              value : 0
          }
        ];

        randomizer.shuffleArray($scope.maximisingQuestions);
  });
