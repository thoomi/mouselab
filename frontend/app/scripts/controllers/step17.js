'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:Step17Ctrl
 * @description
 * # Step17Ctrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('Step17Ctrl', function ($scope, $location, Fullscreen, dataService, randomizer) {
        //if (!dataService.everythingIsValid()) { $location.path(''); }
    
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
                    $location.path('step18');
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
          },
          {
              id    : 7,
              title : 'Es fällt mir häufig schwer, ein Geschenk für einen Freund zu kaufen.',
              label : 'max-questions-7',
              value : 0
          },
          {
              id    : 8,
              title : 'Wenn ich einkaufen gehe, fällt es mir schwer, Kleidungsstücke zu finden, die ich richtig gut finde.',
              label : 'max-questions-8',
              value : 0
          },
          {
              id    : 9,
              title : 'Videos auszuleihen ist sehr schwierig. Ich mühe mich stets damit ab, das Beste auszusuchen.',
              label : 'max-questions-9',
              value : 0
          },
          {
              id    : 10,
              title : ' Ich finde Schreiben schwierig, sogar wenn es darum geht, einem Freund einen Brief zu schreiben. Es ist so schwer, die richtigen Worte zu finden. Auch von einfacheren Sachen mache ich oft mehrere Entwürfe.',
              label : 'max-questions-10',
              value : 0
          },
          {
              id    : 11,
              title : 'Egal was ich tue: Ich messe mich am höchsten Standard.',
              label : 'max-questions-11',
              value : 0
          }
        ];

        randomizer.shuffleArray($scope.maximisingQuestions);
  });
