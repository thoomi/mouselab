'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:MaximisingCtrl
 * @description
 * # MaximisingCtrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('MaximisingCtrl', function ($scope, $location, Fullscreen, dataService) {
        if (!dataService.everythingIsValid()) { $location.path(''); }


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
                answerValues.push(question.value);
                sumAnswers += parseInt(question.value);
            });

            dataService.endTime();


            dataService.saveMaximisingAnswers(answerValues, sumAnswers, function(error) {
                if (!error)
                {
                    $scope.notWaitingForRequestToFinish = true;

                    if (Fullscreen.isEnabled())
                    {
                        Fullscreen.cancel();
                    }

                    $location.path('thanks');
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
              title : 'Wenn ich fernsehe, tappe ich durch die Programme und überfliege oft die zur Verfügung stehenden Alternativen, sogar wenn ich eigentlich eine bestimmte Sendung sehen möchte.',
              label : 'max-questions-1',
              value : 0
          },
          {
              title : 'Wenn ich im Auto Radio höre, prüfe ich oft die anderen Radiosender daraufhin, ob etwas besseres gespielt wird, sogar wenn ich relativ zufrieden mit dem bin, was ich gerade höre.',
              label : 'max-questions-2',
              value : 0
          },
          {
              title : 'Mit Beziehungen ist es wie mit Kleidungsstücken: Ich gehe davon aus, dass ich viele ausprobieren muss, bevor ich die perfekte Passung finde.',
              label : 'max-questions-3',
              value : 0
          },
          {
              title : 'Egal wie zufrieden ich mit meinem Beruf bin, es ist immer sinnvoll, nach besseren Optionen Ausschau zu halten.',
              label : 'max-questions-4',
              value : 0
          },
          {
              title : 'Ich fantasiere oft darüber, ein Leben zu leben, das sich sehr von meinem jetzigen unterscheidet.',
              label : 'max-questions-5',
              value : 0
          },
          {
              title : 'Ich bin ein großer Freund von Ranglisten (die besten Filme, die besten Sänger, die besten Sportler, die besten Bücher, ...).',
              label : 'max-questions-6',
              value : 0
          },
          {
              title : 'Es fällt mir häufig schwer, ein Geschenk für einen Freund zu kaufen.',
              label : 'max-questions-7',
              value : 0
          },
          {
              title : 'Wenn ich einkaufen gehe, fällt es mir schwer, Kleidungsstücke zu finden, die ich richtig gut finde.',
              label : 'max-questions-8',
              value : 0
          },
          {
              title : 'Videos auszuleihen ist sehr schwierig. Ich mühe mich stets damit ab, das Beste auszusuchen.',
              label : 'max-questions-9',
              value : 0
          },
          {
              title : ' Ich finde Schreiben schwierig, sogar wenn es darum geht, einem Freund einen Brief zu schreiben. Es ist so schwer, die richtigen Worte zu finden. Auch von einfacheren Sachen mache ich oft mehrere Entwürfe.',
              label : 'max-questions-10',
              value : 0
          },
          {
              title : 'Egal was ich tue: Ich messe mich am höchsten Standard.',
              label : 'max-questions-11',
              value : 0
          },
          {
              title : 'Ich gebe mich nie mit dem zweitbesten zufrieden.',
              label : 'max-questions-12',
              value : 0
          },
          {
              title : 'Wenn ich eine Entscheidung treffen soll, versuche ich mir alle anderen Möglichkeiten vorzustellen, sogar die, die momentan gar nicht zur Verfügung stehen.',
              label : 'max-questions-13',
              value : 0
          }
        ];

  });
